<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%video_pay}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $video_id
 * @property integer $type
 * @property integer $time
 * @property string $price
 */
class VideoPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%video_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'video_id', 'type', 'time'], 'integer'],
            [['price'], 'number'],
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
            'type' => '付费类型',
            'time' => '免费时长',
            'price' => '收费价格',
        ];
    }
}
