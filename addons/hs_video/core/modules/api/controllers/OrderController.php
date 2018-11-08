<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 11:43
 */

namespace app\modules\api\controllers;


use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\ClerkDetailForm;
use app\modules\api\models\MemberOrderData;
use app\modules\api\models\MemberOrderForm;
use app\modules\api\models\OrderClerkForm;
use app\modules\api\models\OrderDetailForm;
use app\modules\api\models\OrderListForm;
use app\modules\api\models\OrderPayDataForm;
use app\modules\api\models\OrderRefundForm;
use app\modules\api\models\OrderVideoForm;
use app\modules\api\models\PrewForm;
use app\modules\api\models\QrocdeForm;
use app\modules\api\models\RefundDetailForm;
use app\modules\api\models\CouponOrderForm;
use app\modules\api\models\OrderCatForm;
use app\modules\api\models\OrderPayCatForm;
class OrderController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }
     /**
     * 购买视频
     */
    public function actionVideo()
    {
        $form = new OrderVideoForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->save());
    }
    /**
    * 购买分类
    */
    public function actionCat()
    {

        $form = new OrderCatForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->save());
    }
    /**
     * 表单提交
     */
    public function actionPrew()
    {
        $form = new PrewForm();
        $post = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = $post;
        $this->renderJson($form->save());
    }
    /**
     * 获取支付数据
     */
    public function actionGetPayData()
    {
        //$form = new OrderPayDataForm();
        $form = new OrderPayCatForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->search());
    }
    /**
     * 获取订单列表
     */
    public function actionList()
    {
        $form = new OrderListForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
    /**
     * 获取二维码
     */
    public function actionQrcode()
    {
        $form = new QrocdeForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
    /**
     * 获取订单详情
     */
    public function actionDetail()
    {
        $form = new OrderDetailForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
    /**
     * 获取核销订单详情
     */
    public function actionClerkDetail()
    {
        $form = new ClerkDetailForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
    /**
     * 核销订单
     */
    public function actionClerk()
    {
        $form = new OrderClerkForm();
        $form->order_id = \Yii::$app->request->get('order_id');
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $this->renderJson($form->save());
    }
    /**
     * 退款申请
     */
    public function actionRefund()
    {
        $form = new OrderRefundForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->save());
    }
    /**
     * 退款预览
     */
    public function actionRefundPrew()
    {
        $form = new OrderDetailForm();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->identity->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
    /**
     * 退款订单详情
     */
    public function actionRefundDetail()
    {
        $form = new RefundDetailForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }
   

    /**
     * 购买优惠券
     */
    public function actionVideoCoupon()
    {
        $form = new CouponOrderForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->save());
    }

    /**
     * 购买会员 表单提交
     */
    public function actionMemberOrder()
    {
        $form = new MemberOrderForm();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->save());
    }

    /**
     * 会员购买 支付数据
     */
    public function actionGetMemberData()
    {
        $form = new MemberOrderData();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->search());
    }
}