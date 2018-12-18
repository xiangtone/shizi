<?php

/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/12/16
 * Time: 14:36
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '组词列表';
$this->params['active_nav_group'] = 2;
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
    <a href="<?= $urlManager->createUrl(['admin/game/edit', 'video_id' => $video_id]) ?>" class="btn btn-primary">
        添加</a>
    <div class="float-right">

        <form method="get">
            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv) : if (in_array($_gi, $_s)) {
                continue;
            }
            ?>
			                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
			            <?php endforeach; ?>
            <div class="input-group">
                <input class="form-control" placeholder="生字" name="keyword" autocomplete="off"
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
                <th>视频id</th>
                <th>生字</th>
                <th>词汇</th>
                <th>音频地址</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= $value['video_id'] ?></td>
                    
                    <td><?= $value['new_word'] ?></td>
                    <td><?= $value['target_word'] ?></td>
                    <td><?= $value['voice_url'] ?></td>

                    <td>

                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['admin/game/edit', 'id' => $value['id'],'video_id' => $video_id]) ?>">修改</a>
                        
                        <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                           data-url="<?= $urlManager->createUrl(['admin/game/del', 'id' => $value['id']]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
        <div class="text-center">
            <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>
            <div class="text-muted"><?= $row_count ?>条数据</div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function search() {
        var keyword = $("#title_search").val();
        $.ajax({
            url: "<?= $urlManager->createUrl(['admin/game/word-list']) ?>",
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