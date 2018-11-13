<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zjhj_video_coupon_user".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $video_id
 * @property integer $coupon_id
 * @property integer $num
 * @property integer $is_expire
 * @property integer $is_use
 * @property string $desc
 * @property string $clerk
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
 * @property integer $is_delete
 * @property integer $video_coupon_id
 */
class UserCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '%coupon_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'video_id', 'coupon_id', 'num', 'desc', 'coupon_name', 'cost_price', 'original_cost','video_coupon_id'], 'required'],
            [['store_id', 'user_id', 'video_id', 'coupon_id', 'num', 'is_expire', 'is_use', 'draw_type', 'expire_type', 'expire_day', 'begin_time', 'end_time', 'addtime', 'is_delete'], 'integer'],
            [['desc', 'clerk'], 'string'],
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
            'user_id' => 'User ID',
            'video_id' => 'Video ID',
            'coupon_id' => 'Coupon ID',
            'num' => 'Num',
            'is_expire' => 'Is Expire',
            'is_use' => 'Is Use',
            'desc' => 'Desc',
            'clerk' => 'Clerk',
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
            'is_delete' => 'Is Delete',
            'video_coupon_id' => 'video_coupon_id',
        ];
    }

    public function getVideo()
    {
        return $this->hasMany(Video::className(), ['id' => 'video_id']);
    }
    public function getUser()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id']);
    }
}
