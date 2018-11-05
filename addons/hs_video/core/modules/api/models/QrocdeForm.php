<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 17:28
 */

namespace app\modules\api\models;


use app\models\Store;

class QrocdeForm extends Model
{
    public $store_id;
    public $path;
    public $scene;

    public function rules()
    {
        return [
            [['path','scene'],'trim'],
            [['path','scene'],'string'],
        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }

        $store = Store::findOne(['id'=>$this->store_id]);
        $user_id = \Yii::$app->user->identity->id;
        $save_path = \Yii::$app->request->hostInfo.\Yii::$app->request->baseUrl . '/temp/';
        $pic_save_path = \Yii::$app->basePath . '/web/temp/';
        // 验证缓存目录是否存在不存在创建
        if (!file_exists($pic_save_path)) {
            @mkdir($pic_save_path);
        }
        $save_name =  md5("v1.5.0&store_name={$store->name}&user_id={$user_id}&order_no={$this->scene}") . '.jpg';
        if(file_exists($pic_save_path.$save_name)){
            return [
                'code'=>0,
                'file_path'=>$save_path.$save_name
            ];
        }
        $wechat = $this->getWechat();
        $access_token = $wechat->getAccessToken();
        if(!$access_token){
            return [
                'code'=>1,
                'msg'=>$wechat->errMsg
            ];
        }
        $api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$access_token}";
        $data = json_encode([
            'scene' => "{$this->scene}",
            'page' => $this->path,
            'width' => 246,
        ]);
        \Yii::trace("GET WXAPP QRCODE:" . $data);
        $wechat->curl->post($api, $data);
        if (in_array('Content-Type: image/jpeg', $wechat->curl->response_headers)) {
            //返回图片
            return [
                'code' => 0,
                'file_path' => $this->saveTempImageByContent($wechat->curl->response,$pic_save_path,$save_name,$save_path),
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

    /**
     * @param $content
     * @param $pic_save_path 本地路径
     * @param $save_name 图片名称
     * @param $save_path 网络路径
     * @return string
     */
    private function saveTempImageByContent($content,$pic_save_path,$save_name,$save_path)
    {
        $fp = fopen($pic_save_path.$save_name, 'w');
        fwrite($fp, $content);
        fclose($fp);
        return $save_path.$save_name;
    }
}