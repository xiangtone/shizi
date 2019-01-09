<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cat}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $pic_url
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $update_time
 * @property integer $sort
 * @property integer $is_show
 * @property string $cover_url
 * @property integer $is_display
 * @property integer $is_pay
 */
class Cat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete', 'addtime', 'update_time', 'sort', 'is_show', 'is_display', 'is_pay'], 'integer'],
            [['pic_url', 'cover_url'], 'string'],
            [['name'], 'string', 'max' => 255],
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
            'name' => '标题',
            'pic_url' => '图片地址',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'update_time' => '更新时间',
            'sort' => '排序',
            'is_show' => '是否设为首页推荐',
            'cover_url' => '首页缩略图',
            'is_display' => '是否在分页列表中显示',
            'is_pay' => '是否开启付费',
        ];
    }
}
