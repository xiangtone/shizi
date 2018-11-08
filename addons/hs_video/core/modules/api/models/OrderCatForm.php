<?php
/***
    视频分类表格视图
 */
namespace app\modules\api\models;


use app\extensions\WechatTplMsg;
use app\models\OrderCat;
use app\models\Video;

class OrderCatForm extends Model
{
    public $store_id;
    public $user;

    public $cat_id;
    public $price;

    public function rules()
    {
        return [
            [['cat_id','price'],'required'],
            [['price'],'number']
        ];
    }

    public function save()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $order_cat = new OrderCat();
        $order_cat->store_id = $this->store_id;
        $order_cat->cat_id  = $this->cat_id;//视频分类id
        $order_cat->user_id = $this->user->id;
        $order_cat->is_delete = 0;
        $order_cat->addtime = time();
        $order_cat->order_no = $this->getOrderNo();
        $order_cat->is_pay = 0;
        $order_cat->is_refund = 0;
        $order_cat->pay_time = 0;
        $order_cat->type = 1;
        $order_cat->pay_type = 0;
        $order_cat->price = $this->price;
        if($order_cat->save()){
            return [
                'code'=>0,
                'msg'=>'成功',
                'data'=>$order_cat->id
            ];
        }
    }

    public function getOrderNo()
    {
        $order_no = null;
        while(true){
            $order_no = date('YmdHis').rand(100000,999999);
            $exit = OrderCat::find()->where(['order_no'=>$order_no])->exists();
            if(!$exit){
                break;
            }
        }
        return $order_no;
    }
}