<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/26
 * Time: 11:34
 */

namespace app\modules\api\models;

use app\models\Teacher;
use app\models\User;
use Curl\Curl;

class TeacherForm extends Model
{
    public $store_id;
    public $user_id;
    public $appId;
    public $code;
    public $encryptedData;
    public $iv;
    public $wechat_app;
    public $teacherName;
    public $cooName;
    public $bank;
    public $bankAccount;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['binding', 'phone', 'phonecode'], 'integer'],
            [['appId', 'code', 'encryptedData', 'iv', 'wechat_app', 'teacherName', 'cooName', 'bank', 'bankAccount'], 'string'],
        ];
    }

    public function edit()
    {
        $user = User::find()->where(['store_id' => $this->store_id, 'id' => $this->user_id])->one();
        $teacher = Teacher::find()->where(['id' => $this->user_id])->one();
        if (!$teacher) {
            $teacher = new Teacher();
            $teacher->id = $this->user_id;
            $teacher->teacher_name = $this->teacherName;
            $teacher->school_name = $this->cooName;
            $teacher->bank_name = $this->bank;
            $teacher->bank_account = $this->bankAccount;
            $teacher->save();
        } else {
            $teacher->teacher_name = $this->teacherName;
            $teacher->school_name = $this->cooName;
            $teacher->bank_name = $this->bank;
            $teacher->bank_account = $this->bankAccount;
            $teacher->save();
        }
        return [
            'code' => 0,
        ];

    }
    public function userEmpower()
    {
        $user = user::find()->where(['store_id' => $this->store_id, 'id' => $this->user_id])->one();
        $user->binding = $this->phone;
        if ($user->save()) {
            return [
                'code' => 0,
            ];
        } else {
            return [
                'code' => 1,
                'msg' => 'fail',
            ];
        }
    }

    public function binding()
    {
        $res = $this->getOpenid($this->code);
        if (strlen($res['session_key']) != 24) {
            return 1;
        }
        $aesKey = base64_decode($res['session_key']);
        if (strlen($this->iv) != 24) {
            return 3;
        }
        $aesIV = base64_decode($this->iv);
        $aesCipher = base64_decode($this->encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj = json_decode($result);
        if ($dataObj == null) {
            return 4;
        }
        if ($dataObj->watermark->appid != $this->wechat_app->app_id) {
            return 2;
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'dataObj' => $dataObj->phoneNumber,
            ],
        ];
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
