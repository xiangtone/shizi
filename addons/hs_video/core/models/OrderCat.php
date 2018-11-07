<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_cat}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $order_no
 * @property integer $cat_id
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
class OrderCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'cat_id', 'user_id', 'is_pay', 'pay_type', 'pay_time', 'is_use', 'use_time', 'clerk_id', 'is_delete', 'addtime', 'is_refund', 'type'], 'integer'],
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
            'id' => Yii::t('app', 'ID'),
            'store_id' => Yii::t('app', 'Store ID'),
            'order_no' => Yii::t('app', '订单号'),
            'cat_id' => Yii::t('app', '视频分类id'),
            'user_id' => Yii::t('app', '用户id'),
            'is_pay' => Yii::t('app', '是否支付'),
            'pay_type' => Yii::t('app', '支付方式'),
            'pay_time' => Yii::t('app', '支付时间'),
            'is_use' => Yii::t('app', '是否使用 0--未使用 1--已使用 2--退款'),
            'use_time' => Yii::t('app', '使用时间'),
            'clerk_id' => Yii::t('app', 'Clerk ID'),
            'price' => Yii::t('app', '预定金额'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'addtime' => Yii::t('app', 'Addtime'),
            'is_refund' => Yii::t('app', '是否支持退款 0--不支持 1--支持'),
            'type' => Yii::t('app', '订单类型 0--预约 1--购买视频'),
        ];
    }
}
