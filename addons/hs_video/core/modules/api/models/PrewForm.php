<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 16:47
 */

namespace app\modules\api\models;


use app\extensions\WechatTplMsg;
use app\models\FormId;
use app\models\Order;
use app\models\UserForm;
use app\models\Video;

class PrewForm extends Model
{
    public $store_id;
    public $user;

    public $video_id;
    public $formId;
    public $form_list;
    public $price;


    public function rules()
    {
        return [
            [['video_id','price'],'required'],
            [['formId','form_list'],'string'],
            [['price'],'number']
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $t = \Yii::$app->db->beginTransaction();
        $video = Video::findOne(['id'=>$this->video_id,'store_id'=>$this->store_id]);
        $order = new Order();
        $order->store_id = $this->store_id;
        $order->video_id = $this->video_id;
        $order->user_id = $this->user->id;
        $order->is_delete = 0;
        $order->addtime = time();
        $order->order_no = $this->getOrderNo();
        $order->is_use = 0;
        $order->use_time = 0;
        $order->is_refund = $video->refund;
        if($this->price == 0){
            $order->is_pay = 1;
            $order->pay_type = 1;
            $order->pay_time = time();
            FormId::addFormId([
                'store_id'=>$this->store_id,
                'user_id'=>$this->user->id,
                'wechat_open_id'=>$this->user->wechat_open_id,
                'form_id'=>$this->formId,
                'type'=>'form_id',
                'order_no'=>$order->order_no
            ]);
        }else{
            $order->is_pay = 0;
            $order->pay_time = 0;
        }
        $order->price = $this->price;
        $order->type = 0;
        if(!$order->save()){
            $t->rollBack();
            return $this->getModelError($order);
        }

        $form_list = json_decode($this->form_list,true);
        foreach($form_list as $index=>$value){
            if($value['required'] == 1 && !$value['value']){
                $t->rollBack();
                return [
                    'code'=>1,
                    'msg'=>$value['name'].'不能为空'
                ];
            }
            $user_form = new UserForm();
            $user_form->store_id = $this->store_id;
            $user_form->user_id = $this->user->id;
            $user_form->video_id = $this->video_id;
            $user_form->order_id = $order->id;
            $user_form->is_delete = 0;
            $user_form->addtime = time();
            $user_form->key = $value['name'];
            $user_form->value = $value['value'];
            if(!$user_form->save()){
                $t->rollBack();
                return $this->getModelError($user_form);
            }
        }
        $t->commit();
        if($order->is_pay == 1){
            $wechat_tpl_meg_sender = new WechatTplMsg($order->store_id, $order->id, $this->getWechat());
            $wechat_tpl_meg_sender->payMsg();
        }
        return [
            'code'=>0,
            'msg'=>'成功',
            'data'=>$order->id
        ];
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