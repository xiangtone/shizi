<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 14:26
 */

namespace app\modules\api\controllers;


use app\models\User;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\BuyVideoForm;
use app\modules\api\models\CollectForm;
use app\modules\api\models\CollectVideoForm;
use app\modules\api\models\CommentForm;
use app\modules\api\models\CommentListForm;
use app\modules\api\models\ListForm;
use app\modules\api\models\PrewForm;
use app\modules\api\models\ThumpForm;
use app\modules\api\models\UserCommentForm;
use app\modules\api\models\UserCouponQrcodeForm;
use app\modules\api\models\VideoForm;
use app\modules\api\models\UserCouponForm;
use app\models\UserCoupon;
use app\modules\api\models\UserForm;
use app\extensions\Sms;
use app\models\SmsSetting;

class UserController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
                'ignore'=>['video-list','video','index','comment-list']
            ],
        ]);
    }

    /**
     * 收藏&取消收藏
     */
    public function actionCollect()
    {
        $form = new CollectForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->save());
    }

    /**
     * 视频详情
     */
    public function actionVideo()
    {
        $form = new VideoForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    /**
     * 收藏的视频列表
     */
    public function actionCollectVideo()
    {
        $form = new CollectVideoForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
    /**
     * 首页视频列表--分类
     */
     public function actionVideoCatList()
     {
         $form = new ListForm();
         $form->store_id = $this->store->id;
         $form->user_id = \Yii::$app->user->identity->id;
         $form->attributes = \Yii::$app->request->get();
         $this->renderJson($form->getCatList());
     }
    /**
     * 首页视频列表
     */
    public function actionVideoList()
    {
        $form = new ListForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->getList());
    }

    /**
     * 评论发布
     */
    public function actionComment()
    {
        $post = \Yii::$app->request->post();
        $form = new CommentForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = $post;
        $this->renderJson($form->save());
    }

    /**
     * 评论列表
     */
    public function actionCommentList()
    {
        $form = new CommentListForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    /**
     * 未评论点赞
     */
    public function actionThump()
    {
        $form = new ThumpForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->save());
    }

    /**
     * 评论过我的
     */
    public function actionUserComment()
    {
        $form = new UserCommentForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    /**
     * 用户状态
     */
    public function actionIndex()
    {
        $user = User::findOne(['id' => \Yii::$app->user->identity->id, 'store_id' => $this->store->id]);
        if (!$user) {
            $this->renderJson([
                'code' => -2,
                'msg' => ''
            ]);
        }
        $due_time = date('Y-m-d', $user->due_time);
        $this->renderJson([
            'code' => 0,
            'msg' => '',
            'data' => [
                'user_info' => (object)[
                    'access_token' => $user->access_token,
                    'nickname' => $user->nickname,
                    'avatar_url' => $user->avatar_url,
                    'id' => $user->id,
                    'is_comment' => $user->is_comment,
                    'is_clerk' => $user->is_clerk,
                    'is_member' => $user->is_member,
                    'due_time' => $user->due_time ? $due_time : 0,
                    'binding' => $user->binding,
                ]
            ]
        ]);
    }

    /**
     * 已购买过的视频
     */
    public function actionBuyVideo()
    {
        $form = new BuyVideoForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

//领取优惠券
    public function actionCouponReceive()
    {
        $form = new UserCouponForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->save());
    }

//我的优惠卷
    public function actionUserCoupon()
    {
        $form = new UserCouponForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $this->renderJson($form->search());
    }

//    优惠券小程序码
    public function actionUserCouponQrcode()
    {
        $form = new UserCouponQrcodeForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

//    核销优惠券
    public function actionCancelCoupon()
    {
        $form = new UserCouponForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->cancelcoupon());
    }
    public function actionClerk()
    {
        $form = new UserCouponForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->clerk());
    }
//    绑定手机号
    public function actionUserBinding()
    {
        $form = new UserForm();
        $form->attributes = \Yii::$app->request->post();
        $form->wechat_app = $this->wechat_app;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->store_id = $this->store->id;
        $this->renderJson($form->binding());
    }
//    短信验证
    public function actionUserHandBinding()
    {
        $form = new Sms();
        $form->attributes = \Yii::$app->request->post();
        $code = mt_rand(0,999999);
        $this->renderJson($form->send_text($this->store->id,$code,$form->attributes['content']));
    }

//    授权手机号确认
    public function actionUserEmpower()
    {
        $form = new UserForm();
        $form->attributes = \Yii::$app->request->post();
        $form->user_id = \Yii::$app->user->identity->id;
        $form->store_id = $this->store->id;
        $this->renderJson($form->userEmpower());
    }
//    短信验证是否开启
    public function actionSmsSetting()
    {
        $sms_setting = SmsSetting::findOne(['is_delete' => 0, 'store_id' => $this->store->id]);
        if($sms_setting->status == 1){
            $this->renderJson([
                'code'=>0,
                'data'=>$sms_setting->status
            ]);
        }else{
            $this->renderJson([
                'code'=>1,
                'data'=>$sms_setting->status
            ]);
        }
    }

}