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
            [['enjoytime'], 'safe'],
            [['intro', 'portrait'], 'string'],
            [['username', 'password', 'level', 'realname', 'appointment', 'sheng', 'shi', 'status'], 'string', 'max' => 255],
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
            'realname' => '姓名',
            'appointment' => '职位',
            'sheng' => '省',
            'shi' => '市',
            'status' => '状态',
            'intro' => '介绍',
            'portrait' => '头像',
        ];
    }
}
