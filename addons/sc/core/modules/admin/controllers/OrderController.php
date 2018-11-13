<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 14:57
 */

namespace app\modules\admin\controllers;


use app\modules\admin\models\BuyVideoForm;
use app\modules\admin\models\OrderListForm;
use app\modules\admin\models\OrderRefundForm;
use app\modules\admin\models\OrderTypeForm;

class OrderController extends Controller
{
    /**
     * @return string
     * 订单列表
     */
    public function actionIndex()
    {
        $form = new OrderListForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('index', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count']
        ]);
    }

    /**
     * 售后订单
     */
    public function actionRefund()
    {
        if (\Yii::$app->request->isPost) {
            $form = new OrderTypeForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->save());
        } else {
            $form = new OrderRefundForm();
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->get();
            $arr = $form->search();
            return $this->render('refund', [
                'list' => $arr['list'],
                'pagination' => $arr['pagination'],
                'row_count' => $arr['row_count']
            ]);
        }
    }

    /**
     * 视频购买记录
     */
    public function actionVideo()
    {
        $form = new BuyVideoForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->search();
        return $this->render('video', [
            'list' => $arr['video_list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count']
        ]);
    }
    /**
     * 会员购买记录
     */
    public function actionMember()
    {
        $form = new BuyVideoForm();
        $form->store_id = $this->store->id;
        $form->limit = 20;
        $form->attributes = \Yii::$app->request->get();
        $arr = $form->member();
        return $this->render('member', [
            'list' => $arr['video_list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count']
        ]);
    }
}