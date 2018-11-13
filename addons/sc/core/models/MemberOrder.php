<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%member_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $order_no
 * @property string $title
 * @property string $price
 * @property integer $date
 * @property integer $is_pay
 * @property integer $pay_time
 * @property integer $is_delete
 * @property integer $addtime
 */
class MemberOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'date', 'is_pay', 'pay_time', 'is_delete', 'addtime'], 'integer'],
            [['price'], 'number'],
            [['order_no', 'title'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'order_no' => '订单号',
            'title' => '会员标题',
            'price' => '支付价格',
            'date' => '会员时间',
            'is_pay' => '是否支付 0--否 1--是',
            'pay_time' => '支付时间',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public function beforeSave($insert)
    {
        $this->title = Html::encode($this->title);
        return parent::beforeSave($insert);
    }
}
