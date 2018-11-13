<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/30
 * Time: 18:51
 */

namespace app\modules\api\controllers;


use app\models\Store;
use app\models\WechatApp;
use app\models\WechatPlatform;
use luweiss\wechat\Wechat;

/**
 * @property Store $store
 * @property WechatApp $wechat_app
 * @property Wechat $wechat 小程序的
 * @property Wechat $wechat_of_platform 公众号的
 */
class Controller extends \app\controllers\Controller
{
    public $store_id;
    public $_acid;
    public $store;
    public $wechat_app;
    public $wechat_platform;
    public $wechat;
    public $wechat_of_platform;


    public function init()
    {
        $this->enableCsrfValidation = false;
        $this->_acid = \Yii::$app->request->get('_acid');
        if(!$this->_acid){
            $this->_acid = \Yii::$app->request->post('_acid');
        }
        if($this->_acid != -1){
            $this->store = Store::findOne(['acid'=>$this->_acid]);
        }
        if(!$this->store){
            $this->store_id = \Yii::$app->request->get('store_id');
            $this->store = Store::findOne(['id'=>$this->store_id]);
        }
        if(!$this->store)
            $this->renderJson([
                'code'=>1,
                'msg'=>'Store Is Null'
            ]);
        $this->store_id = $this->store->id;
        $this->wechat_app = WechatApp::findOne(['id'=>$this->store->wechat_app_id]);
        if(!$this->wechat_app){
            $this->renderJson([
                'code'=>1,
                'msg'=>'Wechat App Is Null'
            ]);
        }

        $this->wechat = new Wechat([
            'appId' => $this->wechat_app->app_id,
            'appSecret' => $this->wechat_app->app_secret,
            'mchId' => $this->wechat_app->mch_id,
            'apiKey' => $this->wechat_app->key,
            'cachePath' => \Yii::$app->runtimePath . '/cache',
            'certPem' => 0,
            'keyPem' => 0,
        ]);

//        $this->setWechatOfPlatform();

        $access_token = \Yii::$app->request->get('access_token');
        if (!$access_token) {
            $access_token = \Yii::$app->request->post('access_token');
        }
        if ($access_token) {
            \Yii::$app->user->loginByAccessToken($access_token);
        }

        parent::init();
    }

    private function setWechatOfPlatform()
    {
        if ($this->store->use_wechat_platform_pay == 1) {
            $this->wechat_platform = WechatPlatform::findOne($this->store->wechat_platform_id);
            if (!$this->wechat_platform)
                $this->renderJson([
                    'code' => 1,
                    'msg' => 'Wechat Platform Is Null',
                ]);

            $cert_pem_file = null;
            if ($this->wechat_platform->cert_pem) {
                $cert_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_platform->cert_pem);
                if (!file_exists($cert_pem_file))
                    file_put_contents($cert_pem_file, $this->wechat_platform->cert_pem);
            }

            $key_pem_file = null;
            if ($this->wechat_platform->key_pem) {
                $key_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_platform->key_pem);
                if (!file_exists($key_pem_file))
                    file_put_contents($key_pem_file, $this->wechat_platform->key_pem);
            }

            $this->wechat_of_platform = new Wechat([
                'appId' => $this->wechat_platform->app_id,
                'appSecret' => $this->wechat_platform->app_secret,
                'mchId' => $this->wechat_platform->mch_id,
                'apiKey' => $this->wechat_platform->key,
                'cachePath' => \Yii::$app->runtimePath . '/cache',
                'certPem' => $cert_pem_file,
                'keyPem' => $key_pem_file,
            ]);
        }
    }
}