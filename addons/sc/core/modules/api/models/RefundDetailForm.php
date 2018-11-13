<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 15:23
 */

namespace app\modules\api\models;


use app\extensions\getInfo;
use app\extensions\TimeToDay;
use app\models\OrderRefund;
use app\models\Video;

class RefundDetailForm extends Model
{
    public $store_id;
    public $user;
    public $order_id;

    public function rules()
    {
        return [
            [['order_id'],'required']
        ];
    }

    public function search()
    {
        if(!$this->validate()){
            return $this->getModelError();
        }
        $order = OrderRefund::find()->where([
            'store_id'=>$this->store_id,
            'user_id'=>$this->user->id,
            'order_id'=>$this->order_id,
            'is_delete'=>0
        ])->orderBy(['addtime'=>SORT_DESC])->asArray()->one();
        $order['addtime'] = date('Y-m-d H:i:s',$order['addtime']);
        $video = Video::find()->select([
            'title','pic_url','video_url','content','video_time','status','page_view','style'
        ])->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>$order['video_id']])->asArray()->one();
        $video['video_time'] = TimeToDay::time($video['video_time']);
        $video['page_view'] = TimeToDay::getPageView($video['page_view']);
        if($video['type'] == 1){//腾讯视频解析
            $res = getInfo::getVideoInfo($video['video_url']);
            $video['video_url'] = $res['url'];
        }
        return [
            'code'=>0,
            'msg'=>'',
            'data'=>[
                'video'=>$video,
                'order'=>$order
            ]
        ];
    }
}