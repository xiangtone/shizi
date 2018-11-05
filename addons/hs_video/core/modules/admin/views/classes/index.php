<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/19
 * Time: 14:36
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '班级列表';
$this->params['active_nav_group'] = 8;
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
    <a href="<?= $urlManager->createUrl(['admin/classes/edit']) ?>" class="btn btn-primary">
        添加</a>
    <div class="float-right">

        <form method="get">
            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>
            <div class="input-group">
                <input class="form-control" placeholder="班级名称" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>" id="title_search">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>
    <div class="mt-4">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>班级名称</th>
                <th>类型</th>
                <th>创建者id</th>
                <th>创建者名字</th>
                <th>创建时间</th>

                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= $value['class_name'] ?></td>
                    <td>

                        <?php if ($value['type'] == 1): ?>
                            <span>老师创建</span>
                        <?php else: ?>
                            <span>系统创建</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $value['create_user_id'] ?>
                    </td>
                    <td>
                        <?= $value['username'] ?>
                    </td>
                    <td><?= date('Y-m-d H:i:s', $value['create_time']) ?></td>

                    <td>

                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['admin/classes/edit', 'id' => $value['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                           data-url="<?= $urlManager->createUrl(['admin/classes/del', 'id' => $value['id']]) ?>">删除</a>
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

<script type="text/javascript">

    function search() {
        var keyword = $("#title_search").val();
        $.ajax({
            url: "<?=$urlManager->createUrl(['admin/classes/search-classes'])?>",
            dataType: "json",
            data: {
                keyword: keyword,
            },
            success: function (res) {
                if (res.code == 0) {
                    console.log(res.data.list)
                }
            }
        });
    }
</script>