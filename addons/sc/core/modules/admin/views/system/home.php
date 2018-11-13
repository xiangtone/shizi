<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 16:09
 */
use \app\models\Option;
defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '自定义设置';
$this->params['active_nav_group'] = 1;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>
<div class="main-body p-3">
    <form class="form auto-submit-form" method="post" autocomplete="off">
        <div class="form-title">自定义设置</div>
        <div class="form-body">

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">是否开启首页分类</label>
                </div>
                <div class="col-9">
                    <div class="pt-1">
                        <label class="custom-control custom-radio">
                            <input id="radio1" <?= $list['cat_show'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="cat_show" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">开启</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radio2" <?= $list['cat_show'] == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="cat_show" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">关闭</span>
                        </label>
                    </div>
                </div>
            </div>



            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">是否开启广告位</label>
                </div>
                <div class="col-9">
                    <div class="pt-1">
                        <label class="custom-control custom-radio">

                            <input id="radio3" <?= $list['advertisement_show'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="advertisement_show" type="radio" class="custom-control-input">

                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">开启</span>
                        </label>
                        <label class="custom-control custom-radio">

                            <input id="radio4" <?= $list['advertisement_show'] == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="advertisement_show" type="radio" class="custom-control-input">

                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">关闭</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">首页分类小图标</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'cat_pic',
                        'value' => $list['cat_pic'],
                        'width' => 40,
                        'height' => 40,
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">首页分类文字</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="cat_text"
                           value="<?= $list['cat_text'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">首页精选小图标</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'video_pic',
                        'value' => $list['video_pic'],
                        'width' => 40,
                        'height' => 40,
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">首页精选文字</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="video_text"
                           value="<?= $list['video_text'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">返回首页图标</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'index_icon',
                        'value' => $list['index_icon'],
                        'width' => 100,
                        'height' => 100,
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">返回顶部图标</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'top_icon',
                        'value' => $list['top_icon'],
                        'width' => 100,
                        'height' => 100,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-9 offset-sm-3">
                    <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                    <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                    <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).on('click','.style-1 .custom-control-input',function(){
        if($(this).val() == 1){
            $('.pic_icon').prop('hidden',false);
        }else{
            $('.pic_icon').prop('hidden',true);
        }
    });
</script>
