<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "zjhj_coupon".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $draw_type
 * @property string $cost_price
 * @property string $coupon_price
 * @property string $original_cost
 * @property string $sub_price
 * @property integer $expire_type
 * @property integer $expire_day
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $addtime
 * @property integer $is_delete
 * @property integer $sort
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zjhj_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'original_cost'], 'required'],
            [['store_id', 'expire_type', 'draw_type','expire_day', 'begin_time', 'end_time', 'addtime', 'is_delete', 'sort'], 'integer'],
            [['original_cost','cost_price','coupon_price', 'sub_price'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => 'Name',
            'draw_type' => 'draw_type',
            'cost_price' => 'cost_price',
            'coupon_price' => 'coupon_price',
            'original_cost' => 'Original Cost',
            'sub_price' => 'Sub Price',
            'expire_type' => 'Expire Type',
            'expire_day' => 'Expire Day',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'sort' => 'Sort',
        ];
    }

    public static function userAddCoupon($video_id,$store_id ,$total_count, $user_num,$desc, $coupon_id,$coupon_name,$draw_type,$cost_price,$coupon_price,$original_cost,$sub_price,$expire_type,$expire_day,$begin_time,$end_time)
    {
        $coupon = Coupon::findOne([
            'id' => $coupon_id,
            'is_delete' => 0,
        ]);
        if (!$coupon) {
            return false;
        }
        $total_count_arr = [];
        foreach ($total_count as $key => &$value) {
            $total_count_arr[] = $value;
        }
        $user_num_arr = [];
        foreach ($user_num as $key => &$value) {
            $user_num_arr[] = $value;
        }
        $desc_arr = [];
        foreach ($desc as $key => &$value) {
            $desc_arr[] = $value;
        }
        $count = 0;
        foreach ($video_id as $key => &$value) {
            $video = Video::findOne($value);
            if (!$video) {
                return false;
            }
            $user_coupon = new VideoCoupon();
            $user_coupon->store_id = $video->store_id;
            $user_coupon->video_id = $video->id;
            $user_coupon->coupon_id = $coupon->id;
            $user_coupon->user_num = $user_num_arr[$key];
            $user_coupon->total_count = $total_count_arr[$key];
            $user_coupon->count = $total_count_arr[$key];
            $user_coupon->desc = $desc_arr[$key];
            $user_coupon->coupon_name = $coupon_name;
            $user_coupon->draw_type = $draw_type;
            $user_coupon->cost_price = $cost_price;
            $user_coupon->coupon_price = $coupon_price;
            $user_coupon->original_cost = $original_cost;
            $user_coupon->sub_price = $sub_price;
            $user_coupon->expire_type = $expire_type;
            $user_coupon->expire_day = $expire_day;
            $user_coupon->begin_time = $begin_time;
            $user_coupon->end_time = $end_time;
            $user_coupon->is_delete = 0;
            $user_coupon->addtime = time();
            $res = $user_coupon->save();
            $count++;
        }
        if($res){
            return [
                'code' => 0,
                'msg' => "操作完成，共发放{$count}个视频。",
            ];
        }else{
            return [
                'code' => 1,
                'msg' => "发放错误",
            ];
        }

    }


}

