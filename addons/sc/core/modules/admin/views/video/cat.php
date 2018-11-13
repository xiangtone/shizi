<?php
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '视频分类';
$this->params['active_nav_group'] = 2;
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
<div class="main-body p-3" id="app">
    <a href="<?= $urlManager->createAbsoluteUrl(['admin/video/cat-edit']) ?>" class="btn btn-primary mb-3">
        <i class="iconfont icon-playlistadd"></i>添加分类</a>
    <div style="overflow-x: hidden">
        <div class="row">
            <?php foreach ($list as $index => $value): ?>
                <div class="col-12 col-sm-6 col-md-3 col-xl-2">
                    <div class="card">
                        <div class="card-img-top" data-responsive="300:300"
                             style="background: url(<?= $value['pic_url'] ?>) no-repeat;background-size: cover;background-position: center">
                        </div>
                        <div class="card-body p-3">
                            <div>
                                <span
                                    style="font-size: 16pt;font-weight: bold;word-break: break-all"><?= $value['name'] ?></span>
                                <span class="float-right">ID：<?= $value['id'] ?></span>
                            </div>
                            <div><span>排序：<?= $value['sort'] ?></span></div>
                        </div>
                        <div class="card-footer text-muted">
                            <a class="btn btn-sm btn-primary mb-2"
                               href="<?= $urlManager->createUrl(['admin/video/list', 'cat_id' => $value['id']]) ?>">查看视频</a>
                            <a class="btn btn-sm btn-primary mb-2"
                               href="<?= $urlManager->createUrl(['admin/video/cat-edit', 'id' => $value['id']]) ?>">修改</a>
                            <a class="btn btn-sm btn-danger del cat-del mb-2" href="javascript:" data-content="是否删除？"
                               data-url="<?= $urlManager->createUrl(['admin/video/cat-del', 'id' => $value['id']]) ?>">删除</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>