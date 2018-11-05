<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property integer $video_id
 * @property integer $user_id
 * @property string $content
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $c_id
 * @property integer $store_id
 * @property string $upload_img
 * @property integer $top_id
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_id', 'user_id', 'is_delete', 'addtime', 'c_id', 'store_id', 'top_id'], 'integer'],
            [['content', 'upload_img'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_id' => '视频ID',
            'user_id' => '用户ID',
            'content' => '评论内容',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'c_id' => '回复的评论 0--顶级楼层',
            'store_id' => 'Store ID',
            'upload_img' => 'Upload Img',
            'top_id' => '顶级楼层ID',
        ];
    }
    public function beforeSave($insert)
    {
        $this->content = Html::encode($this->content);
        return parent::beforeSave($insert);
    }
}
