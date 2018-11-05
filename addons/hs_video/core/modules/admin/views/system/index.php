<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25
 * Time: 20:22
 * @var \yii\web\View $this
 */
defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '首页';
$this->params['active_nav_group'] = 1;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a flex="cross:center" class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system']) ?>">系统</a>
            <span flex="cross:center" class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

