<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 11:08
 */
defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '轮播图编辑';
$this->params['active_nav_group'] = 2;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/video/banner']) ?>">轮播图列表</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3" id="app">
    <form class="form auto-submit-form" method="post" autocomplete="off"
          data-return="<?= $urlManager->createUrl(['admin/video/banner']) ?>">
        <div class="form-title"><?= $this->title ?></div>
        <div class="form-body">

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">轮播图</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'model[banner_url]',
                        'value' => $list['banner_url'],
                        'width' => 650,
                        'height' => 410,
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">排序</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="number" name="model[sort]" value="<?= $list['sort']?$list['sort']:100 ?>">
                    <div class="text-danger">按照升序排列</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">链接</label>
                </div>
                <div class="col-9">
                    <div class="input-group page-link-input">
                        <input class="form-control link-input"
                               name="model[url]"
                               value="<?= $list['url'] ?>">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary pick-link-btn" href="javascript:" open-type="navigate">选择链接</a>
                        </span>
                    </div>
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



