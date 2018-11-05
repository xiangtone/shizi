<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/8
 * Time: 23:36
 */

namespace app\models;


use Grafika\Grafika;
use Grafika\ImageInterface;
use OSS\OssClient;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * @property UploadConfig $upload_config
 * @property Store $store
 */
class UploadForm extends Model
{
    public $upload_config;
    public $store;

    public $image;
    public $file;
    public $video;

    public static function getMaxUploadSize()
    {
        $upload_max_size = self::get_upload_max_filesize_byte();
        $upload_max_size = intval($upload_max_size / (1024 * 1024));

        $post_max_size = self::get_post_max_filesize_byte();
        $post_max_size = intval($post_max_size / (1024 * 1024));

        return min($upload_max_size, 50, $post_max_size);
    }

    public function rules()
    {
        $min = self::getMaxUploadSize();
        return [
            [['image'], 'file'],
            [['video'], 'file', 'maxSize' => $min * 1024 * 1024, 'message' => '上传视频超过' . $min]
        ];
    }

    private static function get_upload_max_filesize_byte($dec = 2)
    {
        $max_size = ini_get('upload_max_filesize');
        preg_match('/(^[0-9\.]+)(\w+)/', $max_size, $info);
        $size = $info[1];
        $suffix = strtoupper($info[2]);
        $a = array_flip(array("B", "KB", "MB", "GB", "TB", "PB"));
        $b = array_flip(array("B", "K", "M", "G", "T", "P"));
        $pos = $a[$suffix] && $a[$suffix] !== 0 ? $a[$suffix] : $b[$suffix];
        return round($size * pow(1024, $pos), $dec);
    }

    private static function get_post_max_filesize_byte($dec = 2)
    {
        $max_size = ini_get('post_max_size');
        preg_match('/(^[0-9\.]+)(\w+)/', $max_size, $info);
        $size = $info[1];
        $suffix = strtoupper($info[2]);
        $a = array_flip(array("B", "KB", "MB", "GB", "TB", "PB"));
        $b = array_flip(array("B", "K", "M", "G", "T", "P"));
        $pos = $a[$suffix] && $a[$suffix] !== 0 ? $a[$suffix] : $b[$suffix];
        return round($size * pow(1024, $pos), $dec);
    }

    public function saveImage($name = null)
    {
        if (!$name) {
            foreach ($_FILES as $_name => $file)
                $name = $_name;
        }
        if (!$name)
            $name = 'image';
        $this->image = UploadedFile::getInstanceByName($name);
        if (!$this->validate())
            return $this->errors;

        $allow_type = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp',];
        if (!in_array($this->image->extension, $allow_type)) {
            return [
                'code' => 1,
                'msg' => '上传文件格式不正确，请上传' . implode('/', $allow_type) . '格式的图片',
            ];
        }

        $fileMd5 = md5_file($this->image->tempName);
        $saveName = $fileMd5 . '.' . $this->image->extension;
        $saveDir = 'uploads/image/' . substr($saveName, 0, 2) . '/';
        $res = $this->saveFile($this->image->tempName, $saveDir, $saveName);
        if ($res['code'] == 0) {
            $res['data']['extension'] = $this->image->extension;
            $res['data']['type'] = $this->getFileType($this->image->extension);
            $res['data']['size'] = $this->image->size;
        }
        $this->saveData($res);
        return $res;
    }

    /**
     * @param string $file //文件路径
     */
    private function saveFile($file_path, $saveDir, $saveName, $type = 0)
    {
        if (!$this->upload_config || $this->upload_config->storage_type == '') {
            return $this->saveToLocal($file_path, $saveDir, $saveName, $type);
        }
        if ($this->upload_config->storage_type == 'aliyun') {
            return $this->saveToAliyun($file_path, $saveDir, $saveName, $type);
        }
        if ($this->upload_config->storage_type == 'qcloud') {
            return [];
        }
        if ($this->upload_config->storage_type == 'qiniu') {
            return $this->saveToQiniu($file_path, $saveDir, $saveName, $type);
        }
    }

    /**
     * @param string $file_path //文件路径
     */
    private function saveToLocal($file_path, $saveDir, $saveName, $type = 0)
    {
        $webRoot = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/';
        $saveRoot = str_replace('\\', '/', \Yii::$app->basePath) . '/web/';
        if (!is_dir($saveRoot . $saveDir))
            $this->mkdir($saveRoot . $saveDir);
        if (file_exists($saveRoot . $saveDir . $saveName)) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $webRoot . $saveDir . $saveName,
                ],
            ];
        }
