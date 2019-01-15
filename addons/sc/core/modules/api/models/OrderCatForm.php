<?php
/***
视频分类表格视图
 */
namespace app\modules\api\models;

use app\models\Order;

class OrderCatForm extends Model
{
    public $store_id;
    public $user;
    //$form->attributes = \Yii::$app->request->post();要用POST的参数须先定义
    public $cat_id; // 需要先定义
    public $video_id; // 需要定义
    public $price;
    /**
     *所有$this.store_id 等以上定义的变量要使用之前都 RULES操作，否则无法使用

     */
    public function rules()
    {
        return [
            [['video_id', 'cat_id', 'price'], 'required'], //需要加cat_id和video_id才能使用
            [['price'], 'number'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $checkOrder = Order::find()->where(['user_id' => $this->user->id,
            'product_id' => $this->cat_id,
            'product_type' => 'cat',
            'is_pay' => 1])->one();
        if ($checkOrder) {
            return [
                'code' => 5,
                'msg' => '已经购买过，请勿重复购买',
            ];
        } else {
            $order = new Order();
            $order->store_id = $this->store_id;
            $order->video_id = $this->video_id;
            $order->product_id = $this->cat_id;
            $order->product_type = 'cat';
            $order->user_id = $this->user->id;
            $order->is_delete = 0;
            $order->addtime = time();
            $order->order_no = $this->getOrderNo();
            $order->is_pay = 0;
            $order->is_refund = 0;
            $order->pay_time = 0;
            $order->type = 1;
            $order->pay_type = 0;
            $order->price = $this->price;
            if ($order->save()) {
                return [
                    'code' => 0,
                    'msg' => '成功',
                    'data' => $order->id,
                ];
            }
        }
    }

    public function getOrderNo()
    {
        $order_no = null;
        while (true) {
            $order_no = date('YmdHis') . rand(100000, 999999);
            $exit = Order::find()->where(['order_no' => $order_no])->exists();
            if (!$exit) {
                break;
            }
        }
        return $order_no;
    }
}
