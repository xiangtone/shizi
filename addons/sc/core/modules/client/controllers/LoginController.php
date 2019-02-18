<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/13
 * Time: 22:11
 */
namespace app\modules\client\controllers;

use Yii;
use app\models\User;
use app\models\Order;
use Curl\Curl;

class LoginController extends CommonController
{
    public function beforeAction($action)
    {

        if (parent::beforeAction($action)) {
            //判断用户是否登录
            if ($this->userId) {
                return Yii::$app->response->redirect(['client/site/index']);
            }
            return true;
        }
        return false;
    }

    public function actionIndex()
    {
        if (Yii::$app->request->isGet && Yii::$app->request->get('code')) {
            return;
            // return $this->redirect(['site/index']);
        }
        return $this->render('index');
    }

    //用户授权接口：获取access_token、openId等；获取并保存用户资料到数据库
    public function actionAccesstoken()
    {
        $code = $_GET["code"];
        $state = $_GET["state"];
        $appid = Yii::$app->params['wxPcAppId'];
        $appsecret = Yii::$app->params['wxPcAppSecret'];
        $request_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';
        //初始化一个curl会话
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $request_url);
        // // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        // $result = curl_exec($ch);
        // curl_close($ch);
        // $result = $this->response($result);
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($request_url);
        $res = $curl->response;
        $result = json_decode($res, true);
        // var_dump($result);
        //获取token和openid成功，数据解析
        $access_token = $result['access_token'];
        $refresh_token = $result['refresh_token'];
        $openid = $result['openid'];

        //请求微信接口，获取用户信息
        $wxUserInfo = $this->getUserInfo($access_token, $openid);
        $user = User::findOne(['wechat_union_id' => $wxUserInfo['unionid']]);
        if ($user){
            $checkOrder = Order::find()->where(['user_id' => $user->id,
            'product_type' => 'cat',
            'is_pay' => 1])->one();
            if ($checkOrder){
                $session = Yii::$app->session;
                $session->set("wxUser", $wxUserInfo);
                $session->set("userInfo", $user);
                return Yii::$app->response->redirect(['client/user/index']); 
            }
        }
        return Yii::$app->response->redirect(['client/login/index','reason'=>'noRecord']); 
        // var_dump($wxUserInfo);
    }

    //从微信获取用户资料
    public function getUserInfo($access_token, $openid)
    {
        $request_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        //初始化一个curl会话
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $request_url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $result = curl_exec($ch);
        // curl_close($ch);
        // $result = $this->response($result);
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($request_url);
        $res = $curl->response;
        $result = json_decode($res, true);
        return $result;
    }
    private function response($text)
    {
        return json_decode($text, true);
    }
}
