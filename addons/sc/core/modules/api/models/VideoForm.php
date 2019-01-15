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
use app\models\UserCoupon;
use app\models\Video;
use app\models\VideoCoupon;
use app\models\VideoPay;
use app\models\CatPay;
use app\models\Cat;

class VideoForm extends Model
{
    public $store_id;
    public $store;
    public $video_id;
    public $user_id;
    public $cat_id;
    public $sort;
    public $direction;

    public function rules()
    {
        return [
            [['video_id'], 'required'],
            [['cat_id', 'sort'], 'integer'],
            [['direction'], 'string'],
        ];
    }

    public function getNum($num1, $num2)
    {
        $num = false;
        if (is_numeric($num1) && is_numeric($num2)) {
            $num = ($num1 / $num2) * 100;
            return $num;
        } else {
            return $num;
        }
    }

    public function nextVideo()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }
        $next_video = Video::find()->where([
            'cat_id' => $this->cat_id,
            'status' => 0,
        ]);
        if ($this->direction == 'next') {
            $next_video->andWhere(['>', 'sort', $this->sort])->orderBy('sort');
        } else {
            $next_video->andWhere(['<', 'sort', $this->sort])->orderBy('sort DESC');
        }
        $next_video = $next_video->select([
            'id',
        ])->asArray()->one();
        if ($next_video) {
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'next_video' => $next_video,

                ],
            ];
        } else {
            if ($this->direction == 'next') {
                return [
                    'code' => 2,
                    'msg' => '已经是本册最后一课',
                ];
            } else {
                return [
                    'code' => 2,
                    'msg' => '已经是本册第一课',
                ];
            }
        }
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

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
        ])->leftJoin(['c' => Cat::tableName()], 'c.id = v.cat_id')->select([
            'v.*','c.is_pay as cat_is_pay'
        ])->asArray()->one();
        $video['video_time'] = TimeToDay::time($video['video_time']);
        $video['page_view'] = TimeToDay::getPageView($video['page_view']);
        if ($video['type'] == 1) { //腾讯视频解析
            $res = getInfo::getVideoInfo($video['video_url']);
            $video['video_url'] = $res['url'];
        }
        $video['collect_count'] = Collect::find()->where(['store_id' => $this->store_id, 'video_id' => $this->video_id, 'is_delete' => 0])->count();

        $form_list = Form::find()->select([
            'name', 'type', 'required', 'default', 'tip',
        ])->where(['store_id' => $this->store_id, 'video_id' => $video['id'], 'is_delete' => 0])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        $form_name = null;
        foreach ($form_list as $index => $value) {
            $form_list[$index]['value'] = $value['default'];
            if ($value['type'] == 'form_name') {
                $form_name = $value['name'];
            }
        }
        $video['form_list'] = $form_list;
        $video['form_name'] = $form_name;
        $video_list = $this->getGroom($this->store_id, $this->video_id);
        $video_pay = VideoPay::find()->where(['store_id' => $this->store_id, 'video_id' => $video['id']])->asArray()->orderBy(['id' => SORT_DESC])->one();
        $video_pay['d_time'] = TimeToDay::date($video_pay['time']);
        $video['checkMark'] = 0;
        // if ($video_pay){
        //     if ($video_pay['price']=='0'){
        //         $video['is_pay'] = 0;
        //         $video['checkMark'] = 1;
        //     }
        // }
        if ($video['cat_is_pay']==0){
            $video['is_pay']  = 0;
        }
        $cat_pay = CatPay::find()->where(['store_id' => $this->store_id, 'cat_id' => $video['cat_id']])->asArray()->orderBy(['id' => SORT_DESC])->one();
        if($cat_pay){
            if (!($cat_pay['price']>0)){
                $video['is_pay'] = 0;
            }
            $video_pay['price'] = $cat_pay['price'];
        }
        if ($this->user_id) {
            $collect = Collect::find()->where(['store_id' => $this->store_id, 'video_id' => $this->video_id, 'user_id' => $this->user_id])->one();
            if (!$collect) {
                $video['collect'] = 1;
            } else {
                $video['collect'] = $collect->is_delete;
            }
            //查看该视频id是否购买
            $order1 = Order::find()->where([
                'store_id' => $this->store_id, 'type' => 1, 'is_pay' => 1, 'video_id' => $video['id'],'product_type'=>'video',
                'user_id' => $this->user_id, 'is_delete' => 0,
            ])->exists();

            //查看分类id是否购买了
            $order2 = Order::find()->where([
                'store_id' => $this->store_id, 'type' => 1, 'is_pay' => 1, 'product_id' => $video['cat_id'],'product_type'=>'cat',
                'user_id' => $this->user_id, 'is_delete' => 0,
            ])->exists();

            //只要有一个买了就可以播放了
            if ($order2 || $order1) {
                $video['is_pay'] = 0;
            }

            if ($this->store->default_coupon_price){
                $orderAny = Order::find()->where([
                    'store_id' => $this->store_id, 'type' => 1, 'is_pay' => 1, 
                    'user_id' => $this->user_id, 'is_delete' => 0,
                ])->exists();
                if ($orderAny){
                    if ($video_pay['price']>$this->store->default_coupon_price){
                        $video_pay['price_origin'] = $video_pay['price'];
                        $video_pay['price'] = $video_pay['price']-$this->store->default_coupon_price;
                        $video_pay['default_coupon_price'] = $this->store->default_coupon_price;
                    }   
                }
            }

            $user = User::findOne(['id' => $this->user_id]);
            if ($user->is_member == 1) {
                $video['is_pay'] = 0;
            }
            if ($video['style'] == 0) {
                $user->last_video = $video['id'];
                $user->save();
            }
        } else {
            $video['collect'] = 1;
        }

        $video_coupon = VideoCoupon::find()->where(['video_id' => $this->video_id, 'store_id' => $this->store_id, 'is_delete' => 0])->asArray()->all();
        foreach ($video_coupon as $key => &$value) {
            if ($this->user_id) {
                $userCoupon = UserCoupon::findBySql("select sum(num) as total_num from zjhj_video_coupon_user where video_coupon_id = '$value[id]' && user_id = $this->user_id && video_id = $this->video_id && store_id = $this->store_id GROUP BY video_coupon_id;")->asArray()->one();
            } else {
                $userCoupon = [];
            }

            if ($userCoupon['total_num'] >= $value['user_num']) {
                $value['type'] = 1;
            } else {
                $value['type'] = 0;
            }
            $value['num'] = 0;
            if ($value['expire_type'] == 2) {
                if ($value['end_time'] < time()) {
                    unset($video_coupon[$key]);
                }
            }
            if ($value['draw_type'] == 2) {
                $userCoupons = UserCoupon::findBySql("select sum(num) as total_num from zjhj_video_coupon_user where video_coupon_id = '$value[id]' && store_id = $this->store_id  && video_id = $this->video_id  GROUP BY video_coupon_id;")->asArray()->one();
                $value['percentage'] = (100 - round($this->getNum($userCoupons['total_num'], $value['count']))) . '%';
            }
            if ($value['total_count'] <= 0) {
                unset($video_coupon[$key]);
            }
            if ($userCoupon['total_num']) {
                $value['num'] = $userCoupon['total_num'];
            } else {
                $value['num'] = 0;
            }
        }

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'video' => $video,
                'video_list' => $video_list,
                'video_pay' => $video_pay,
                'video_coupon' => $video_coupon,
                'store' => array('default_coupon_price'=>$this->store->default_coupon_price,
                'enable_ios_pay'=>$this->store->enable_ios_pay,),
            ],
        ];
    }

    /**
     * @param $store_id
     * @param $video_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getGroom($store_id, $video_id)
    {
        $video = Video::findOne(['id' => $video_id, 'store_id' => $store_id]);
        $video_list = Video::find()->select([
            'id', 'pic_url', 'title', 'video_time', 'style',
        ])->where([
            'store_id' => $store_id,
            'is_delete' => 0,
            'cat_id' => $video['cat_id'],
            'status' => 0,
        ])->andWhere(['!=', 'id', $video_id])->orderBy(['sort' => SORT_ASC])->limit(10)->asArray()->all();
        if (!$video_list) {
            $video_list = Video::find()->select([
                'id', 'pic_url', 'title', 'video_time',
            ])->where([
                'store_id' => $store_id,
                'is_delete' => 0,
                'status' => 0,
            ])->andWhere(['!=', 'id', $video_id])->orderBy(['sort' => SORT_ASC])->limit(10)->asArray()->all();
        }
        foreach ($video_list as $index => $value) {
            $video_list[$index]['video_time'] = TimeToDay::time($value['video_time']);
        }
        return $video_list;
    }
}
