<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 14:07
 */

namespace app\modules\admin\models;


use app\models\Option;
use app\models\Store;
use app\models\WechatApp;

class StoreSettingForm extends Model
{
    public $store_id;


    public $name;
    public $app_id;
    public $app_secret;
    public $contact_tel;
    public $show_customer_service;
    public $customer_service_pic;
    public $copyright_pic_url;
    public $copyright;
    public $copyright_url;

    public $mch_id;
    public $key;
    public $cert_pem;
    public $key_pem;

    public $pic_style;
    public $video_icon;
    public $audio_icon;
    public $refund;
    public $member;


    public function rules()
    {
        return [
            [['name','app_id','app_secret','contact_tel','copyright_pic_url','copyright','copyright_url'],'trim'],
            [['name','app_id','app_secret','mch_id','key','cert_pem','key_pem'],'required'],
            [['show_customer_service','pic_style','refund','member'],'integer'],
            [['contact_tel','video_icon','audio_icon','customer_service_pic'],'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '店铺名称',
            'app_id' => '小程序AppId',
            'app_secret' => '小程序AppSecret',
            'mch_id' => '商户号id',
            'key' => '微信支付key',
            'cert_pem' => '微信支付cert_pem',
            'key_pem' => '微信支付key_pem',
        ];
    }
    public function save()
    {
        if(!$this->validate())
            return $this->getModelError();
        $store = Store::findOne(['id'=>$this->store_id]);
        $store->name = $this->name;
        $store->contact_tel = $this->contact_tel;
        $store->show_customer_service = $this->show_customer_service;
        $store->customer_service_pic = $this->customer_service_pic;
        $store->copyright_pic_url = $this->copyright_pic_url;
        $store->copyright = $this->copyright;
        $store->copyright_url = $this->copyright_url;
        $store->pic_style = $this->pic_style;
        $store->video_icon = $this->video_icon;
        $store->audio_icon = $this->audio_icon;
        $store->refund = $this->refund;
        $store->member = $this->member;
        $store->save();

        $wechat_app = WechatApp::findOne($store->wechat_app_id);
        $wechat_app->app_id = $this->app_id;
        $wechat_app->app_secret = $this->app_secret;
        $wechat_app->mch_id = $this->mch_id;
        $wechat_app->key = $this->key;
        $wechat_app->cert_pem = $this->cert_pem;
        $wechat_app->key_pem = $this->key_pem;
        $wechat_app->save();

        return [
            'code' => 0,
            'msg' => '保存成功',
        ];
    }

}