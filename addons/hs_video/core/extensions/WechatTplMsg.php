<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21
 * Time: 9:36
 */

namespace app\extensions;


use app\models\FormId;
use app\models\Option;
use app\models\Order;
use app\models\Store;
use app\models\User;
use app\models\Video;
use luweiss\wechat\Wechat;

/**
 * @property Store $store
 * @property User $user
 * @property Order $order
 * @property Wechat $wechat
 * @property FormId $formId
 */
class WechatTplMsg
{
    public $store_id;
    public $order_id;


    public $store;
    public $order;
    public $user;
    public $wechat;
    public $formId;

    /**
     * @param $store_id
     * @param $order_id
     * @param $wechat
     */
    public function __construct($store_id,$order_id,$wechat)
    {
        $this->store = Store::findOne(['id'=>$store_id]);
        $this->order = Order::findOne(['id'=>$order_id]);
        $this->wechat = $wechat;
        if(!$this->order){
            return ;
        }
        $this->user = User::findOne(['id'=>$this->order->user_id]);
    }

    /**
     * 发送支付通知模板消息
     */
    public function payMsg()
    {
        $this->formId = FormId::find()->where(['store_id'=>$this->store->id,'order_no'=>$this->order->order_no])
            ->orderBy('id DESC')->one();
        $pay_tpl = Option::get('pay_tpl',$this->store->id,'tpl');
        try{
            if(!$pay_tpl){
                return ;
            }
            $video = Video::findOne(['id'=>$this->order->video_id,'store_id'=>$this->store->id]);
            $data = [
                'touser'=>$this->user->wechat_open_id,
                'template_id'=>$pay_tpl,
                'form_id'=>$this->formId->form_id,
                'page'=>'pages/order/order?status=1',
                'data'=>[
                    'keyword1'=>[
                        'value'=>$this->order->order_no,
                        'color'=>'#333333'
                    ],
                    'keyword2'=>[
                        'value'=>$this->order->price!=0?$this->order->price:'免费',
                        'color'=>'#333333'
                    ],
                    'keyword3'=>[
                        'value'=>$video->title,
                        'color'=>'#333333'
                    ]
                ]
            ];
            $this->sendTplMsg($data);
        }catch(\Exception $e){
            \Yii::warning($e->getMessage());
        }
    }

    /**
     * 发送退款通知模板消息
     * @param double $refund_price 退款金额
     * @param string $refund_reason 退款原因
     * @param string $remark 备注
     */
    public function refundMsg($refund_price,$refund_reason = '', $remark = '')
    {
        $this->formId = FormId::find()->where(['store_id'=>$this->store->id,'order_no'=>$this->order->order_no])
            ->orderBy('id DESC')->one();
        $refund_tpl = Option::get('refund_tpl',$this->store->id,'tpl');
        try{
            if(!$refund_tpl){
                return ;
            }
            $data = [
                'touser'=>$this->user->wechat_open_id,
                'template_id'=>$refund_tpl,
                'form_id'=>$this->formId->form_id,
                'page'=>'pages/order/order?status=3',
                'data'=>[
                    'keyword1'=>[
                        'value'=>$refund_price,
                        'color'=>'#333333'
                    ],
                    'keyword2'=>[
                        'value'=>$this->order->order_no,
                        'color'=>'#333333'
                    ],
                    'keyword3'=>[
                        'value'=>$refund_reason,
                        'color'=>'#333333'
                    ],
                    'keyword4'=>[
                        'value'=>$remark,
                        'color'=>'#333333'
                    ]
                ]
            ];
            $this->sendTplMsg($data);
        }catch(\Exception $e){
            \Yii::warning($e->getMessage());
        }
    }

    private function sendTplMsg($data)
    {
        $access_token = $this->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$access_token}";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->wechat->curl->post($api, $data);
        $res = json_decode($this->wechat->curl->response, true);
        if (!empty($res['errcode']) && $res['errcode'] != 0) {
            \Yii::warning("模板消息发送失败：\r\ndata=>{$data}\r\nresponse=>" . json_encode($res, JSON_UNESCAPED_UNICODE));
        }else{
            $this->formId->send_count += 1;
            $this->formId->save();
        }
    }

}