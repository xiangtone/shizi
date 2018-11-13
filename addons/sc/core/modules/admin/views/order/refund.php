<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 16:01
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '售后订单';
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
        width: 30%;
    }

    .order-tab-4 {
        width: 20%;
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
        <a class="mr-3 status-item <?= $status == -1 ? 'active' : null ?>"
           href="<?= $urlManager->createUrl(['admin/order/refund']) ?>">全部</a>
        <a class="mr-3 status-item <?= $status == 1 ? 'active' : null ?>"
           href="<?= $urlManager->createUrl(['admin/order/refund', 'status' => 1]) ?>">待处理</a>
        <a class="mr-3 status-item <?= $status == 2 ? 'active' : null ?>"
           href="<?= $urlManager->createUrl(['admin/order/refund', 'status' => 2]) ?>">已处理</a>
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
            <td class="order-tab-3">退款信息</td>
            <td class="order-tab-4">订单状态</td>
            <td class="order-tab-5">操作</td>
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
                        <span class="mr-4">退单编号：<?= $value['order_refund_no'] ?></span>
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
                                <div class="fs-sm">小计：<span class="text-danger"><?= $value['refund_price'] ?>元</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="order-tab-3">
                        <?php if ($value['refund_price'] == 0): ?>
                            <div>售后类型：取消预约</div>
                        <?php else: ?>
                            <div>售后类型：退款</div>
                            <div>退款金额：<?= $value['refund_price'] ?>元</div>
                            <div>申请理由：<?= $value['desc'] ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="order-tab-4">
                        <?php if ($value['status'] == 0): ?>
                            <span class="badge badge-success">待处理</span>
                        <?php elseif ($value['status'] == 1): ?>
                            <?php if ($value['refund_price'] == 0): ?>
                                <span class="badge badge-default">已取消</span>
                            <?php else: ?>
                                <span class="badge badge-default">已退款</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge badge-danger">已拒绝退款</span>
                        <?php endif; ?>
                    </td>
                    <td class="order-tab-5">
                        <?php if ($value['status'] == 0): ?>
                            <a href="javascript:" class="btn btn-sm btn-success agree-btn"
                               data-id="<?= $value['id'] ?>" data-price="<?= $value['refund_price'] ?>">同意</a>
                            <a href="javascript:" class="btn btn-sm btn-danger disagree-btn"
                               data-id="<?= $value['id'] ?>">拒绝</a>
                        <?php endif; ?>
                    </td>
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
<script>
    //同意退款
    $(document).on('click', '.agree-btn', function () {
        var id = $(this).attr("data-id");
        var price = $(this).attr("data-price");
        $.myConfirm({
            content: "确认同意退款？<br>确认通过后退款金额<b class=text-danger>" + price + "元</b>将直接返还给用户！",
            confirm: function () {
                $.myLoading({
                    title: "正在提交"
                });
                $.ajax({
                    type: "post",
                    data: {
                        _csrf: _csrf,
                        order_refund_id: id,
                        type: 1,
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res.code == 0) {
                            $.myAlert({
                                content: res.msg,
                                confirm: function () {
                                    location.reload();
                                }
                            });
                        }
                        if (res.code == 1) {
                            $.myAlert({
                                content: res.msg,
                                confirm: function () {
                                    $.myLoadingHide();
                                }
                            });
                        }
                    }
                });

            }
        });
    });
    //拒绝退款
    $(document).on('click', '.disagree-btn', function () {
        var id = $(this).attr("data-id");
        $.myConfirm({
            content: "确认拒绝退款申请？",
            confirm: function () {
                $.myLoading({
                    title: "正在提交"
                });
                $.ajax({
                    type: "post",
                    data: {
                        _csrf: _csrf,
                        order_refund_id: id,
                        type: 2,
                    },
                    dataType: "json",
                    success: function (res) {
                        if (res.code == 0) {
                            $.myAlert({
                                content: res.msg,
                                confirm: function () {
                                    location.reload();
                                }
                            });
                        }
                        if (res.code == 1) {
                            $.myAlert({
                                content: res.msg,
                                confirm: function () {
                                    $.myLoadingHide();
                                }
                            });
                        }
                    }
                });

            }
        });
    });
</script>
