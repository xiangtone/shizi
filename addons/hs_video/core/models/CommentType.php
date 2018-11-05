<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%comment_type}}".
 *
 * @property integer $id
 * @property integer $c_id
 * @property integer $type
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $user_id
 * @property integer $store_id
 */
class CommentType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'type', 'is_delete', 'addtime', 'user_id', 'store_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'c_id' => '评论ID',
            'type' => '类型 0--点赞 1--踩 ',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'user_id' => '操作者ID',
            'store_id' => 'Store ID',
        ];
    }
}
