<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19
 * Time: 15:31
 */

namespace app\modules\api\models;


use app\extensions\WechatTplMsg;
use app\models\Order;
use app\models\Video;

class OrderVideoForm extends Model
{
    public $store_id;
    public $user;

    public $video_id;
    public $price;

    public function rules()
    {
        return [
            [['video_id','price'],'required'],
            [['price'],'number']
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $order = new Order();
        $order->store_id = $this->store_id;
        $order->video_id = $this->video_id;
        $order->product_id = $this->video_id;
        $order->product_type = 'video';
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
        if($order->save()){
            return [
                'code'=>0,
                'msg'=>'成功',
                'data'=>$order->id
            ];
        }
    }

    public function getOrderNo()
    {
        $order_no = null;
        while(true){
            $order_no = date('YmdHis').rand(100000,999999);
            $exit = Order::find()->where(['order_no'=>$order_no])->exists();
            if(!$exit){
                break;
            }
        }
        return $order_no;
    }
}