//        $file->saveAs($saveRoot . $saveDir . $saveName);
        if ($type == 0) {
            move_uploaded_file($file_path, $saveRoot . $saveDir . $saveName);
            try {
                $this->compressImage($saveRoot . $saveDir . $saveName);
            } catch (\Exception $e) {
                var_dump($e);
            }
        } else {
            try {
                move_uploaded_file($file_path, $saveRoot . $saveDir . $saveName);
            } catch (\Exception $e) {
                var_dump($e);
            }
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'url' => $webRoot . $saveDir . $saveName,
            ],
        ];

    }

    /**
     * @param string $file_path //文件路径
     */
    private function saveToQiniu($file_path, $saveDir, $saveName, $type = 0)
    {
        $config = json_decode($this->upload_config->qiniu, true);
        $auth = new Auth($config['access_key'], $config['secret_key']);
        $token = $auth->uploadToken($config['bucket']);
        $uploader = new UploadManager();
        try{
            list($res, $err) = $uploader->putFile($token, $saveDir . $saveName, $file_path);
        }catch(\Exception $e){
            var_dump($e);
        }
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $url = $config['domain'] . '/' . $saveDir . $saveName;
        if ($type == 0) {
            $url .= ($config['style_api'] ? '?' . $config['style_api'] : '');
        }
        if (!$err) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $url,
                ],
            ];
        }
    }


    private function saveData($res)
    {
        \Yii::trace('===>1');
        if ($res['code'] != 0 || !$this->store)
            return;
        $url = $res['data']['url'];
        $exist = UploadFile::findOne([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'file_url' => $url,
        ]);
        \Yii::trace('===>2');
        if ($exist) {
            $exist->addtime = time();
            $exist->save();
            return;
        }
        $model = new UploadFile();
        $model->store_id = $this->store->id;
        $model->file_url = $url;
        $model->extension = $res['data']['extension'];
        $model->type = $res['data']['type'];
        $model->size = $res['data']['size'];
        $model->addtime = time();
        $model->is_delete = 0;
        \Yii::trace('===>3');
        $model->save();
        \Yii::trace('===>4' . json_encode($model->errors, JSON_UNESCAPED_UNICODE));
    }

    private function getFileType($extension)
    {
        $type_list = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp',],
            'video' => ['mp4', 'flv', 'ogg', 'mov',],
        ];
        foreach ($type_list as $type => $exs) {
            if (in_array($extension, $exs))
                return $type;
        }
        return '';
    }

    private function compressImage($imageFile)
    {
        if (!class_exists('Grafika\Grafika')) {
            \Yii::warning("Class 'Grafika\Grafika' not found");
            return false;
        }
        \Yii::trace("Grafika Start");
        $maxWidth = 1920;
        $maxHeight = 1920;
        $editor = Grafika::createEditor();
        /* @var  ImageInterface $image */
        $editor->open($image, $imageFile);
        $imageWidth = $image->getWidth();
        $imageHeight = $image->getHeight();
        $newWidth = $imageWidth;
        $newHeight = $imageHeight;
        if ($imageWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = intval($newWidth * $imageHeight / $imageWidth);
        }
        if ($newHeight > $maxHeight) {
            $newHeight = $maxHeight;
            $newWidth = intval($newHeight * $imageWidth / $imageHeight);
        }
        $editor->resize($image, $newWidth, $newHeight);
        $editor->save($image, $imageFile, null, 85);
    }

    private function mkdir($dir)
    {
        if (!is_dir($dir)) {
            if (!$this->mkdir(dirname($dir))) {
                return false;
            }
            if (!mkdir($dir)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param null $file_path //文件路径
     * @param null $saveName
     * @return array
     */
    public function saveQrcode($file_path = null, $saveName = null)
    {
        $saveDir = 'uploads/linshi/';
        if (!$this->upload_config || $this->upload_config->storage_type == '') {
            $webRoot = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/';
            try {
                $this->compressImage($file_path);
            } catch (\Exception $e) {
                var_dump($e);
            }
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $webRoot . $saveDir . $saveName,
                ],
            ];
        }
        $res = $this->saveFile($file_path, $saveDir, $saveName);
        return $res;
    }

    public function saveVideo($name = null)
    {
        if (!$name) {
            foreach ($_FILES as $_name => $file)
                $name = $_name;
        }
        if (!$name)
            $name = 'video';
        $this->video = UploadedFile::getInstanceByName($name);
        if (empty($_FILES) || $this->video->error != 0) {
            return [
                'code' => 1,
                'msg' => '上传视频大小超过php配置大小，请检查php上传配置',
                'data' => $_FILES
            ];
        }
        if (!$this->validate())
            return $this->getModelError();

        $allow_type = ['mpg', 'm4v', 'mp4', 'avi', 'rmvb', 'mkv'];
        if (!in_array($this->video->extension, $allow_type)) {
            return [
                'code' => 1,
                'msg' => '上传文件格式不正确，请上传' . implode('/', $allow_type) . '格式的视频',
                'data' => $_FILES,
            ];
        }

        $fileMd5 = md5_file($this->video->tempName);
        $saveRoot = str_replace('\\', '/', \Yii::$app->basePath) . '/web/';
        $webRoot = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/';
        $saveName = $fileMd5 . '.' . $this->video->extension;
        $saveDir = 'uploads/video/' . substr($saveName, 0, 2) . '/';
        if (!is_dir($saveRoot . $saveDir))
            $this->mkdir($saveRoot . $saveDir);
//        if (file_exists($saveRoot . $saveDir . $saveName)) {
//            return [
//                'code' => 0,
//                'msg' => 'success_1',
//                'data' => [
//                    'url' => $webRoot . $saveDir . $saveName,
//                ],
//            ];
//        }
//        $this->video->saveAs($saveRoot . $saveDir . $saveName);
        $res = $this->saveFile($this->video->tempName, $saveDir, $saveName, 1);
        return $res;
    }


    public function saveDVideo($name = null)
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }
        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }
