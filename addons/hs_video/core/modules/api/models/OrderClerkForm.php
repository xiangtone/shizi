<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 14:54
 */

namespace app\modules\api\models;


use app\models\Order;

class OrderClerkForm extends Model
{
    public $store_id;
    public $user;
    public $order_id;

    public function save()
    {
        $order = Order::findOne(['id'=>$this->order_id,'store_id'=>$this->store_id]);
        if(!$order){
            return [
                'code'=>1,
                'msg'=>'订单不存在'
            ];
        }
        if($this->user->is_clerk == 0){
            return [
                'code'=>1,
                'msg'=>'不是核销员'
            ];
        }
        if($order->is_use == 1){
            return [
                'code'=>1,
                'msg'=>'订单已核销'
            ];
        }
        $order->clerk_id = $this->user->id;
        $order->is_use = 1;
        $order->use_time = time();

        if($order->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return [
                'code'=>1,
                'msg'=>'网络异常'
            ];
        }
    }

}