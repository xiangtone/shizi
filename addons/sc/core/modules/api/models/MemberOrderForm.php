<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22
 * Time: 11:36
 */

namespace app\modules\api\models;


use app\models\Level;
use app\models\MemberOrder;

class MemberOrderForm extends Model
{
    public $store_id;
    public $user;

    public $level_id;

    public function rules()
    {
        return [
            [['level_id'], 'integer']
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $level = Level::findOne(['id' => $this->level_id, 'store_id' => $this->store_id]);
        $member_order = new MemberOrder();
        $member_order->store_id = $this->store_id;
        $member_order->user_id = $this->user->id;
        $member_order->is_delete = 0;
        $member_order->addtime = time();
        $member_order->order_no = 'M' . $this->getOrderNo();
        $member_order->title = $level->title;
        if($this->user->is_member == 1 && $level->s_price){
            $member_order->price = $level->s_price;
        }else{
            $member_order->price = $level->price;
        }
        $member_order->date = $level->date;
        $member_order->is_pay = 0;
        $member_order->pay_time = 0;
        if (!$member_order->save()) {
            return [
                'code' => 1,
                'msg' => $this->getModelError($member_order)
            ];
        }
        return [
            'code' => 0,
            'msg' => '成功',
            'data' => $member_order->id
        ];
    }

    public function getOrderNo()
    {
        $order_no = null;
        while (true) {
            $order_no = date('YmdHis') . rand(100000, 999999);
            $exit = MemberOrder::find()->where(['order_no' => 'M' . $order_no])->exists();
            if (!$exit) {
                break;
            }
        }
        return $order_no;
    }
}