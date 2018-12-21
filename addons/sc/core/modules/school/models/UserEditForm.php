<?php
namespace app\modules\school\models;

use yii\db\ActiveRecord;

class UserEdit extends ActiveRecord
{
    const SUPER_ID = 9;
    public $newPassword ;
    public $confirmPassword ;
    public static function tableName()
    {
       // return '{{%user}}';
        return '{{user}}';
    }

    public function rules()
    {
        return [
            ['username' , 'checkName' , 'skipOnEmpty' => false],
            //['password' , 'string' , /*'min' => 6 ,'tooShort' => '密码的长度不能少于6位' , */ 'skipOnEmpty' => false /*,'when' => function($model){ return ($model->isNewRecord || $model->password != '');}*/],
            //['newPassword' , 'string' ,/*'min' => 6  , 'tooShort' => '密码的长度不能少于6位' ,*/  'skipOnEmpty' => false/* ,'when' => function($model){ return ($model->isNewRecord || $model->newPassword != '');}*/],
            //['status' , 'in' , 'range' => [0 , 1] , 'message' => '非法操作']
            //[['newPassword', 'password'], 'required'],
            [ 'password', 'required','message'=>'原始密码必填'],
            [ 'newPassword', 'required','message'=>'新密码必填'],
            [ 'confirmPassword', 'required','message'=>'确认密码必填'],
            ['confirmPassword','compare','compareAttribute'=>'newPassword','message'=>'新密码和确认密码输入不相同']
        ];
    }

    public function checkName($attribute , $params)
    {
        /*
        //字母，数字 2~30
        if(!preg_match("/^[\w]{2,30}$/" , $this->$attribute)){
            $this->addError($attribute , '用户名必须为2~30的数字或字母');
        }else if(self::find()->where(['username' => $this->$attribute])->andWhere(['!=' , 'id' , $this->id])->count() > 0){
            $this->addError($attribute , '用户名已经被占用');
        }
        */
        if(self::find()->where(['username' => $this->$attribute])->andWhere(['!=' , 'id' , $this->id])->count() > 0){
            $this->addError($attribute , '用户名已经被占用');
            //Yii::$app->session->setFlash('error' , '用户名已经被占用');
        }
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            /*
            if($this->isNewRecord){
                $this->date = $this->login_date = time();
            }
            */
            /*
            var_dump($this->password);
            var_dump($this->newPassword);
            var_dump($this->confirmPassword);
            */
            //var_dump($this->password);
            if(empty($this->newPassword)){
                unset($this->newPassword);
            }else{
                $this->password = md5($this->newPassword);
                //var_dump($this->password);
            }
            return true;

        }
        return false;
    }




}