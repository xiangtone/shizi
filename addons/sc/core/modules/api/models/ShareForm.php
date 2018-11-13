<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1
 * Time: 11:22
 */

namespace app\modules\api\models;


use app\extensions\GrafikaHelper;
use app\extensions\TimeToDay;
use app\models\Store;
use app\models\Video;
use Curl\Curl;
use Grafika\Color;
use Grafika\Grafika;
use yii\helpers\Html;


class ShareForm extends Model
{
    public $store_id;
    public $video_id;
    public $user_id;

    public function rules()
    {
        return [
            [['video_id'], 'required']
        ];
    }

    public function search()
    {
        $video = Video::findOne(['id' => $this->video_id, 'store_id' => $this->store_id]);
        if (!$video) {
            return [
                'code' => 1,
                'msg' => '视频不存在'
            ];
        }
        $store = Store::findOne($this->store_id);
        $pic = $video->pic_url;
        $pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $pic_save_name = md5("v1.5.0&video_id={$video->id}&video_title={$video->title}&store_name={$store->name}") . '.jpg';
        $pic_url = str_replace('http://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $pic_save_name);
//        $pic_url = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $pic_save_name;

        if (file_exists($pic_save_path . $pic_save_name)) {
            return [
                'code' => 0,
                'data' => [
                    'video_title' => $video->title,
                    'url' => $pic_url . '?v=' . time(),
                ],
            ];
        }
        $pic_path = $this->saveTempImage($pic);
        if (!$pic_path) {
            return [
                'code' => 1,
                'msg' => '获取海报失败：图片丢失'
            ];
        }
        $qrcode_dst = \Yii::$app->basePath . '/web/statics/images/qrcode-dst.jpg';
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';

        $editor = Grafika::createEditor(GrafikaHelper::getSupportEditorLib());
        $editor->open($qrcode_dst_e, $qrcode_dst);
        $editor->open($pic_path_e, $pic_path);

        $wxapp_qrcode_file_res = $this->getQrcode($video->id, $this->user_id);
        if ($wxapp_qrcode_file_res['code'] == 1) {
            unlink($pic_path);
            return [
                'code' => 1,
                'msg' => '获取商品海报失败：获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }
        $wxapp_qrcode_file_path = $wxapp_qrcode_file_res['file_path'];
        $editor->open($wxapp_qrcode, $wxapp_qrcode_file_path);

        //裁剪封面图
        $editor->resizeFill($pic_path_e, 610, 344);
        //加视频封面图
        $editor->blend($qrcode_dst_e, $pic_path_e, 'normal', 1.0, 'top-left', 70, 124);

        $name_size = 21;
        //标题换行处理
        $name = $this->autowrap($name_size, 0, $font_path, $video->title, 590, 1);
        //加标题
        $title_width = $this->textleft($name_size, 0, $font_path, $name);
        $editor->text($qrcode_dst_e, $name, $name_size, $title_width, 541, new Color('#303030'), $font_path, 0);
        //加时长
        $time = TimeToDay::time($video->video_time);
        //获取标题距左
        $time_width = $this->textleft(13, 0, $font_path, '时长　' . $time);
        $editor->text($qrcode_dst_e, '时长　' . $time, 13, $time_width, 607, new Color('#303030'), $font_path, 0);
        //加介绍
        $editor->text($qrcode_dst_e, '介绍', 13, 100, 700, new Color('#303030'), $font_path, 0);
        //加介绍内容
        $content = str_replace(array("\r\n", "\r", "\n",'<br />'),'',$video->content);
        $content = $this->autowrap(12, 0, $font_path, $content, 460, 5);
        $editor->text($qrcode_dst_e, $content, 12, 160, 700, new Color('#303030'), $font_path, 0);

        //调整小程序码图片
        $editor->resizeExactWidth($wxapp_qrcode, 246);
        //加小程序码
        $editor->blend($qrcode_dst_e, $wxapp_qrcode, 'normal', 1.0, 'top-left', 118, 892);
        //加小程序名称
        $editor->text($qrcode_dst_e, '长按小程序码查看', 15, 412, 1002, new Color('#303030'), $font_path, 0);
        $editor->text($qrcode_dst_e, $store->name, 16, 412, 1032, new Color('#303030'), $font_path, 0);


        $editor->resizeExactWidth($qrcode_dst_e, 750);
        $editor->save($qrcode_dst_e, $pic_save_path . $pic_save_name, 'jpeg', 100);

        //删除临时图片
        unlink($pic_path);
        unlink($wxapp_qrcode_file_path);

        return [
            'code' => 0,
            'data' => [
                'title' => $video->title,
                'url' => $pic_url . '?v=' . time(),
            ],
        ];


    }

    private function getQrcode($video_id, $user_id)
    {
        $wechat = $this->getWechat();
        $access_token = $wechat->getAccessToken();
        if (!$access_token) {
            return [
                'code' => 1,
                'msg' => $wechat->errMsg,
            ];
        }
        $api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$access_token}";
//        $curl = new Curl();
//        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $data = json_encode([
            'scene' => "{$video_id}",
            'page' => 'pages/video/video',
            'width' => 246,
        ]);
        \Yii::trace("GET WXAPP QRCODE:" . $data);
        $wechat->curl->post($api, $data);
        if (in_array('Content-Type: image/jpeg', $wechat->curl->response_headers)) {
            //返回图片
            return [
                'code' => 0,
                'file_path' => $this->saveTempImageByContent($wechat->curl->response),
            ];
        } else {
            //返回文字
            $res = json_decode($wechat->curl->response, true);
            return [
                'code' => 1,
                'msg' => $res['errmsg'],
            ];
        }
    }

