<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9
 * Time: 8:56
 */

namespace app\modules\api\models;


use app\models\User;
use Curl\Curl;

class LoginForm extends Model
{

    public $wechat_app;

    public $code;
    public $user_info;
    public $encrypted_data;
    public $iv;
    public $signature;
    public $fd;
    public $cd;

    public $store_id;

    public function rules()
    {
        return [
            [['fd','cd'], 'integer'],
            [['wechat_app', 'code', 'user_info', 'encrypted_data', 'iv', 'signature',], 'required'],
        ];
    }

    public function login()
    {
        if (!$this->validate())
            return $this->getModelError();
        $res = $this->getOpenid($this->code);
        if (!$res || empty($res['openid'])) {
            return [
                'code' => 1,
                'msg' => '获取用户OpenId失败',
                'data' => $res,
            ];
        }
        $session_key = $res['session_key'];
        require __DIR__ . '/wxbdc/WXBizDataCrypt.php';
        $pc = new \WXBizDataCrypt($this->wechat_app->app_id, $session_key);
        $errCode = $pc->decryptData($this->encrypted_data, $this->iv, $data);
        if ($errCode == 0) {
            $data = json_decode($data, true);
            $user = User::findOne(['wechat_open_id' => $data['openId'], 'store_id' => $this->store_id]);
            if (!$user) {
                $user = new User();
                $user->type = 1;
                $user->username = $data['openId'];
                $user->password = \Yii::$app->security->generatePasswordHash(\Yii::$app->security->generateRandomString());
                $user->auth_key = \Yii::$app->security->generateRandomString();
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->addtime = time();
                $user->is_delete = 0;
                $user->wechat_open_id = $data['openId'];
                $user->wechat_union_id = isset($data['unionId']) ? $data['unionId'] : '';
                $user->nickname = preg_replace('/[\xf0-\xf7].{3}/', '', $data['nickName']);
                $user->avatar_url = $data['avatarUrl'];
                $user->store_id = $this->store_id;
                $user->teacher_id = $this->fd;
                $user->channel_id = $this->cd;
                $user->save();
                sleep(1);
                $same_user = User::find()->select('id')->where([
                    'AND',
                    [
                        'store_id' => $this->store_id,
                        'wechat_open_id' => $data['openId'],
                        'is_delete' => 0,
                    ],
                    ['<', 'id', $user->id],
                ])->one();
                if ($same_user) {
                    $user->delete();
                    $user = null;
                    $user = $same_user;
                }
            }else{
                $user->nickname = preg_replace('/[\xf0-\xf7].{3}/', '', $data['nickName']);
                $user->avatar_url = $data['avatarUrl'];
                $user->save();
            }
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => (object)[
                    'access_token' => $user->access_token,
                    'nickname' => $user->nickname,
                    'avatar_url' => $user->avatar_url,
                    'id' => $user->id,
                    'is_comment' => $user->is_comment,
                    'is_clerk' => $user->is_clerk,
                    'is_member' => $user->is_member,
                    'teacher_id' => $user->teacher_id,
                    'channel_id' => $user->channel_id,
                    'is_teacher' => $user->is_teacher,
                    'due_time' => date('Y-m-d', $user->due_time),
                ]
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '登录失败，请重试',
            ];
        }
    }

    private function getOpenid($code)
    {
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->wechat_app->app_id}&secret={$this->wechat_app->app_secret}&js_code={$code}&grant_type=authorization_code";
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($api);
        $res = $curl->response;
        $res = json_decode($res, true);
        return $res;
    }
}