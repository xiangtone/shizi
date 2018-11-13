<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 9:57
 */

namespace app\modules\api\models;


use app\models\Order;
use app\models\OrderRefund;

class OrderRefundForm extends Model
{
    public $store_id;
    public $user;
    public $order_id;
    public $desc;


    public function rules()
    {
        return [
            [['order_id'],'required'],
            [['desc'],'trim'],
            [['desc'],'string'],
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $order = Order::findOne(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>$this->order_id]);
        if($order->is_use == 2){
            return [
                'code'=>1,
                'msg'=>'该订单已申请售后'
            ];
        }
        $order->is_use = 2;
        $order->save();


        $refund = new OrderRefund();
        $refund->store_id = $this->store_id;
        $refund->user_id = $this->user->id;
        $refund->order_id = $this->order_id;
        $refund->video_id = $order->video_id;
        $refund->desc = $this->desc;
        $refund->order_refund_no = $this->getOrderRefundNo();
        $refund->type = 0;
        $refund->refund_price = $order->price;
        if($order->price == 0){
            $refund->status = 1;
        }else{
            $refund->status = 0;
        }
        $refund->response_time = 0;
        $refund->is_delete = 0;
        $refund->addtime = time();
        if($refund->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return $this->getModelError($refund);
        }
    }

    private function getOrderRefundNo()
    {
        $order_refund_no = null;
        while (true) {
            $order_refund_no = date('YmdHis') . rand(100000, 999999);
            $exist_order_refund_no = OrderRefund::find()->where(['order_refund_no' => $order_refund_no])->exists();
            if (!$exist_order_refund_no)
                break;
        }
        return $order_refund_no;
    }
}