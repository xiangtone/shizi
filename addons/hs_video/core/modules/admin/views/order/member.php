<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/23
 * Time: 10:07
 */
defined('YII_RUN') or exit('Access Denied');
use \app\models\Level;
use yii\data\Pagination;

/* @var \app\models\Level $level */
/* @var \yii\data\Pagination $pagination */
$urlManager = Yii::$app->urlManager;
$this->title = '会员购买记录';
$this->params['active_nav_group'] = 5;
?>
<style>
    table {
        table-layout: fixed;
    }

    th {
        text-align: center;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td {
        text-align: center;
    }

    .ellipsis {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td.nowrap {
        white-space: nowrap;
        overflow: hidden;
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
<div class="main-body p-3">
    <div class="float-right mb-4">
        <form method="get">

            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>

            <div class="input-group">
                <input class="form-control" placeholder="会员昵称/订单号" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>
    <div class="mt-4">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>序号</th>
                <th>订单号</th>
                <th>会员昵称</th>
                <th>会员描述</th>
                <th>购买价格</th>
                <th>会员时间</th>
                <th>购买时间</th>
            </tr>
            </thead>
            <col style="width:5%;">
            <col style="width:20%;">
            <col style="width:10%;">
            <col style="width:20%;">
            <col style="width:10%;">
            <col style="width:10%;">
            <col style="width:15%;">
            <tbody>
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?= $index + 1 + $pagination->page * $pagination->limit ?></td>
                    <td class="ellipsis"><?= $value['order_no'] ?></td>
                    <td class="ellipsis"><?= $value['nickname'] ?></td>
                    <td class="ellipsis"><?= $value['title'] ?></td>
                    <td><?= $value['price'] ?></td>
                    <td><?= $value['date'] ?>天</td>
                    <td><?= date('Y-m-d H:i', $value['addtime']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
        <div class="text-center">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination,]) ?>
            <div class="text-muted"><?= $row_count ?>条数据</div>
        </div>
    </div>
</div>
<script>
    function upDown(id, type) {
        var text = '';
        if (type == 'up') {
            text = "推荐";
        } else {
            text = '不推荐';
        }

        var url = "<?= $urlManager->createUrl(['admin/member/up-down']) ?>";
        if (confirm("是否" + text + "？")) {
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                data: {id: id, type: type},
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    }
                    if (res.code == 1) {
                        alert(res.msg);
                        if (res.return_url) {
                            location.href = res.return_url;
                        }
                    }
                }
            });
        }
        return false;
    }
</script>
