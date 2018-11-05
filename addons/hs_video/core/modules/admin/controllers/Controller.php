<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25
 * Time: 19:04
 */

namespace app\modules\admin\controllers;


use app\models\Store;
use app\models\WechatApp;
use luweiss\wechat\Wechat;

class Controller extends \app\controllers\Controller
{
    public $layout = 'main';
    public $store;
    /* @var Wechat*/
    public $wechat;
    public $wechat_app;

    public $is_admin = false;
    public $version;

    public function init()
    {
        parent::init();
        $this->store = Store::findOne([
            'id'=>\Yii::$app->session->get('store_id')
        ]);
        if (empty($this->store)) {
            \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl(['admin/passport/login']))->send();
            \Yii::$app->end();
        }
        $this->wechat_app = WechatApp::findOne(['id'=>$this->store->wechat_app_id]);

        if (!is_dir(\Yii::$app->runtimePath . '/pem')) {
            mkdir(\Yii::$app->runtimePath . '/pem');
            file_put_contents(\Yii::$app->runtimePath . '/pem/index.html', '');
        }
        $cert_pem_file = null;
        if ($this->wechat_app->cert_pem) {
            $cert_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_app->cert_pem);
            if (!file_exists($cert_pem_file))
                file_put_contents($cert_pem_file, $this->wechat_app->cert_pem);
        }
        $key_pem_file = null;
        if ($this->wechat_app->key_pem) {
            $key_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->wechat_app->key_pem);
            if (!file_exists($key_pem_file))
                file_put_contents($key_pem_file, $this->wechat_app->key_pem);
        }
        $this->wechat = new Wechat([
            'appId' => $this->wechat_app->app_id,
            'appSecret' => $this->wechat_app->app_secret,
            'mchId' => $this->wechat_app->mch_id,
            'apiKey' => $this->wechat_app->key,
            'certPem' => $cert_pem_file,
            'keyPem' => $key_pem_file,
        ]);
        if (isset($_SESSION['we7_user']['uid']) && $_SESSION['we7_user']['uid'] == 1)
            $this->is_admin = true;
        $version = json_decode(file_get_contents(\Yii::$app->basePath . '/version.json'));
        $this->version = empty($version->version) ? '未知' : $version->version;

    }

    /**
     * 检查是否是总管理员，不是管理员则转到首页或指定页面
     * @param String $return_url 跳转的页面
     * @return boolean
     */
    public function checkIsAdmin($return_url = null)
    {
        if (!$this->is_admin) {
            $return_url = $return_url ? $return_url : \Yii::$app->urlManager->createUrl(['admin/system/index']);
            $this->redirect($return_url)->send();
            \Yii::$app->end();
        }
        return true;
    }
}