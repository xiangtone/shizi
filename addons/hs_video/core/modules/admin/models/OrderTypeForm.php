<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 16:44
 */

namespace app\modules\admin\models;


use app\extensions\WechatTplMsg;
use app\models\Order;
use app\models\OrderRefund;

class OrderTypeForm extends Model
{
    public $store_id;
    public $order_refund_id;
    public $type;

    public function rules()
    {
        return [
            [['store_id', 'order_refund_id', 'type'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $order_refund = OrderRefund::findOne([
            'id' => $this->order_refund_id,
            'store_id' => $this->store_id,
            'is_delete' => 0
        ]);
        if (!$order_refund) {
            return [
                'code' => 1,
                'msg' => '售后订单不存在，请刷新页面'
            ];
        }
        if ($order_refund->status != 0)
            return [
                'code' => 1,
                'msg' => '售后订单已经处理过了，请刷新页面',
                'id' => $order_refund->id,
            ];
        $wechat = $this->getWechat();
        $order = Order::findOne(['id' => $order_refund->order_id, 'is_delete' => 0, 'store_id' => $this->store_id]);
        if ($this->type == 2) {//拒绝
            $order_refund->status = 2;
            $order_refund->response_time = time();
            if ($order_refund->save()) {
                $order->is_use = 0;
                $order->save();
                $wechat_tpl_meg_sender = new WechatTplMsg($order->store_id, $order->id, $wechat);
                $wechat_tpl_meg_sender->refundMsg(0.00,$order_refund->desc,'商家拒绝退款');
                return [
                    'code' => 0,
                    'msg' => '处理成功，已拒绝该订单退款'
                ];
            } else {
                return $this->getModelError($order_refund);
            }
        }
        if ($this->type == 1) {
            $order_refund->status = 1;
            $order_refund->response_time = time();
            if ($order_refund->refund_price > 0) {
                $data = [
                    'out_trade_no' => $order->order_no,
                    'out_refund_no' => $order_refund->order_refund_no,
                    'total_fee' => $order->price * 100,
                    'refund_fee' => $order_refund->refund_price * 100
                ];
                $res = $wechat->pay->refund($data);
                if(!$res){
                    return [
                        'code' => 1,
                        'msg' => '退款失败，请检查微信支付key和pem证书是否配置正确',
                    ];
                }
                if($res['return_code'] != 'SUCCESS'){
                    return [
                        'code' => 1,
                        'msg' => '退款失败，' . $res['return_msg'],
                    ];
                }
                if ($res['result_code'] != 'SUCCESS') {
                    return [
                        'code' => 1,
                        'msg' => '退款失败，' . $res['err_code_des'],
                    ];
                }
                if ($order_refund->save()) {
                    $wechat_tpl_meg_sender = new WechatTplMsg($order->store_id, $order->id, $wechat);
                    $wechat_tpl_meg_sender->refundMsg($order_refund->refund_price,$order_refund->desc,'退款已完成');
                    return [
                        'code' => 0,
                        'msg' => '处理成功，已完成退款。',
                    ];
                }
                return $this->getModelError($order_refund);
            }
        }
    }

}