<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%video}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $cat_id
 * @property string $title
 * @property string $pic_url
 * @property string $video_url
 * @property string $content
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $status
 * @property integer $is_show
 * @property string $introduce
 * @property string $banner_url
 * @property string $video_time
 * @property string $detail
 * @property integer $order
 * @property string $money
 * @property integer $refund
 * @property integer $page_view
 * @property integer $type
 * @property integer $style
 * @property integer $is_pay
 * @property integer $is_index
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%video}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'cat_id', 'sort', 'is_delete', 'addtime', 'status', 'is_show', 'order', 'refund', 'page_view', 'type', 'style', 'is_pay','is_index'], 'integer'],
            [['pic_url', 'video_url', 'content', 'banner_url', 'detail'], 'string'],
            [['video_time', 'money'], 'number'],
            [['title', 'introduce'], 'string', 'max' => 255],
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
            'cat_id' => '分类id',
            'title' => '标题',
            'pic_url' => '封面图片',
            'video_url' => '视频',
            'content' => '简介',
            'sort' => '排序',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'status' => '状态 0--下架 1--上架',
            'is_show' => '是否设为轮播 0--否 1--是',
            'introduce' => '简介',
            'banner_url' => 'Banner Url',
            'video_time' => '时长',
            'detail' => 'Detail',
            'order' => '是否开启预定',
            'money' => '预定金额',
            'refund' => 'Refund',
            'page_view' => '浏览量',
            'type' => '视频来源 0--其他 1--腾讯视频',
            'style' => '多媒体类型 0--视频 1--音频',
            'is_pay' => '是否开启付费收看',
            'is_index' => '是否展示到首页',
        ];
    }
    public function beforeSave($insert)
    {
        $this->title = Html::encode($this->title);
        $this->content = Html::encode($this->content);
        $this->introduce = Html::encode($this->introduce);
        return parent::beforeSave($insert);
    }
}
