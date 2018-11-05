<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21
 * Time: 9:11
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '视频购买记录';
$this->params['active_nav_group'] = 5;
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
    <div class="mb-4" flex="dir:left main:right">
        <form method="get">
            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>

            <div class="input-group">
                <input class="form-control" placeholder="微信昵称\订单号" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>
    <div style="overflow: hidden">
        <div class="row">
            <?php foreach ($list as $index => $value): ?>
                <div class="col-12 col-sm-6 col-md-3 col-xl-2 mb-4">
                    <div class="card">
                        <a href="<?= $value['video_url'] ?>" target="_blank">
                            <div class="card-img-top" data-responsive="750:420"
                                 style="background: url(<?= $value['pic_url'] ?>) no-repeat;background-size: cover;background-position: center">
                            </div>
                        </a>
                        <div class="card-body p-3">
                            <div>
                                <span
                                    style="font-size: 16pt;font-weight: bold;word-break: break-all;word-wrap:break-word"><?= $value['title'] ?></span>
                            </div>
                            <div>
                                <table style="width:100%;">
                                    <tr>
                                        <td>微信昵称：<?= $value['nickname'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>购买时间：<?= date('Y-m-d H:i', $value['addtime']) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            <div>
                                <span style="word-break: break-all;word-wrap:break-word">订单号：<?= $value['order_no'] ?></span>
                            </div>
                            <div>
                                <span>价格：<?= $value['price'] ?>元</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination,]) ?>
            <div class="text-muted"><?= $row_count ?>条数据</div>
        </div>
    </div>
</div>