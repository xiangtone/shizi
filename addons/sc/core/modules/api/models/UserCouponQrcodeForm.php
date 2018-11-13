<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/26
 * Time: 9:21
 */

namespace app\modules\api\models;

use app\models\UserCoupon;
use Curl\Curl;

class UserCouponQrcodeForm extends Model
{
    public $store_id;
    public $user_coupon_id;
    public $user_id;

    public function rules()
    {
        return [
            [['user_coupon_id'], 'required']
        ];
    }

    public function search()
    {
        $userCoupon = UserCoupon::findOne($this->user_coupon_id);
        if (!$userCoupon)
            return [
                'code' => 1,
                'msg' => '优惠券不存在',
            ];

        //获取小程序码图片
        $wxapp_qrcode_file_res = $this->getQrcode($userCoupon->id);

        if ($wxapp_qrcode_file_res['code'] == 1) {
            return [
                'code' => 1,
                'msg' => '获取小程序码失败，' . $wxapp_qrcode_file_res['msg'],
            ];
        }else{
            $goods_qrcode_url = $wxapp_qrcode_file_res['file_path'];
        }
        $goods_pic_save_path = \Yii::$app->basePath . '/web/temp/';
        $goods_pic_save_name = md5("v=1.6.2&goods_id={$userCoupon->id}&goods_name={$userCoupon->desc}") . '.jpg';
        $goods_qrcode_path = $goods_pic_save_path.$goods_pic_save_name;

        $fp = fopen($goods_qrcode_path, 'w');
        fwrite($fp, $goods_qrcode_url);
        fclose($fp);

        $goods_qrcode_url = trim(strrchr($goods_qrcode_path, '/'),'/');
        $pic_url = str_replace('https://', 'https://', \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/temp/' . $goods_qrcode_url);
        return [
            'code' => 0,
            'data' => [
                'pic_url' => $pic_url . '?v=' . time(),
            ],
        ];

    }

    private function getQrcode($userCouponId)
    {
        $wechat = $this->getWechat();
        $access_token = $wechat->getAccessToken();
        if (!$access_token) {
            return [
                'code' => 1,
                'msg' => $wechat->errMsg,
            ];
        }
        $api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$access_token}";
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $data = json_encode([
            'scene' => "cid:{$userCouponId}",
            'page' => 'pages/cancel-coupon/cancel-coupon',
//            'page' => '',
            'width' => 	430,
        ]);

        \Yii::trace("GET WXAPP QRCODE:" . $data);
        $curl->post($api, $data);

        if (in_array('Content-Type: image/jpeg', $curl->response_headers)) {
            //返回图片
            return [
                'code' => 0,
                'file_path' => $curl->response,
            ];
        } else {
            //返回文字
            $res = json_decode($curl->response, true);
            return [
                'code' => 1,
                'msg' => $res['errmsg'],
            ];
        }
    }


}