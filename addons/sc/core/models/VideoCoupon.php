<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zjhj_video_coupon".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $video_id
 * @property integer $coupon_id
 * @property integer $is_delete
 * @property integer $user_num
 * @property integer $total_count
 * @property integer $count
 * @property string $desc
 * @property string $coupon_name
 * @property integer $draw_type
 * @property string $cost_price
 * @property string $coupon_price
 * @property string $original_cost
 * @property string $sub_price
 * @property integer $expire_type
 * @property integer $expire_day
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $addtime
 */
class VideoCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '%coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'video_id', 'coupon_id', 'user_num', 'total_count','count', 'desc', 'coupon_name', 'cost_price', 'original_cost'], 'required'],
            [['store_id', 'video_id', 'coupon_id', 'is_delete', 'user_num', 'total_count', 'draw_type', 'expire_type', 'expire_day', 'begin_time', 'end_time', 'addtime'], 'integer'],
            [['desc'], 'string'],
            [['cost_price', 'coupon_price', 'original_cost', 'sub_price'], 'number'],
            [['coupon_name'], 'string', 'max' => 255],
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
            'video_id' => 'Video ID',
            'coupon_id' => 'Coupon ID',
            'is_delete' => 'Is Delete',
            'user_num' => 'User Num',
            'total_count' => 'Total Count',
            'desc' => 'Desc',
            'coupon_name' => 'Coupon Name',
            'draw_type' => 'Draw Type',
            'cost_price' => 'Cost Price',
            'coupon_price' => 'Coupon Price',
            'original_cost' => 'Original Cost',
            'sub_price' => 'Sub Price',
            'expire_type' => 'Expire Type',
            'expire_day' => 'Expire Day',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'addtime' => 'Addtime',
        ];
    }

//    public function getCoupon()
//    {
//        return $this->hasMany(Coupon::className(), ['id' => 'coupon_id']);
//    }
    public function getVideo()
    {
        return $this->hasMany(Video::className(), ['id' => 'video_id']);
    }
}
