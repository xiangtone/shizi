<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 10:07
 */

namespace app\modules\api\models;


use app\extensions\getInfo;
use app\extensions\TimeToDay;
use app\models\Order;
use app\models\UserForm;
use app\models\Video;

class ClerkDetailForm extends Model
{
    public $store_id;
    public $user;
    public $order_no;


    public function rules()
    {
        return [
            [['order_no'],'trim'],
            [['order_no'],'string'],
        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $order = Order::findOne([
            'store_id'=>$this->store_id,
            'order_no'=>$this->order_no,
            'is_delete'=>0,
        ]);
        if(!$order){
            return [
                'code'=>1,
                'msg'=>'订单不存在'
            ];
        }
        if($this->user->is_clerk != 1){
            return [
                'code'=>1,
                'msg'=>'不是核销员，无法核销！'
            ];
        }
        $user_form = UserForm::find()->select([
            'key','value'
        ])->where(['store_id'=>$this->store_id,'order_id'=>$order->id,'is_delete'=>0])
            ->asArray()->all();
        $video = Video::find()->where([
            'id'=>$order->video_id,
            'store_id'=>$this->store_id,
            'is_delete'=>0
        ])->asArray()->one();
        $video['video_time'] = TimeToDay::time($video['video_time']);
        $video['page_view'] = TimeToDay::getPageView($video['page_view']);
        if($video['type'] == 1){//腾讯视频解析
            $res = getInfo::getVideoInfo($video['video_url']);
            $video['video_url'] = $res['url'];
        }
        return [
            'code'=>0,
            'msg'=>'success',
            'data'=>[
                'video'=>$video,
                'user_form'=>$user_form,
                'order'=>[
                    'id'=>$order->id,
                    'is_use'=>$order->is_use,
                    'order_no'=>$order->order_no,
                    'addtime'=>date('Y-m-d H:i:s',$order->addtime),
                ]
            ]
        ];
    }
}