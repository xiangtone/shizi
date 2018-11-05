<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/19
 * Time: 9:57
 */

namespace app\controllers;


use app\extensions\Sms;
use app\extensions\WechatTplMsg;
use app\models\MemberOrder;
use app\models\Option;
use app\models\Order;
use app\models\Store;
use app\models\User;
use app\models\WechatApp;
use luweiss\wechat\DataTransform;
use luweiss\wechat\Wechat;
use app\models\CouponOrder;
use app\models\VideoCoupon;
use app\models\UserCoupon;

class PayNotifyController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $xml = file_get_contents("php://input");
        $res = DataTransform::xmlToArray($xml);
        if ($res && !empty($res['out_trade_no'])) {//微信支付回调
            $this->wechatPayNotify($res);
        }
    }
    private function wechatPayNotify($res)
    {
        if ($res['result_code'] != 'SUCCESS' && $res['return_code'] != 'SUCCESS')
            return;
        $order = Order::findOne([
            'order_no' => $res['out_trade_no'],
        ]);
        if(!$order){
            $order2 = CouponOrder::findOne([
                'order_no' => $res['out_trade_no'],
            ]);
            if (!$order2){
                return $this->wechatPayNotify_1($res);
            }
            $store = Store::findOne($order2->store_id);
            if (!$store)
                return;
            $wechat_app = WechatApp::findOne($store->wechat_app_id);
            if (!$wechat_app)
                return;
            $wechat = new Wechat([
                'appId' => $wechat_app->app_id,
                'appSecret' => $wechat_app->app_secret,
                'mchId' => $wechat_app->mch_id,
                'apiKey' => $wechat_app->key,
                'cachePath' => \Yii::$app->runtimePath . '/cache',
            ]);
            $new_sign = $wechat->pay->makeSign($res);
            if ($new_sign != $res['sign']) {
                echo "Sign 错误";
                return;
            }
            if ($order2->is_pay == 1) {
                echo "订单已支付";
                return;
            }
            $order2->is_pay = 1;
            $order2->pay_time = time();
            $order2->pay_type = 1;
            if ($order2->save()) {
                $this->couponReceive($order2);
                echo '支付成功';
                return;
            } else {
                echo "支付失败";
                return;
            }
        }
        $store = Store::findOne($order->store_id);
        if (!$store)
            return;
        $wechat_app = WechatApp::findOne($store->wechat_app_id);
        if (!$wechat_app)
            return;
        $wechat = new Wechat([
            'appId' => $wechat_app->app_id,
            'appSecret' => $wechat_app->app_secret,
            'mchId' => $wechat_app->mch_id,
            'apiKey' => $wechat_app->key,
            'cachePath' => \Yii::$app->runtimePath . '/cache',
        ]);
        $new_sign = $wechat->pay->makeSign($res);
        if ($new_sign != $res['sign']) {
            echo "Sign 错误";
            return;
        }
        if ($order->is_pay == 1) {
            echo "订单已支付";
            return;
        }
        $order->is_pay = 1;
        $order->pay_time = time();
        $order->pay_type = 1;
        if ($order->save()) {
            if($order->type == 0){
                $wechat_tpl_meg_sender = new WechatTplMsg($order->store_id, $order->id, $wechat);
                $wechat_tpl_meg_sender->payMsg();
            }
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return;
        } else {
            echo "支付失败";
            return;
        }
    }

    private function couponReceive($order2)
    {
        $videoCoupon = VideoCoupon::findOne($order2->coupon_id);
        \Yii::warning($videoCoupon);
        if(!$videoCoupon){
            return;
        }
        if($videoCoupon->total_count <= 0){
            echo '优惠券已领取完';
            return;
        }
        $user_coupon = new UserCoupon();
        $user_coupon->store_id = $videoCoupon->store_id;
        $user_coupon->user_id = $order2->user_id;
        $user_coupon->video_id = $order2->video_id;
        $user_coupon->coupon_id = $order2->video_coupon_id;
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
                echo "领取成功";
            }else{
                echo "领取失败";
                return;
            }
        }else{
            echo "领取失败";
            return;
        }
    }

    /**
     * @param $res
     * 会员购买  微信回调
     */
    private function wechatPayNotify_1($res)
    {
        if ($res['result_code'] != 'SUCCESS' && $res['return_code'] != 'SUCCESS')
            return;
        $order = MemberOrder::findOne([
            'order_no' => $res['out_trade_no'],
        ]);
        if (!$order)
            return;
        $store = Store::findOne($order->store_id);
        if (!$store)
            return;
        $wechat_app = WechatApp::findOne($store->wechat_app_id);
        if (!$wechat_app)
            return;
        $wechat = new Wechat([
            'appId' => $wechat_app->app_id,
            'appSecret' => $wechat_app->app_secret,
            'mchId' => $wechat_app->mch_id,
            'apiKey' => $wechat_app->key,
            'cachePath' => \Yii::$app->runtimePath . '/cache',
        ]);
        $new_sign = $wechat->pay->makeSign($res);
        if ($new_sign != $res['sign']) {
            echo "Sign 错误";
            return;
        }
        if ($order->is_pay == 1) {
            echo "订单已支付";
            return;
        }
        $order->is_pay = 1;
        $order->pay_time = time();
        if ($order->save()) {
            $this->due_time($order);
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return;
        } else {
            echo "支付失败";
            return;
        }
    }

    /**
     * 修改会员到期时间
     * @var \app\models\MemberOrder $order
     */
    private function due_time($order)
    {
        try {
            $user = User::findOne(['id' => $order->user_id, 'store_id' => $order->store_id]);
            $user->is_member = 1;
            if (!$user->due_time) {
                $user->due_time = time() + ($order->date * 86400);
            } else {
                $user->due_time += $order->date * 86400;
            }
            $user->save();
        } catch (\Exception $e) {

        }
    }
}