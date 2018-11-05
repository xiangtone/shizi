<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/29
 * Time: 9:15
 */

namespace app\modules\api\models;

use app\models\CouponOrder;

class CouponOrderForm extends Model
{
    public $store_id;
    public $video_coupon_id;
    public $video_id;
    public $coupon_id;
    public $user;
    public $price;

    public function rules()
    {
        return [
            [['video_coupon_id','price'],'required'],
            [['price'],'number'],
            [['video_id', 'coupon_id'], 'integer'],
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $videoCoupon = new CouponOrder();
        $videoCoupon->store_id = $this->store_id;
        $videoCoupon->order_no = 'VP'.$this->getOrderNo();
        $videoCoupon->video_coupon_id = $this->video_coupon_id;
        $videoCoupon->user_id = $this->user->id;
        $videoCoupon->is_pay = 0;
        $videoCoupon->pay_type = 0;
        $videoCoupon->pay_time = '';
        $videoCoupon->price = $this->price;
        $videoCoupon->is_delete = 0;
        $videoCoupon->is_refund = 0;
        $videoCoupon->num = 1;
        $videoCoupon->video_id = $this->video_id;
        $videoCoupon->coupon_id = $this->coupon_id;
        if($videoCoupon->save()){
            return [
                'code'=>0,
                'msg'=>'成功',
                'data'=>$videoCoupon->id
            ];
        }
    }

    public function getOrderNo()
    {
        $order_no = null;
        while(true){
            $order_no = date('YmdHis').rand(100000,999999);
            $exit = CouponOrder::find()->where(['order_no'=>$order_no])->exists();
            if(!$exit){
                break;
            }
        }
        return $order_no;
    }
}