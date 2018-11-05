<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $order_no
 * @property integer $video_id
 * @property integer $user_id
 * @property integer $is_pay
 * @property integer $pay_type
 * @property integer $pay_time
 * @property integer $is_use
 * @property integer $use_time
 * @property integer $clerk_id
 * @property string $price
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $is_refund
 * @property integer $type
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'video_id', 'user_id', 'is_pay', 'pay_type', 'pay_time', 'is_use', 'use_time', 'clerk_id', 'is_delete', 'addtime', 'is_refund', 'type'], 'integer'],
            [['price'], 'number'],
            [['order_no'], 'string', 'max' => 255],
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
            'order_no' => '订单号',
            'video_id' => '视频ID',
            'user_id' => 'User ID',
            'is_pay' => '是否支付',
            'pay_type' => '支付方式',
            'pay_time' => '支付时间',
            'is_use' => '是否使用 0--未使用 1--已使用 2--退款',
            'use_time' => '使用时间',
            'clerk_id' => 'Clerk ID',
            'price' => '预定金额',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'is_refund' => '是否支持退款 0--不支持 1--支持',
            'type' => '订单类型 0--预约 1--购买视频',
        ];
    }
}
