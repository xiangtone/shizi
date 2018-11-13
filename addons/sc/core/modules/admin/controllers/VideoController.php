<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 20:47
 */

namespace app\modules\admin\controllers;


use app\extensions\getInfo;
use app\models\Banner;
use app\models\Cat;
use app\models\Form;
use app\models\Video;
use app\models\VideoPay;
use app\modules\admin\models\BannerForm;
use app\modules\admin\models\BannerListForm;
use app\modules\admin\models\CatForm;
use app\modules\admin\models\VideoForm;
use app\modules\admin\models\VideoListForm;

class VideoController extends Controller
{
    /**
     * @return string
     * 分类列表
     */
    public function actionCat()
    {
        $list = Cat::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])
            ->orderBy(['sort' => SORT_ASC])->asArray()->all();
        return $this->render('cat', [
            'list' => $list
        ]);
    }

    /**
     * @param null $id
     * @return string
     * 分类编辑
     */
    public function actionCatEdit($id = null)
    {
        $cat = Cat::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cat)
            $cat = new Cat();
        if (\Yii::$app->request->isPost) {
            $form = new CatForm();
            $form->cat = $cat;
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post('model');
            $this->renderJson($form->save());
        }
        return $this->render('cat-edit', [
            'list' => $cat
        ]);
    }

    public function actionCatDel($id = null)
    {
        $cat = Cat::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cat) {
            $this->renderJson([
                'code' => 1,
                'msg' => '分类已删除'
            ]);
        }
        $cat->is_delete = 1;
        if ($cat->save()) {
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

    public function actionList()
    {
        $form = new VideoListForm();
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

        $cat = Cat::find()->where(['is_delete' => 0, 'store_id' => $this->store->id])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        $list = Video::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        $form_list = Form::find()->where(['store_id'=>$this->store->id,'video_id'=>$id,'is_delete'=>0])->orderBy(['sort'=>SORT_ASC])->asArray()->all();
        $form_name = null;
        foreach($form_list as $index=>$value){
            if($value['type'] == 'form_name'){
                $form_name = $value['name'];
            }
        }
        $pay_video = VideoPay::findOne(['video_id'=>$list->id]);
        if (!$list){
            $list = new Video();
        }
        if(!$pay_video){
            $pay_video = new VideoPay();
        }
        $pay = [];
        foreach($pay_video as $index=>$value){
            $pay['pay_'.$index] = $value;
        }
        if (\Yii::$app->request->isPost) {
            $form = new VideoForm();
            $model = \Yii::$app->request->post('model');
            $model_pay = \Yii::$app->request->post('pay');
            $model = array_merge($model,$model_pay);
            if($model['style'] == 2){
//                $form->scenario = 'ARTICLE';
            }else{
                $form->scenario = 'VIDEO';
            }
            $form->attributes = $model;
            $form->store_id = $this->store->id;
            $form->video = $list;
            $form->pay = $pay_video;
            $this->renderJson($form->save());
        }
        return $this->render('edit', [
            'list' => $list,
            'cat_list' => $cat,
            'form_list'=>json_encode($form_list,JSON_UNESCAPED_UNICODE),
            'pay'=>$pay,
            'form_name'=>$form_name
        ]);
    }

    public function actionDel($id = null)
    {
        $video = Video::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$video)
            $this->renderJson([
                'code' => 1,
                'msg' => '视频已删除'
            ]);
        $video->is_delete = 1;
        if ($video->save()) {
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

    /**
     * 视频下架
     */
    public function actionStatus($id = null, $status = 0)
    {
        $video = Video::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$video) {
            $this->renderJson([
                'code' => 1,
                'msg' => '网络异常'
            ]);
        }
        $video->status = $status;
        if ($video->save()) {
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

    public function actionBatch()
    {
        $get = \Yii::$app->request->get();

        $video_id = $get['video_id'];
        $condition = ['and', ['in', 'id', $video_id], ['store_id' => $this->store->id]];

        if ($get['type'] == 1) { //批量下架
            $res = Video::updateAll(['status' => 1], $condition);
        } elseif ($get['type'] == 0) {//批量上架
            $res = Video::updateAll(['status' => 0], $condition);
        } elseif ($get['type'] == 2) {//批量删除
            $res = Video::updateAll(['is_delete' => 1], $condition);
        }elseif ($get['type'] == 3) {//全部上架
            $res = Video::updateAll(['status' => 1], ['store_id' => $this->store->id]);
        }elseif ($get['type'] == 4) {//全部下架
            $res = Video::updateAll(['status' => 0], ['store_id' => $this->store->id]);
        }elseif ($get['type'] == 5) {//全部展示到首页
            $res = Video::updateAll(['is_index' => 0], $condition);
        }elseif ($get['type'] == 6) {//全部从首页撤下
            $res = Video::updateAll(['is_index' => 1], $condition);
        }
        if ($res > 0) {
            $this->renderJson([
                'code' => 0,
                'msg' => 'success'
            ]);
        } else {
            $this->renderJson([
                'code' => 1,
                'msg' => 'fail'
            ]);
        }
    }

    /**
     * 是否展示到首页
     */
    public function actionIsIndex($id = null, $is_index = 0)
    {
        $video = Video::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$video) {
            $this->renderJson([
                'code' => 1,
                'msg' => '网络异常'
            ]);
        }
        $video->is_index = $is_index;
        if ($video->save()) {
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

    /**
     * 轮播图
     */
    public function actionBanner()
    {
        $form = new BannerListForm();
        $form->store_id = $this->store->id;
        $arr = $form->search();
        return $this->render('banner',[
            'list'=>$arr['list'],
            'row_count'=>$arr['row_count']
        ]);
    }

    /**
     * 轮播图编辑
     */
    public function actionBannerEdit($id = null)
    {
        $banner = Banner::findOne(['id'=>$id,'store_id'=>$this->store->id]);
        if(!$banner){
            $banner = new Banner();
        }
        if(\Yii::$app->request->isPost){
            $form = new BannerForm();
            $form->store_id = $this->store->id;
            $form->banner = $banner;
            $form->attributes = \Yii::$app->request->post('model');
            $this->renderJson($form->save());
        }
        return $this->render('banner-edit',[
            'list'=>$banner
        ]);
    }

    /**
     * 轮播图删除
     */
    public function actionBannerDel($id=null)
    {
        $banner = Banner::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$banner) {
            $this->renderJson([
                'code' => 1,
                'msg' => '网络异常'
            ]);
        }
        $banner->is_delete = 1;
        if ($banner->save()) {
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
    /**
     * 获取腾讯视频播放地址
     */
    public function actionGetVideo()
    {
        $url = \Yii::$app->request->get('url');
        $this->renderJson(getInfo::getVideoInfo($url));
    }
}