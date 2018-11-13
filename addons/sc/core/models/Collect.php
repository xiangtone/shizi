<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%collect}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $video_id
 * @property integer $user_id
 * @property integer $is_delete
 * @property integer $addtime
 */
class Collect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collect}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'video_id', 'user_id', 'is_delete', 'addtime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'video_id' => '视频ID',
            'user_id' => '用户ID',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
