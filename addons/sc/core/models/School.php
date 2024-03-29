<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%school}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $level
 * @property string $enjoytime
 * @property string $realname
 * @property string $appointment
 * @property string $sheng
 * @property string $shi
 * @property string $status
 * @property string $intro
 * @property string $portrait
 * @property string $last_login_ip
 * @property string $last_login_date
 * @property integer $login_count
 * @property string $expire_date
 * @property string $init_password
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%school}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enjoytime', 'last_login_date', 'expire_date'], 'safe'],
            [['intro', 'portrait', 'last_login_ip'], 'string'],
            [['login_count'], 'integer'],
            [['username', 'password', 'level', 'realname', 'appointment', 'sheng', 'shi', 'status'], 'string', 'max' => 255],
            [['init_password'], 'string', 'max' => 20],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'level' => '权限级别',
            'enjoytime' => '注册时间',
            'realname' => '学校名称',
            'appointment' => '职位',
            'sheng' => '省',
            'shi' => '市',
            'status' => '状态',
            'intro' => '介绍',
            'portrait' => '头像',
            'last_login_ip' => '最后登录IP',
            'last_login_date' => '最后登录时间',
            'login_count' => '登录次数',
            'expire_date' => '过期时间',
            'init_password' => '初始密码明文',
        ];
    }
}
