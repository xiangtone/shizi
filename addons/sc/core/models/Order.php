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
 * @property integer $product_id
 * @property string $product_type
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
            [['store_id', 'video_id', 'user_id', 'is_pay', 'pay_type', 'pay_time', 'is_use', 'use_time', 'clerk_id', 'is_delete', 'product_id', 'addtime', 'is_refund', 'type'], 'integer'],
            [['price'], 'number'],
            [['order_no'], 'string', 'max' => 255],
            [['product_type'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'store_id' => Yii::t('app', 'Store ID'),
            'order_no' => Yii::t('app', '订单号'),
            'video_id' => Yii::t('app', '视频ID'),
            'user_id' => Yii::t('app', '用户ID'),
            'is_pay' => Yii::t('app', '是否支付'),
            'pay_type' => Yii::t('app', '支付方式'),
            'pay_time' => Yii::t('app', '支付时间'),
            'is_use' => Yii::t('app', '是否使用 0--未使用 1--已使用 2--退款'),
            'use_time' => Yii::t('app', '使用时间'),
            'clerk_id' => Yii::t('app', '店员id'),
            'price' => Yii::t('app', '预定金额'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'product_id' => Yii::t('app', '购买的产品id'),
            'product_type' => Yii::t('app', '购买的产品类别 1.video 2.cat 3.member'),
            'addtime' => Yii::t('app', '订单生成时间'),
            'is_refund' => Yii::t('app', '是否支持退款 0--不支持 1--支持'),
            'type' => Yii::t('app', '订单类型 0--预约 1--购买视频'),
        ];
    }
}
