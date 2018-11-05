<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22
 * Time: 11:55
 */

namespace app\modules\api\models;


use app\models\FormId;
use app\models\MemberOrder;

/**
 * @property \app\models\User $user
 * @property \app\models\Order $order
 */
class MemberOrderData extends Model
{
    public $store_id;
    public $order_id;
    public $user;
    public $pay_type;

    private $wechat;
    private $order;

    public function rules()
    {
        return [
            [['order_id','pay_type'],'required'],
            [['pay_type'],'in','range'=>['WECHAT_PAY','ALIPAY']]
        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $this->wechat = $this->getWechat();
        $this->order = MemberOrder::findOne(['store_id'=>$this->store_id,'id'=>$this->order_id]);
        if(!$this->order){
            return [
                'code'=>1,
                'msg'=>'订单不存在'
            ];
        }
        $body = mb_substr($this->order->title,0,32,'utf-8');
        if($this->pay_type == 'WECHAT_PAY'){
            $res = $this->unifiedOrder($body);
            if(isset($res['code']) && $res['code'] == 1){
                return $res;
            }
            FormId::addFormId([
                'store_id'=>$this->store_id,
                'user_id'=>$this->user->id,
                'wechat_open_id'=>$this->user->wechat_open_id,
                'form_id'=>$res['prepay_id'],
                'type'=>'prepay_id',
                'order_no'=>$this->order->order_no
            ]);

            $pay_data = [
                'appId' => $this->wechat->appId,
                'timeStamp' => '' . time(),
                'nonceStr' => md5(uniqid()),
                'package' => 'prepay_id=' . $res['prepay_id'],
                'signType' => 'MD5',
            ];
            $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => (object)$pay_data,
                'res' => $res,
                'body' => $body,
            ];
        }


    }

    private function unifiedOrder($goods_names)
    {
        $res = $this->wechat->pay->unifiedOrder([
            'body' => $goods_names,
            'out_trade_no' => $this->order->order_no,
            'total_fee' => $this->order->price * 100,
            'notify_url' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/pay-notify.php',
            'trade_type' => 'JSAPI',
            'openid' => $this->user->wechat_open_id,
        ]);
        if (!$res)
            return [
                'code' => 1,
                'msg' => '支付失败',
            ];
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '支付失败，' . (isset($res['return_msg']) ? $res['return_msg'] : ''),
                'res' => $res,
            ];
        }
        if ($res['result_code'] != 'SUCCESS') {
            if ($res['err_code'] == 'INVALID_REQUEST') {//商户订单号重复
                $this->order->order_no = (new PrewForm())->getOrderNo();
                $this->order->save();
                return $this->unifiedOrder($goods_names);
            } else {
                return [
                    'code' => 1,
                    'msg' => '支付失败，' . (isset($res['err_code_des']) ? $res['err_code_des'] : ''),
                    'res' => $res,
                ];
            }
        }
        return $res;
    }
}