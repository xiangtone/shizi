<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zjhj_video_store".
 *
 * @property integer $id
 * @property integer $acid
 * @property integer $user_id
 * @property integer $wechat_platform_id
 * @property integer $wechat_app_id
 * @property string $name
 * @property string $copyright
 * @property string $copyright_pic_url
 * @property string $copyright_url
 * @property string $contact_tel
 * @property string $show_customer_service
 * @property integer $pic_style
 * @property string $video_icon
 * @property string $audio_icon
 * @property integer $refund
 * @property integer $member
 * @property string $customer_service_pic
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zjhj_video_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acid', 'user_id', 'wechat_platform_id', 'wechat_app_id', 'pic_style', 'refund', 'member'], 'integer'],
            [['video_icon', 'audio_icon', 'customer_service_pic'], 'string'],
            [['name', 'copyright', 'copyright_pic_url', 'copyright_url', 'contact_tel', 'show_customer_service'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acid' => 'Acid',
            'user_id' => 'User ID',
            'wechat_platform_id' => 'Wechat Platform ID',
            'wechat_app_id' => 'Wechat App ID',
            'name' => 'Name',
            'copyright' => '版权文字',
            'copyright_pic_url' => '版权图片链接',
            'copyright_url' => '版权的超链接',
            'contact_tel' => 'Contact Tel',
            'show_customer_service' => '客服  1开启  0关闭',
            'pic_style' => '是否开启类型标签0--关闭 1--开启',
            'video_icon' => '视频标签',
            'audio_icon' => '音频标签',
            'refund' => '是否开启退款功能 0关闭 1--开启',
            'member' => '是否开通会员 0--不开通 1--开通',
            'customer_service_pic' => '客服图标',
        ];
    }
}
