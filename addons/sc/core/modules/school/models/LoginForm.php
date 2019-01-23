<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/13
 * Time: 22:18
 */
namespace app\modules\school\models;

use app\models\School;
use Yii;
use yii\base\Model;
use yii\web\Cookie;
use yii\web\Request;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;
    public $remember;

    private $user;
    const BACKEND_ID = 'backend_id';
    const BACKEND_USERNAME = 'backend_username';
    const BACKEND_LEVEL = 'backend_level';
    const BACKEND_COOKIE = 'backend_remember';

    public function rules()
    {

        return [
            ['username', 'validateAccount', 'skipOnEmpty' => false],
            ['verifyCode', 'captcha', 'captchaAction' => 'school/login/captcha', 'message' => '验证码错误'],
            [['password', 'remember'], 'safe'],
        ];
    }

    /**
     * 验证用户和密码
     */
    public function validateAccount($attribute, $params)
    {
        /*
        //账号输入为2-11位
        if(!preg_match('/^\w{2,11}$/' , $this->$attribute)){
        $this->addError($attribute , '账号或密码错误');
        //密码输入不能小于6位
        }else if(strlen($this->password) < 6){
        $this->addError($attribute , '账号或密码错误');
        }else{
        //读取数据库数据比较
        $user = User::find()->where(['username' => $this->$attribute , 'level' =>'admin'])->asArray()->one();
        var_dump( md5($this->password));
        //权限判断--admin权限才能登陆
        if( $user['level'] != 'admin'){
        $this->addError($attribute , '该账号无权限');
        }
        if(!$user || md5($this->password) != $user['password']){
        $this->addError($attribute , '账号或密码错误');
        }else{
        $this->user = $user;
        }
        }
         */
        //读取数据库数据比较
        $user = School::find()->where(['username' => $this->$attribute])->asArray()->one();
        //var_dump( md5($this->password));
        //var_dump( $user['level']);

        if (!$user || md5($this->password) != $user['password']) {
            $this->addError($attribute, '账号或密码错误');
        }
        //权限判断--admin和kefu权限才能登陆
        if ($user['level'] === 'admin' ||
            $user['level'] === 'kefu') {
            $this->user = $user;
            $this->updateUserStatus();
        } else {
            $this->addError($attribute, '该账号无权限');
        }
    }


    public function login()
    {
        //不存在-创建session
        if (!$this->user) {
            return false;
        }

        $this->createSession();
        //remember用于创建cookie
        if ($this->remember == 1) {
            $this->createCookie();
        }
        return true;
    }

    private function createSession()
    {
        //第一步生成session
        $session = Yii::$app->session;
        $session->set(self::BACKEND_ID, $this->user['id']);
        $session->set(self::BACKEND_USERNAME, $this->user['username']);
        $session->set(self::BACKEND_LEVEL, $this->user['level']);
    }

    private function createCookie()
    {
        $cookie = new Cookie();
        $cookie->name = self::BACKEND_COOKIE;
        $cookie->value = [
            'id' => $this->user['id'],
            'username' => $this->user['username'],
            'level' => $this->user['level'],
        ];
        //cookie保存7天
        $cookie->expire = time() + 60 * 60 * 24 * 7;
        $cookie->httpOnly = true;

        Yii::$app->response->cookies->add($cookie);
    }

    private function updateUserStatus()
    {
        $user = School::findOne($this->user['id']);
        $user->last_login_ip = Yii::$app->request->getUserIP();
        $user->last_login_date = date('Y-m-d H:i:s');
        return $user->save();
    }

    /**
     * 通过cookie登录
     */
    public function loginByCookie()
    {
        $cookies = Yii::$app->request->cookies;
        if ($cookies->has(self::BACKEND_COOKIE)) {
            $userData = $cookies->getValue(self::BACKEND_COOKIE);

            if (isset($userData['id']) && isset($userData['username']) && isset($userData['level'])) {
                $this->user = School::find()->where(
                    ['username' => $userData['username'],
                        'id' => $userData['id'],
                        'level' => $userData['level']])->asArray()->one();
                if ($this->user) {
                    $this->createSession();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 退出登录
     */
    public static function lagout()
    {
        $session = Yii::$app->session;
        $session->remove(self::BACKEND_ID);
        $session->remove(self::BACKEND_USERNAME);
        $session->remove(self::BACKEND_LEVEL);
        $session->destroy();

        $cookies = Yii::$app->request->cookies;
        //可能存在cookie
        if ($cookies->has(self::BACKEND_COOKIE)) {
            $rememberCookie = $cookies->get(self::BACKEND_COOKIE);
            Yii::$app->response->cookies->remove($rememberCookie);
        }
        return true;
    }

}