//        @set_time_limit(5 * 60);
        // Get 或 file 方式获取文件名
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        // Uncomment this one to fake upload time
        // usleep(5000);
        // Settings
        // $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
//        $targetDir = './Fadonghui/College/uploadfile/cache/';
//        $uploadDir = './Fadonghui/College/uploadfile/cache/';
        $targetDir = str_replace('\\', '/', \Yii::$app->basePath) . '/web/uploads/cache/';
        $cleanupTargetDir = true; // 开启文件缓存删除
        $maxFileAge = 60 * 60; // 文件缓存时间超过时间自动删除
        // 验证缓存目录是否存在不存在创建
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        $oldName = $fileName;//记录文件原始名字
        $filePath = $targetDir . $fileName;
        // $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // 删除缓存校验
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp|mp4)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
        // 打开并写入缓存文件
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 2048)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");
        $index = 0;
        $done = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        //文件全部上传 执行合并文件
        if ($done) {
            $saveDir = 'uploads/video/' . date('YmdHis', time()) . '/';
            $basePath = str_replace('\\', '/', \Yii::$app->basePath);
            if(!file_exists($basePath.'/web/uploads/video')){
                @mkdir($basePath.'/web/uploads/video');
            }
            $uploadDir = $basePath . '/web/' . $saveDir;
            // 验证缓存目录是否存在不存在创建
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir);
            }
            $pathInfo = pathinfo($fileName);
            $hashStr = substr(md5($pathInfo['basename']), 8, 16);
            $hashName = time() . $hashStr . '.' . $pathInfo['extension'];
            $uploadPath = $uploadDir . $hashName;
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);

//            $response = array(
//                'success' => true,
//                'oldName' => $oldName,
//                //'filePaht'=>$hashName,
//                'filePaht' => substr($uploadPath, 1),
//                'fileSuffixes' => $pathInfo['extension'],
//                'time' =>$ThisFileInfo['playtime_string'],
//            );
            //删除源文件
            /*unlink($uploadPath);*/
            $webRoot = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/';
            if (!$this->upload_config || $this->upload_config->storage_type == '') {
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => [
                        'url' => $webRoot . $saveDir . $hashName,
                    ],
                ];
            }
            $res = $this->saveFile($uploadPath, $saveDir, $hashName,1);
            return $res;
//            return $response;
//            die(json_encode($response));
        }
        // Return Success JSON-RPC response
        return [
            'code' => 0,
            'msg' => 'success',
            'chunk'=>$chunk,
            'chunks'=>$chunks
        ];
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }


    /**
     * 保存到阿里云
     * @param string $file_path //文件路径
     */
    private function saveToAliyun($file_path, $saveDir, $saveName, $type = 0)
    {
        require_once '../extensions/aliyun-oss-php-sdk-2.2.4/autoload.php';
        $config = json_decode($this->upload_config->aliyun, true);
        if($config['CNAME'] == 0){
            $ossClient = new OssClient($config['access_key'],$config['secret_key'],$config['domain']);
        }else{
            $ossClient = new OssClient($config['access_key'],$config['secret_key'],$config['domain'],true);
        }
        list($res, $err) = $ossClient->uploadFile($config['bucket'],$saveDir.$saveName,$file_path);
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        if($config['CNAME'] == 0){
            $arr = explode('//',$config['domain']);
            $url = $arr[0].'//'.$config['bucket'].'.'.$arr[1] . '/' . $saveDir . $saveName;
        }else{
            $url = $config['domain'].'/'.$saveDir.$saveName;
        }
        if ($type == 0) {
            $url .= ($config['style_api'] ? '?' . $config['style_api'] : '');
        }
        if (!$err) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'url' => $url,
                ],
            ];
        }

    }
}