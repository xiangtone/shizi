<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25
 * Time: 20:21
 */

namespace app\modules\admin\controllers;


use app\models\Option;
use app\models\Order;
use app\models\UploadConfig;
use app\models\UploadFile;
use app\modules\admin\models\HomeForm;
use app\modules\admin\models\StoreSettingForm;
use app\modules\admin\models\StoreUploadForm;
use app\modules\admin\models\TplForm;
use Comodojo\Zip\Zip;
use app\models\SmsSetting;
use app\modules\admin\models\SmsForm;

class SystemController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index',[
            'store'=>$this->store
        ]);
    }
    // 系统设置
    public function actionSetting()
    {
        if(\Yii::$app->request->isPost){
            $form = new StoreSettingForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $this->renderJson($form->save());
        }else{
            return $this->render('setting', [
                'store' => $this->store,
                'wechat_app' => $this->wechat_app,
            ]);
        }
    }
    //上传设置
    public function actionUpload()
    {
        $this->checkIsAdmin();
        $model = UploadConfig::findOne([
            'store_id' => 0,
            'is_delete' => 0,
        ]);
        if (!$model) {
            $model = new UploadConfig();
        }
        if (\Yii::$app->request->isPost) {
            $form = new StoreUploadForm();
            $form->attributes = \Yii::$app->request->post();
            $form->model = $model;
            $form->store_id = $this->store->id;
            $this->renderJson($form->save());
        } else {
            $model->aliyun = json_decode($model->aliyun, true);
            $model->qcloud = json_decode($model->qcloud, true);;
            $model->qiniu = json_decode($model->qiniu, true);
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param string $type
     * @param int $page
     * 历史文件
     */
    public function actionUploadFileList($type = 'image', $page = 1)
    {
        $offset = ($page - 1) * 20;
        $list = UploadFile::find()
            ->where(['store_id' => $this->store->id, 'is_delete' => 0, 'type' => $type])
            ->orderBy('addtime DESC')
            ->limit(20)->offset($offset)->asArray()->select('file_url')->all();
        $this->renderJson([
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list,
            ],
        ]);
    }

    //小程序安装
    public function actionWxapp()
    {
        if (\Yii::$app->request->isPost) {
            $this->_wxapp_write_api_file();
            $download_url = $this->_wxapp_zip_dir();
            $this->renderJson([
                'code' => 0,
                'msg' => 'success',
                'data' => $download_url,
            ]);
        } else {
            return $this->render('wxapp');
        }
    }
    //获取小程序二维码
    public function actionWxappQrcode()
    {
        if (\Yii::$app->request->isPost) {
            $save_file = md5($this->wechat->appId . $this->wechat->appSecret) . '.png';
            $save_dir = \Yii::$app->basePath . '/web/temp/' . $save_file;
            $web_dir = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $save_file;
            if (file_exists($save_dir)) {
                $this->renderJson([
                    'code' => 0,
                    'msg' => 'success',
                    'data' => $web_dir,
                ]);
            }
            $access_token = $this->wechat->getAccessToken();
            $api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$access_token}";
            $data = json_encode([
                'scene' => '0',
                'path' => '/pages/index/index',
                'width' => 480,
            ], JSON_UNESCAPED_UNICODE);
            $this->wechat->curl->post($api, $data);
            file_put_contents($save_dir, $this->wechat->curl->response);
            $this->renderJson([
                'code' => 0,
                'msg' => 'success',
                'data' => $web_dir,
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
            ]);
        }
    }

    //1.设置api.js文件
    private function _wxapp_write_api_file()
    {
        $app_root = str_replace('\\', '/', \Yii::$app->basePath) . '/';
        $api_root = str_replace('http://', 'https://', \Yii::$app->request->hostInfo) . \Yii::$app->urlManager->scriptUrl . "?store_id={$this->store->id}&r=api/";
        $api_tpl_file = $app_root . 'wechatapp/api.tpl.js';
        $api_file_content = file_get_contents($api_tpl_file);
        $api_file_content = str_replace('{$_api_root}', $api_root, $api_file_content);
        $api_file = $app_root . 'wechatapp/api.js';
        file_put_contents($api_file, $api_file_content);
    }

    //2.zip打包目录
    private function _wxapp_zip_dir()
    {
        $app_root = str_replace('\\', '/', \Yii::$app->basePath) . '/';
        $wxapp_root = $app_root . 'wechatapp';
        $zip_name = 'wechatapp' . date('Ymd') . rand(1000, 9999) . '.zip';
        if(!is_dir($app_root . 'web/temp')){
            mkdir($app_root . 'web/temp');
        }
        $zip = Zip::create($app_root . 'web/temp/' . $zip_name);
        $zip->add($wxapp_root);
        return \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $zip_name;
    }

    /**
     * @return string
     * 消息模板配置
     */
    public function actionTpl()
    {
        if(\Yii::$app->request->isPost){
            $form = new TplForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->save());
        }else{
            $list = Option::getList(['pay_tpl','refund_tpl'],$this->store->id,'tpl');
            return $this->render('tpl',[
                'list'=>$list
            ]);
        }
    }
    /**
     * 自定义设置
     */
    public function actionHome()
    {
        $list = Option::getGroup($this->store->id,'home');
        if(\Yii::$app->request->isPost){
            $form = new HomeForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->save());
        }else{
            return $this->render('home',[
                'list'=>$list
            ]);
        }
    }

    /**
     * 小程序页面
     */
    public function actionWxappPages()
    {
        return $this->render('wxapp-pages');
    }

    public function actionSms()
    {
        $form = new SmsForm();
        $sms_setting = SmsSetting::findOne(['is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$sms_setting) {
            $sms_setting = new SmsSetting();
        }
        if (\Yii::$app->request->isPost) {
            $form->sms = $sms_setting;
            $form->store_id = $this->store->id;
            $post = \Yii::$app->request->post();
            $form->attributes = $post;
            $this->renderJson($form->save());
        }
        return $this->render('sms',['sms_setting'=>$sms_setting]);
    }
}
