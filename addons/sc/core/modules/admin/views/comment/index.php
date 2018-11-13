<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 18:37
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '评论列表';
$this->params['active_nav_group'] = 4;
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

    <div class="float-left">
        <a href="javascript:void(0)" class="btn btn-danger batch"
           data-url="<?= $urlManager->createUrl(['admin/comment/batch']) ?>" data-content="评论下面的回复会一起删除，是否确认删除？"
           data-type="2">批量删除</a>
    </div>
    <div class="float-right mb-4">
        <form method="get">

            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>

            <div class="input-group">
                <input class="form-control" placeholder="评论者" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>

    <table class="table table-bordered bg-white">
        <thead>
        <tr>
            <td><input type="checkbox" class="check-all">ID</td>
            <td>评论者</td>
            <td>评论的视频</td>
            <td>内容</td>
            <td>图片</td>
            <td>点赞数</td>
            <td>时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <col style="width: 5%;">
        <col style="width: 5%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 20%;">
        <col style="width: 5%;">
        <col style="width: 7%;">
        <col style="width: 5%;">
        <tbody>
        <?php foreach ($list as $index => $value): ?>
            <tr>
                <td><input type="checkbox" class="check" value="<?=$value['id']?>"><?= $value['id'] ?></td>
                <td><?= $value['nickname'] ?></td>
                <td><?= $value['title'] ?></td>
                <td><?= $value['content'] ?></td>
                <td>
                    <div>
                        <?php foreach($value['img'] as $v):?>
                            <a href="<?=$v?>" target="_blank"><img src="<?=$v?>" style="width: 60px;height: 60px;"></a>
                        <?php endforeach;?>
                    </div>
                </td>
                <td><?=$value['thump_count']?></td>
                <td><?= date('Y-m-d H:i', $value['addtime']) ?></td>
                <td>
                    <a class="btn btn-sm btn-danger del" href="javascript:"
                       data-content="评论下面的回复会一起删除，是否确认删除？"
                       data-url="<?= $urlManager->createUrl(['admin/comment/del', 'id' => $value['id']]) ?>">删除</a>
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
<script>

    $(document).on('click', '.check-all', function () {
        var checked = $(this).prop('checked');
        $('.check').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.check', function () {
        var checked = $(this).prop('checked');
        var all = $('.check');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_use = true;
            } else {
                is_all = false;
            }
        });
        if (is_all) {
            $('.check-all').prop('checked', true);
        } else {
            $('.check-all').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.batch', function () {
        var all = $('.check');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_all = false;
            }
        });
        if (is_all) {
            $.myAlert({
                content: "请先勾选需要删除项"
            });
        }
    });
    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var check = [];
        var all = $('.check');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                check.push($(all[i]).val());
            }
        });
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        check: check,
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {

                        }
                    },
                    complete: function () {
                        $.myLoadingHide();
                    }
                });
            }
        })
    });
</script>
