<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9
 * Time: 11:07
 */

defined('YII_RUN') or exit('Access Denied');
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '广告位';
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
<div class="main-body p-3" id="app">
    <div style="overflow-x: hidden">
        <div class="row">

                <div class="col-12 col-sm-6 col-md-3 col-xl-2 mb-4">
                    <div class="card">
                        <div class="card-img-top" data-responsive="750:590"
                             style="background: url('<?= $advertisement->pic ?>') no-repeat;background-size: cover;background-position: center">
                        </div>
                        <div class="card-body p-3">
                            <div><span>广告位标题：<?=$advertisement->title?></span></div>
                        </div>
                        <div class="card-footer text-muted">
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['admin/advertisement/edit', 'id' => $advertisement->id]) ?>">更换</a>
                        </div>
                    </div>
                </div>
            
        </div>
    </div>
</div>
