<?php

namespace app\modules\api\controllers;

use app\models\Cat;
use app\models\Option;
use app\models\UploadConfig;
use app\models\UploadForm;
use app\models\Video;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\CatForm;
use app\modules\api\models\CatVideoForm;
use app\modules\api\models\CommentListForm;
use app\modules\api\models\HotSearchForm;
use app\modules\api\models\ListForm;
use app\modules\api\models\SearchForm;
use app\modules\api\models\ShareForm;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * 首页列表
     */
    public function actionIndex()
    {
        $form = new ListForm();
        $form->store_id = $this->store->id;
        $this->renderJson($form->search());
    }

    /**
     * 系统配置
     */
    public function actionStore()
    {
        $this->renderJson([
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'store_name' => $this->store->name,
                'contact_tel' => $this->store->contact_tel,
                'store' => (object)[
                    'id' => $this->store->id,
                    'name' => $this->store->name,
                    'copyright' => $this->store->copyright,
                    'copyright_pic_url' => $this->store->copyright_pic_url,
                    'copyright_url' => $this->store->copyright_url,
                    'contact_tel' => $this->store->contact_tel,
                    'show_customer_service' => $this->store->show_customer_service,
                    'customer_service_pic' => $this->store->customer_service_pic,
                    'pic_style' => $this->store->pic_style,
                    'video_icon' => $this->store->video_icon,
                    'audio_icon' => $this->store->audio_icon,
                    'refund' => $this->store->refund,
                    'member' => $this->store->member,
                    'top_icon' => Option::get('top_icon', $this->store->id, 'home', ''),
                    'index_icon' => Option::get('index_icon', $this->store->id, 'home', ''),
                ],
            ]
        ]);
    }

    /**
     * 分类列表
     */
    public function actionCat()
    {
        $form = new CatForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    /**
     * 某个分类下的视频列表
     */
    public function actionCatVideo()
    {
        $form = new CatVideoForm();
        $form->store_id = $this->store->id;
        $form->limit = 10;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    /**
     * 热门搜索&&视频标题列表
     */
    public function actionHotSearch()
    {
        $form = new HotSearchForm();
        $form->store_id = $this->store->id;
        $this->renderJson($form->search());
    }

    /**
     * 搜索视频
     */
    public function actionSearch()
    {
        $form = new SearchForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        $this->renderJson($form->search());
    }

    /**
     * @param null $name
     * @return null
     * 上传图片
     */
    public function actionUploadImg($name = null)
    {
        $form = new UploadForm();
        $upload_config = UploadConfig::find()->where(['store_id' => 0, 'is_delete' => 0])->one();
        $form->upload_config = $upload_config;
        $form->store = $this->store;
        return $this->renderJson($form->saveImage($name));
    }

    /**
     * 分享海报
     */
    public function actionShare()
    {
        $form = new ShareForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->post();
        $this->renderJson($form->search());
    }
}
