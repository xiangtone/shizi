<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 19:07
 */

namespace app\modules\api\models;


use app\extensions\getInfo;
use app\extensions\TimeToDay;
use app\models\Collect;
use app\models\Form;
use app\models\Order;
use app\models\User;
use app\models\Video;
use app\models\VideoPay;
use app\models\VideoCoupon;
use app\models\Coupon;
use app\models\UserCoupon;

class VideoForm extends Model
{
    public $store_id;
    public $video_id;
    public $user_id;

    public function rules()
    {
        return [
            [['video_id'], 'required']
        ];
    }

    public function getNum($num1, $num2) {
        $num = false;
        if(is_numeric($num1) && is_numeric($num2)) {
            $num = ( $num1 / $num2 ) * 100 ;
            return $num;
        } else {
            return $num;
        }
    }

    public function search()
    {
        if (!$this->validate())
            return $this->getModelError();

        $old_video = Video::findOne([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'id' => $this->video_id,
        ]);
        $old_video->page_view += 1;
        $old_video->save();
        $video = Video::find()->alias('v')->where([
            'v.store_id' => $this->store_id,
            'v.is_delete' => 0,
            'v.id' => $this->video_id,
        ])->select([
            'v.*'
        ])->asArray()->one();
        $video['video_time'] = TimeToDay::time($video['video_time']);
        $video['page_view'] = TimeToDay::getPageView($video['page_view']);
        if($video['type'] == 1){//腾讯视频解析
            $res = getInfo::getVideoInfo($video['video_url']);
            $video['video_url'] = $res['url'];
        }
        $video['collect_count'] = Collect::find()->where(['store_id' => $this->store_id, 'video_id' => $this->video_id, 'is_delete' => 0])->count();

        $form_list = Form::find()->select([
            'name', 'type', 'required', 'default', 'tip'
        ])->where(['store_id' => $this->store_id, 'video_id' => $video['id'], 'is_delete' => 0])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        $form_name = null;
        foreach ($form_list as $index => $value) {
            $form_list[$index]['value'] = $value['default'];
            if($value['type'] == 'form_name'){
                $form_name = $value['name'];
            }
        }
        $video['form_list'] = $form_list;
        $video['form_name'] = $form_name;
        $video_list = $this->getGroom($this->store_id,$this->video_id);
        $video_pay = VideoPay::find()->where(['store_id'=>$this->store_id,'video_id'=>$video['id']])->asArray()->orderBy(['id'=>SORT_DESC])->one();
        $video_pay['d_time'] = TimeToDay::date($video_pay['time']);
        if($this->user_id){
            $collect = Collect::find()->where(['store_id' => $this->store_id, 'video_id' => $this->video_id, 'user_id' => $this->user_id])->one();
            if (!$collect) {
                $video['collect'] = 1;
            } else {
                $video['collect'] = $collect->is_delete;
            }
            //查看该视频id是否购买
            $order1 = Order::find()->where([
                'store_id'=>$this->store_id,'type'=>1,'is_pay'=>1, 'video_id'=>$video['id'],
                'user_id'=>$this->user_id,'is_delete'=>0
            ])->exists();
            
            //查看分类id是否购买了
            $order2 = Order::find()->where([
                'store_id'=>$this->store_id,'type'=>1,'is_pay'=>1, 'product_id'=>$video['cat_id'],
                'user_id'=>$this->user_id,'is_delete'=>0
            ])->exists();
            
            //只要有一个买了就可以播放了
            if($order2 || $order1){
                $video['is_pay'] = 0;
            }
            
            $user = User::findOne(['id'=>$this->user_id]);
            if($user->is_member == 1){
                $video['is_pay'] = 0;
            }
        }else{
            $video['collect'] = 1;
        }

        $video_coupon= VideoCoupon::find()->where(['video_id'=>$this->video_id,'store_id'=>$this->store_id,'is_delete'=>0])->asArray()->all();
        foreach ($video_coupon as $key => &$value) {
            if($this->user_id){
                $userCoupon = UserCoupon::findBySql("select sum(num) as total_num from zjhj_video_coupon_user where video_coupon_id = '$value[id]' && user_id = $this->user_id && video_id = $this->video_id && store_id = $this->store_id GROUP BY video_coupon_id;")->asArray()->one();
            }else{
                $userCoupon = [];
            }

            if($userCoupon['total_num'] >= $value['user_num']){
                $value['type'] = 1;
            }else{
                $value['type'] = 0;
            }
            $value['num'] = 0;
            if($value['expire_type'] == 2){
                if($value['end_time'] < time()){
                    unset($video_coupon[$key]);
                }
            }
            if($value['draw_type'] == 2){
                $userCoupons = UserCoupon::findBySql("select sum(num) as total_num from zjhj_video_coupon_user where video_coupon_id = '$value[id]' && store_id = $this->store_id  && video_id = $this->video_id  GROUP BY video_coupon_id;")->asArray()->one();
                $value['percentage'] = (100 - round($this->getNum($userCoupons['total_num'],$value['count']))).'%';
            }
            if($value['total_count'] <= 0){
                unset($video_coupon[$key]);
            }
            if($userCoupon['total_num']){
                $value['num'] = $userCoupon['total_num'];
            }else{
                $value['num'] = 0;
            }
        }

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'video' => $video,
                'video_list' => $video_list,
                'video_pay'=>$video_pay,
                'video_coupon'=>$video_coupon,
            ]
        ];
    }

    /**
     * @param $store_id
     * @param $video_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getGroom($store_id,$video_id)
    {
        $video = Video::findOne(['id'=>$video_id,'store_id'=>$store_id]);
        $video_list = Video::find()->select([
            'id', 'pic_url', 'title', 'video_time','style'
        ])->where([
            'store_id' => $store_id,
            'is_delete' => 0,
            'cat_id' => $video['cat_id'],
            'status'=>0
        ])->andWhere(['!=', 'id', $video_id])->orderBy(['sort' => SORT_ASC])->limit(10)->asArray()->all();
        if (!$video_list) {
            $video_list = Video::find()->select([
                'id', 'pic_url', 'title', 'video_time'
            ])->where([
                'store_id' => $store_id,
                'is_delete' => 0,
                'status' => 0
            ])->andWhere(['!=', 'id', $video_id])->orderBy(['sort' => SORT_ASC])->limit(10)->asArray()->all();
        }
        foreach ($video_list as $index => $value) {
            $video_list[$index]['video_time'] = TimeToDay::time($value['video_time']);
        }
        return $video_list;
    }
}