<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 14:08
 */
defined('YII_RUN') or exit('Access Denied');
use \app\models\Level;

/* @var \app\models\Level $level */
$urlManager = Yii::$app->urlManager;
$this->title = '会员设置';
$this->params['active_nav_group'] = 6;
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
    <a href="<?=$urlManager->createUrl(['admin/member/edit'])?>" class="btn btn-primary">添加会员设置</a>
    <div class="mt-4">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>会员描述</th>
                <th>会员价格</th>
                <th>续费价格</th>
                <th>会员时间</th>
                <th>状态</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width:10%;">
            <col style="width:20%;">
            <col style="width:10%;">
            <col style="width:10%;">
            <col style="width:10%;">
            <col style="width:10%;">
            <col style="width:10%;">
            <col style="width:20%;">
            <tbody>
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?=$value['id']?></td>
                    <td class="ellipsis"><?=$value['title']?></td>
                    <td><?=$value['price']?></td>
                    <td><?=$value['s_price']?></td>
                    <td><?=$value['date']?>天</td>
                    <td>
                        <?php if ($value['is_groom'] == 1): ?>
                            <span class="badge badge-success">推荐</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $value['id'] ?>,'down');">不推荐</a>
                        <?php else: ?>
                            <span class="badge badge-default">不推荐</span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $value['id'] ?>,'up');">推荐</a>
                        <?php endif ?>
                    </td>
                    <td><?=$value['sort']?></td>
                    <td class="nowrap">
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['admin/member/edit', 'id' => $value['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                           data-url="<?= $urlManager->createUrl(['admin/member/del', 'id' => $value['id']]) ?>">删除</a>
                    </td>
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