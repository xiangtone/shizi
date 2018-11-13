<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%order_refund}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $video_id
 * @property string $order_refund_no
 * @property integer $type
 * @property string $refund_price
 * @property string $desc
 * @property integer $status
 * @property string $refund_desc
 * @property integer $response_time
 * @property integer $is_delete
 * @property integer $addtime
 */
class OrderRefund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'user_id', 'video_id', 'type', 'status', 'response_time', 'is_delete', 'addtime'], 'integer'],
            [['refund_price'], 'number'],
            [['desc', 'refund_desc'], 'string'],
            [['order_refund_no'], 'string', 'max' => 255],
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
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'video_id' => 'Video ID',
            'order_refund_no' => '退款单号',
            'type' => '类型 0--退款',
            'refund_price' => '退款金额',
            'desc' => '退款说明',
            'status' => '处理状态 0--待处理 1--退款成功 2--拒绝退款',
            'refund_desc' => '拒绝理由',
            'response_time' => '处理时间',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
    public function beforeSave($insert)
    {
        $this->desc = Html::encode($this->desc);
        $this->refund_desc = Html::encode($this->refund_desc);
        return parent::beforeSave($insert);
    }
}
