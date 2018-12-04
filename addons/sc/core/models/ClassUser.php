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
            [['user_id', 'class_id', 'create_time', 'role'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'class_id' => 'Class ID',
            'create_time' => 'Create Time',
            'role' => '0普通1创始人2管理员',
        ];
    }
}
