<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 11:08
 */
defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '更换广告位';
$this->params['active_nav_group'] = 1;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <span class="breadcrumb-item active"><a href="<?= $urlManager->createUrl(['admin/advertisement/edit']) ?>"><?= $this->title ?></a></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3" id="app">
    <form class="form auto-submit-form" method="post" autocomplete="off">
        <div class="form-body">
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">广告图</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'model[pic]',
                        'value' => $advertisement->pic,
                        'width' => 750,
                        'height' => 180,
                    ]) ?>
                    <div class="fs-sm">诺为空，则表示不开启广告</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">标题：</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="model[title]" value="<?=$advertisement->title ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">appid：</label>
                </div>

                <div class="col-9">
                    <input class="form-control" name="model[appid]" value="<?=$advertisement->appid ?>">
                    <div class="text-muted fs-sm">广告所要跳转小程序的APPID</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">path：</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="model[path]" value="<?=$advertisement->path ?>">
                    <div class="text-muted fs-sm">所要跳转小程序的页面路径--选填</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-9 offset-sm-3">
                    <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                    <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                    <a class="btn btn-primary submit-btn" href="javascript:">提交</a>
                </div>
            </div>
        </div>
    </form>
</div>





