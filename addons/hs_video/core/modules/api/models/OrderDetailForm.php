<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 10:20
 */

namespace app\modules\api\models;


use app\extensions\getInfo;
use app\extensions\TimeToDay;
use app\models\Order;
use app\models\UserForm;
use app\models\Video;

class OrderDetailForm extends Model
{
    public $store_id;
    public $user_id;
    public $order_id;

    public function rules()
    {
        return [
            [['order_id'], 'required']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $order = Order::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->order_id,
            'is_delete' => 0
        ]);
        var_dump($order);die();
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在'
            ];
        }
        $user_form = UserForm::find()->select([
            'key', 'value'
        ])->where(['store_id' => $this->store_id, 'order_id' => $this->order_id, 'is_delete' => 0])
            ->asArray()->all();
        $video = Video::find()->where([
            'id' => $order->video_id,
            'store_id' => $this->store_id,
            'is_delete' => 0
        ])->asArray()->one();
        $video['video_time'] = TimeToDay::time($video['video_time']);
        $video['page_view'] = TimeToDay::getPageView($video['page_view']);
        $video_list = VideoForm::getGroom($this->store_id,$order->video_id);
        if($video['type'] == 1){//腾讯视频解析
            $res = getInfo::getVideoInfo($video['video_url']);
            $video['video_url'] = $res['url'];
        }

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'video' => $video,
                'video_list' => $video_list,
                'user_form' => $user_form,
                'order' => [
                    'id' => $order->id,
                    'is_use' => $order->is_use,
                    'order_no' => $order->order_no,
                    'price' => $order->price,
                    'is_refund' => $order->is_refund,
                    'addtime' => date('Y-m-d H:i:s', $order->addtime),
                ]
            ]
        ];
    }
}