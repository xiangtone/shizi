<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 13:29
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '核销员列表';
$this->params['active_nav_group'] = 3;
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
        <a class="btn btn-primary" href="javascript:" data-toggle="modal" data-target="#edit" data-backdrop="static">添加核销员</a>
    </div>
    <div class="float-right mb-4">
        <form method="get">

            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>

            <div class="input-group">
                <input class="form-control" placeholder="微信昵称" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>

    <table class="table table-bordered bg-white">
        <thead>
        <tr>
            <td>ID</td>
            <td>头像</td>
            <td>昵称</td>
            <td>身份</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $index => $value): ?>
            <tr>
                <td><?= $value['id'] ?></td>
                <td><img src="<?= $value['avatar_url'] ?>" style="width: 30px;height: 30px;"></td>
                <td><?= $value['nickname'] ?></td>
                <td>核销员</td>
                <td>
                    <a class="btn btn-sm btn-danger del" href="javascript:"
                       data-url="<?= $urlManager->createUrl(['admin/user/clerk-edit', 'id' => $value['id'], 'status' => 0]) ?>"
                       data-content="是否解除核销员">解除核销员</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center">
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination,]) ?>
        <div class="text-muted"><?= $row_count ?>条数据</div>
    </div>
    <div id="app">
        <!-- 设置核销员 -->
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">设置核销员</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control keyword" placeholder="输入微信昵称查找">
                                <input class="form-control order-id" type="hidden">
                            <span class="input-group-btn">
                                    <button v-on:click="showKeyword()" class="btn btn-info">
                                        查找
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div style="max-height:400px;overflow: auto">
                            <table class="table table-bordered">
                                <tr v-for="(item,index) in show_user_list">
                                    <td>{{item.id}}</td>
                                    <td>{{item.nickname}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm send" href="javascript:"
                                           data-url="<?= $urlManager->createUrl(['admin/user/clerk-edit', 'status' => 1]) ?>"
                                           :data-index="item.id">设为核销员</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            user_list:<?=$user_list?>,
            show_user_list:<?=$user_list?>,
        },
        methods: {
            //关键字查询
            showKeyword: function () {
                var _self = this;
                var keyword = $.trim($('.keyword').val());
                if (keyword == "") {
                    _self.show_user_list = _self.user_list;
                    return;
                }
                _self.show_user_list = [];
                $.ajax({
                    url: '<?=$urlManager->createUrl(['admin/user/get-user'])?>',
                    dataType: 'json',
                    type: 'get',
                    data: {
                        keyword_1: keyword
                    },
                    success: function (res) {
                        _self.show_user_list = res;
                    }
                });
            }
        }
    });
</script>
<script>
    $(document).on('click', '.send', function () {
        var a = $(this);
        var index = $(this).data('index');
        $.ajax({
            url: a.data('url'),
            type: 'get',
            dataType: 'json',
            data: {
                id: index,
            },
            success: function (res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    $.myAlert({
                        title: res.msg
                    });
                }
            }
        });
        return false;
    });
</script>