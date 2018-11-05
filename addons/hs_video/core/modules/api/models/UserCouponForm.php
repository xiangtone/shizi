<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/23
 * Time: 15:52
 */

namespace app\modules\api\models;

use app\models\Coupon;
use app\models\UserCoupon;
use app\models\User;
use app\models\VideoCoupon;

class UserCouponForm extends Model
{
    public $coupon_id;
    public $user_id;
    public $store_id;

    public function rules()
    {
        return [
            [['coupon_id'], 'required']
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $videoCoupon = VideoCoupon::findOne($this->coupon_id);
        if(!$videoCoupon){
            return;
        }
        if($videoCoupon->total_count <= 0){
            return [
                'code'=>1,
                'msg'=>'优惠券已领取完'
            ];
        }
        $user_coupon = new UserCoupon();
        $user_coupon->store_id = $this->store_id;
        $user_coupon->user_id = $this->user_id;
        $user_coupon->video_id = $videoCoupon->video_id;
        $user_coupon->coupon_id = $videoCoupon->coupon_id;
        $user_coupon->num = 1;
        $user_coupon->is_expire = 0;
        $user_coupon->is_use = 0;
        $user_coupon->desc = $videoCoupon->desc;
        $user_coupon->coupon_name = $videoCoupon->coupon_name;
        $user_coupon->draw_type = $videoCoupon->draw_type;
        $user_coupon->cost_price = $videoCoupon->cost_price;
        $user_coupon->coupon_price = $videoCoupon->coupon_price;
        $user_coupon->original_cost = $videoCoupon->original_cost;
        $user_coupon->sub_price = $videoCoupon->sub_price;
        $user_coupon->expire_type = $videoCoupon->expire_type;
        $user_coupon->expire_day = $videoCoupon->expire_day;
        $user_coupon->begin_time = $videoCoupon->begin_time;
        $user_coupon->end_time = $videoCoupon->end_time;
        $user_coupon->addtime = time();
        $user_coupon->is_delete = 0;
        $user_coupon->video_coupon_id = $videoCoupon->id;
        if($user_coupon->save()){
            $videoCoupon->total_count -= 1;
            if($videoCoupon->save()){
                return [
                    'code'=>0,
                    'msg'=>'fail'
                ];
            }else{
                return $this->getModelError();
            }
        }else{
            return $this->getModelError();
        }

    }

    function diffBetweenTwoDays ($day1, $day2)
    {
        $second1 = $day1;
        $second2 = $day2;
        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }

    public function  search()
    {
        $user_coupons = UserCoupon::find()->where(['user_id' => $this->user_id,'store_id' => $this->store_id,'is_delete'=>0])->asArray()->all();
        foreach ($user_coupons as $key => &$value) {
            if($value['expire_type'] == 1){
                $begin_time = $value['addtime'];
                $b = $value['expire_day'] * 86400;
                $end_time = $begin_time + $b;
                $value['end_time'] = $end_time;
                $value['begin_time'] = $begin_time;
            }
            $value['remaining_time'] = round($this->diffBetweenTwoDays(time(),$value['end_time']));
            $value['endtime'] = $value['end_time'];
            $value['end_time'] = date('Y.m.d',$value['end_time']);
            $value['begin_time'] = date('Y.m.d',$value['begin_time']);
        }
        return [
            'code'=>0,
            'data'=>[
                'user_coupon'=>$user_coupons,
            ]
        ];
    }

    public function cancelcoupon()
    {
        $userCoupon = UserCoupon::find()->where(['id'=>$this->coupon_id,'store_id'=>$this->store_id,'is_delete'=>0,'is_use'=>0])->one();
        if(!$userCoupon){
            return [
                'code' => 2,
                'msg'=>'已核销',
            ];
        }
        $user = User::find()->where(['id'=>$this->user_id,'store_id'=>$this->store_id,'is_clerk'=>1,'is_delete'=>0])->one();
        if($user){
            return [
                'code' => 0,
                'msg'=>'fail',
            ];
        }else{
            return [
                'code' => 1,
                'msg'=>'不是核销员',
            ];
        }
    }

    public function clerk()
    {
        $userCoupon = UserCoupon::find()->where(['id'=>$this->coupon_id,'store_id'=>$this->store_id,'is_delete'=>0])->one();
        $userCoupon->is_use = 1;
        $user = User::findOne( $this->user_id);
        $userCoupon->clerk = $user->nickname;
        if($userCoupon->save()){
            return [
                'code' => 0,
            ];
        }else{
            return [
                'code' => 1,
            ];
        }

    }

}