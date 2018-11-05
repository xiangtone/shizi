<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coupon_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $order_no
 * @property integer $video_coupon_id
 * @property integer $user_id
 * @property integer $is_pay
 * @property integer $pay_type
 * @property integer $pay_time
 * @property integer $is_use
 * @property integer $use_time
 * @property string $price
 * @property integer $is_delete
 * @property integer $is_refund
 * @property integer $num
 * @property integer $video_id
 * @property integer $coupon_id
 */
class CouponOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'video_coupon_id', 'user_id', 'is_pay', 'pay_type', 'pay_time', 'is_use', 'use_time', 'is_delete', 'is_refund', 'num', 'video_id', 'coupon_id'], 'integer'],
            [['price'], 'number'],
            [['num'], 'required'],
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
            'order_no' => 'Order No',
            'video_coupon_id' => 'Video Coupon ID',
            'user_id' => 'User ID',
            'is_pay' => 'Is Pay',
            'pay_type' => 'Pay Type',
            'pay_time' => 'Pay Time',
            'is_use' => 'Is Use',
            'use_time' => 'Use Time',
            'price' => 'Price',
            'is_delete' => 'Is Delete',
            'is_refund' => 'Is Refund',
            'num' => 'Num',
            'video_id' => 'Video ID',
            'coupon_id' => 'Coupon ID',
        ];
    }
}