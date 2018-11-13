<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 15:23
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '订单列表';
$this->params['active_nav_group'] = 5;
$status = Yii::$app->request->get('status');
if ($status === '' || $status === null || $status == -1)
    $status = -1;
?>
<style>
    .order-tab-1 {
        width: 40%;
    }

    .order-tab-2 {
        width: 30%;
    }

    .order-tab-3 {
        width: 10%;
        text-align: center;
    }

    .order-tab-4 {
        width: 10%;
        text-align: center;
    }

    .order-tab-5 {
        width: 10%;
        text-align: center;
    }

    .goods-pic {
        width: 15rem;
        height: 8.4rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 1rem;
    }

    .goods-name {
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        word-break: break-all;
    }

    .order-item {
        border: 1px solid transparent;
        margin-bottom: 1rem;
    }

    .order-item table {
        margin: 0;
    }

    .order-item:hover {
        border: 1px solid #3c8ee5;
    }

    .status-item.active {
        color: inherit;
    }
</style>
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
    <div class="float-left">
        <a class="mr-3 status-item <?= $status == -1 ? 'active' : null ?>" href="<?= $urlManager->createUrl(['admin/order/index']) ?>">全部</a>
        <a class="mr-3 status-item <?= $status == 1 ? 'active' : null ?>" href="<?= $urlManager->createUrl(['admin/order/index', 'status' => 1]) ?>">未使用</a>
        <a class="mr-3 status-item <?= $status == 2 ? 'active' : null ?>" href="<?= $urlManager->createUrl(['admin/order/index', 'status' => 2]) ?>">已使用</a>
        <!--<a class="mr-3 <?= $status == 3 ? 'active' : null ?>" href="<?= $urlManager->createUrl(['admin/order/index', 'status' => 3]) ?>">退款</a>-->
    </div>
    <div class="float-right mb-4">
        <form method="get">

            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>

            <div class="input-group">
                <input class="form-control" placeholder="用户/订单号" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>

    <table class="table table-bordered bg-white">
        <tbody>
        <tr>
            <td class="order-tab-1">商品信息</td>
            <td class="order-tab-2">表单信息</td>
            <td class="order-tab-3">预约金额（元）</td>
            <td class="order-tab-4">订单状态</td>
<!--            <td class="order-tab-5">操作</td>-->
        </tr>
        </tbody>
    </table>
    <?php foreach ($list as $index => $value): ?>
        <div class="order-item">
            <table class="table table-bordered  bg-white">
                <tbody>
                <tr>
                    <td colspan="5">
                        <span class="mr-4"><?= date('Y-m-d H:i:s', $value['addtime']) ?></span>
                        <span class="mr-4">订单编号：<?= $value['order_no'] ?></span>
                        <span class="mr-4">用户：<?= $value['nickname'] ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="order-tab-1">
                        <div flex="dir:left box:first">
                            <div>
                                <div class="goods-pic" style="background-image: url('<?= $value['pic_url'] ?>')"></div>
                            </div>
                            <div flex="dir:top main:justify">
                                <div class="goods-name"><?= $value['title'] ?></div>
                                <div class="fs-sm">小计：<span class="text-danger"><?= $value['price'] ?>元</span></div>
                            </div>
                        </div>
                    </td>
                    <td class="order-tab-2">
                        <?php foreach ($value['user_form'] as $i => $v): ?>
                            <div flex="dir:left box:first">
                                <div><?= $v['key'] ?>：</div>
                                <div class="goods-name"><?= $v['value'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    <td class="order-tab-3"><?= $value['price'] ?></td>
                    <td class="order-tab-4">
                        <?php if ($value['is_use'] == 0): ?>
                            <span class="badge badge-success">未使用</span>
                        <?php elseif ($value['is_use'] == 1): ?>
                            <span class="badge badge-default">已使用</span>
                        <?php else: ?>
                            <span class="badge badge-danger">退款</span>
                        <?php endif; ?>
                    </td>
<!--                    <td class="order-tab-5"></td>-->
                </tr>
                </tbody>
            </table>

        </div>
    <?php endforeach; ?>

    <div class="text-center">
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination,]) ?>
        <div class="text-muted"><?= $row_count ?>条数据</div>
    </div>
</div>