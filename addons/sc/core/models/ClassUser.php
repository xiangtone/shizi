<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%class_user}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $class_id
 * @property string $create_time
 * @property integer $role
 * @property integer $ex_count
 */
class ClassUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%class_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'class_id', 'create_time'], 'required'],
            [['user_id', 'class_id', 'create_time', 'role', 'ex_count'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id自增',
            'user_id' => '用户Id',
            'class_id' => '班级id',
            'create_time' => '创建时间',
            'role' => '0普通1创始人2管理员',
            'ex_count' => '练习次数-相当于对班级贡献',
        ];
    }
}
