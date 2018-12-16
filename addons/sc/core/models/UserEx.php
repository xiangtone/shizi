<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_ex}}".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $video_id
 * @property integer $ex_type
 * @property integer $ex_count
 * @property string $create_time
 * @property string $modify_time
 */
class UserEx extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_ex}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'video_id', 'ex_type', 'ex_count', 'create_time', 'modify_time'], 'integer'],
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
            'video_id' => 'Video ID',
            'ex_type' => '1组词2组句',
            'ex_count' => '练习次数',
            'create_time' => 'Create Time',
            'modify_time' => 'Modify Time',
        ];
    }
}
