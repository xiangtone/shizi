<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/19
 * Time: 13:54
 */

namespace app\modules\admin\controllers;

use app\models\Coupon;
use app\modules\admin\models\CouponForm;
use app\models\Video;
use app\modules\admin\models\UserCouponForm;
use app\modules\admin\models\VideoCouponForm;
use app\models\VideoCoupon;
use app\models\UserCoupon;

class CouponController extends Controller
{
    public $keyword;
    public $coupon_name;

    public function actionList()
    {
        $form = new CouponForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $form->limit = 10;
        $arr = $form->getList();
        return $this->render('list', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count']
        ]);
    }

    public function actionEdit($id = null)
    {
        $coupon = Coupon::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$coupon) {
            $coupon = new Coupon();
        }
        if(\Yii::$app->request->isPost){
            $form = new CouponForm();
            $form->coupon = $coupon;
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post();

            if($form->draw_type == 1){
                $form->cost_price = 0;
                $form->coupon_price = 0;
            }else{
                $form->original_cost = 0;
                $form->sub_price = 0;
            }
            $this->renderJson($form->save());
        }else{
            return $this->render('edit',[
                'coupon'=>$coupon,
            ]);
        }
    }

//    删除优惠券
    public function actionDel($id)
    {
        $videocoupon = Coupon::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$videocoupon) {
            $this->renderJson([
                'code' => 1,
                'msg' => '优惠券删除'
            ]);
        }
        $videocoupon->is_delete = 1;
        if ($videocoupon->save()) {
            $this->renderJson([
                'code' => 0,
                'msg' => '成功'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '网络异常'
            ]);
        }
    }
//    发放优惠券
    public function actionProvide($id)
    {
        $coupon = Coupon::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$coupon) {
            \Yii::$app->response->redirect(\Yii::$app->request->referrer)->send();
            return;
        }
        if (\Yii::$app->request->isPost) {
            $form = new VideoCouponForm();
            $form->attributes = \Yii::$app->request->post();
            $form->store_id = $this->store->id;
            $form->coupon_id = $coupon->id;
            if($form->draw_type == 1){
                $form->cost_price = 0;
                $form->coupon_price = 0;
            }else{
                $form->original_cost = 0;
                $form->sub_price = 0;
            }
            if($form->expire_type == 1){
                $form->begin_time = 0;
                $form->end_time = 0;
            }else{
                $form->expire_day = 0;
            }
            $this->renderJson($form->save());
        }
        return $this->render('coupon-provide',['coupon'=>$coupon]);
    }

//    查找视频
    public function actionSearchVideo($keyword){
        $keyword = trim($keyword);
        $query = Video::find()->alias('v')->where([
            'AND',
            ['LIKE', 'v.title', $keyword],
            ['store_id' => $this->store->id],
            ['is_delete'=>0],
        ]);
        $list = $query->orderBy('v.title')->limit(30)->asArray()->select('id,title,pic_url')->all();

        $this->renderJson([
            'code' => 0,
            'msg' => 'success',
            'data' => (object)[
                'list' => $list
            ],
        ]);
    }

//    发放劵管理
    public function actionProvideCoupon()
    {
        $form = new VideoCouponForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $form->limit = 10;
        $arr = $form->getList();
        return $this->render('videoCouponList', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count']
        ]);
    }
//    发放劵删除
    public function actionProvideCouponDel($id)
    {
        $videocoupon = VideoCoupon::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$videocoupon) {
            $this->renderJson([
                'code' => 1,
                'msg' => '优惠券删除'
            ]);
        }
        $videocoupon->is_delete = 1;
        if ($videocoupon->save()) {
            $this->renderJson([
                'code' => 0,
                'msg' => '成功'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => '网络异常'
            ]);
        }
    }
//    发放劵修改
    public function actionProvideCouponEdit($id)
    {
        $videocoupon = VideoCoupon::findOne([
            'id' => $id,
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ]);
        if (!$videocoupon) {
            $this->renderJson([
                'code' => 1,
                'msg' => '优惠券已删除'
            ]);
        }
        if(\Yii::$app->request->isPost){
            $form = new VideoCouponForm();
            $form->attributes = \Yii::$app->request->post();
            $this->renderJson($form->edit($videocoupon->id));
        }
        return $this->render('provide-coupon-edit', [
            'list' => $videocoupon
        ]);
    }

//    用户优惠券
    public function actionUserCoupon()
    {
        $form = new UserCouponForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $form->limit = 10;

        $arr = $form->getList();
        return $this->render('userCoupon', [
            'list' => $arr['list'],
            'pagination' => $arr['pagination'],
            'row_count' => $arr['row_count']
        ]);
    }


}