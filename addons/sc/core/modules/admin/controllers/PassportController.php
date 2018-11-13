<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25
 * Time: 19:03
 */

namespace app\modules\admin\controllers;



use app\controllers\Controller;
use app\models\Store;
use app\models\User;
use app\models\WechatApp;

class PassportController extends Controller
{
    public function actionLogin()
    {
        $we7_user = \Yii::$app->session->get('we7_user');
        $we7_account = \Yii::$app->session->get('we7_account');
        if(empty($we7_user) || empty($we7_account)){
            $current_url = \Yii::$app->request->absoluteUrl;
            $key = 'addons/'.WE7_MODULE_NAME.'/core/web';
            $we7_url = mb_substr($current_url,0,stripos($current_url,$key));
            $this->redirect($we7_url)->send();
            exit();
        }
        $user = User::findOne([
            'username'=>$we7_user['username']
        ]);
        if (!$user) {
            $user = new User();
            $user->username = $we7_user['username'];
            $user->type = 0;
            $user->password = \Yii::$app->security->generatePasswordHash(md5(uniqid()));
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->addtime = time();
            $user->nickname = $we7_user['name'];
            $user->avatar_url = '0';
            $user->store_id = 0;
            $user->save();
            if (!$user->save()) {
                \Yii::warning('用户保存失败原因：'.$user->errors);
            }
        }
        \Yii::$app->user->login($user);
        $wechat_app = WechatApp::findOne([
            'acid'=>$we7_account['acid']
        ]);
        if(!$wechat_app){
            $wechat_app = new WechatApp();
            $wechat_app->acid = $we7_account['acid'];
            $wechat_app->user_id = $user->id;
            $wechat_app->name = $we7_account['name'];
            $wechat_app->app_id = '0';
            $wechat_app->app_secret = '0';
            if (!$wechat_app->save()) {
                \Yii::warning('保存失败原因：'.$wechat_app->errors);
            }
        }

        $store = Store::findOne([
            'acid' => $we7_account['acid'],
        ]);
        if (!$store) {
            $store = new Store();
            $store->acid = $we7_account['acid'];
            $store->user_id = $user->id;
            $store->wechat_app_id = $wechat_app->id;
            $store->name = $we7_account['name'];
            if (!$store->save()) {
                \Yii::warning('保存失败原因：'.$store->errors);
            }
        }

        \Yii::$app->session->set('store_id', $store->id);
        $this->redirect(\Yii::$app->urlManager->createUrl(['admin/system/index']))->send();
    }
}