    //保存图片内容到临时文件
    private function saveTempImageByContent($content)
    {
        $save_path = \Yii::$app->runtimePath . '/image/' . md5(base64_encode($content)) . '.jpg';
        $fp = fopen($save_path, 'w');
        fwrite($fp, $content);
        fclose($fp);
        return $save_path;
    }

    //获取网络图片到临时目录
    private function saveTempImage($url)
    {
        $wdcp_patch = false;
        $wdcp_patch_file = \Yii::$app->basePath . '/patch/wdcp.json';
        if (file_exists($wdcp_patch_file)) {
            $wdcp_patch = json_decode(file_get_contents($wdcp_patch_file), true);
            if ($wdcp_patch && in_array(\Yii::$app->request->hostName, $wdcp_patch)) {
                $wdcp_patch = true;
            } else {
                $wdcp_patch = false;
            }
        }
        if ($wdcp_patch)
            $url = str_replace('http://', 'https://', $url);

        if (!is_dir(\Yii::$app->runtimePath . '/image'))
            mkdir(\Yii::$app->runtimePath . '/image');
        $save_path = \Yii::$app->runtimePath . '/image/' . md5($url) . '.jpg';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $img = curl_exec($ch);
        curl_close($ch);
        $fp = fopen($save_path, 'w');
        fwrite($fp, $img);
        fclose($fp);
        return $save_path;
    }

    /**
     * @param integer $fontsize 字体大小
     * @param integer $angle 角度
     * @param string $fontface 字体名称
     * @param string $string 字符串
     * @param integer $width 预设宽度
     */
    private function autowrap($fontsize, $angle, $fontface, $string, $width, $max_line = null)
    {
        // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
        $content = "";
        // 将字符串拆分成一个个单字 保存到数组 letter 中
        $letter = [];
        for ($i = 0; $i < mb_strlen($string, 'utf-8'); $i++) {
            $letter[] = mb_substr($string, $i, 1, 'utf-8');
        }
        $line_count = 0;
        $line_last = 0;
        foreach ($letter as $l) {
            $teststr = $content . " " . $l;
            $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($testbox[2] > $width) && ($content !== "")) {
                $line_count++;
                if ($max_line && $line_count >= $max_line) {
                    $content = mb_substr($content, 0, -1, 'utf-8') . "...";
                    break;
                }
                $content .= "\n";
            }
            $line_last = $testbox[2];
            $content .= $l;
        }
        return $content;
    }

    /**
     * @param integer $fontsize 字体大小
     * @param integer $angle 角度
     * @param string $fontface 字体名称
     * @param string $string 字符串
     * @return float
     * 获取文字距左距离
     */
    private function textleft($fontsize, $angle, $fontface, $string)
    {
        $text_width = 754;
        $testbox = imagettfbbox($fontsize, $angle, $fontface, $string);
        $text = $testbox[2];
        return ($text_width - $text) / 2;
    }
}