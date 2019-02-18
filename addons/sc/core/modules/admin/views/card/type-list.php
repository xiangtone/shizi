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
$this->title = '学生卡类型列表';
$this->params['active_nav_group'] = 7;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?=$urlManager->createUrl(['admin/system/index'])?>">系统</a>
            <span class="breadcrumb-item active"><?=$this->title?></span>
        </nav>
    </div>
    <div>
        <?=$this->render('/layouts/nav-right')?>
    </div>
</div>
<div class="main-body p-3" id="app">
    <a href="<?=$urlManager->createUrl(['admin/card/type-edit'])?>" class="btn btn-primary">
        添加</a>

  
    <div class="float-right">

        <!-- <form method="get">
            <?php $_s = ['keyword']?>
            <?php foreach ($_GET as $_gi => $_gv): if (in_array($_gi, $_s)) {
        continue;
    }
    ?>
				                <input type="hidden" name="<?=$_gi?>" value="<?=$_gv?>">
				            <?php endforeach;?>
            <div class="input-group">
                <input class="form-control" placeholder="生字" name="keyword" autocomplete="off"
                       value="<?=isset($_GET['keyword']) ? trim($_GET['keyword']) : null?>" id="title_search">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form> -->
    </div>
    <div class="mt-4">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>课程</th>
                <th>前缀</th>
                <th>开始卡号</th>
                <th>结束卡号</th>
                <th>数量</th>
                <th>生成时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?=$value['id']?></td>
                    <td><?=$value['name']?></td>

                    <td><?=$value['prefix']?></td>
                    <td><?=$value['prefix'].$value['start_num']?></td>
                    <td><?=$value['prefix'].$value['end_num']?></td>
                    <td><?=$value['end_num']-$value['start_num']+1?></td>
                    <td><?=$value['add_time']?></td>
                    <td>

                        <!-- <a class="btn btn-sm btn-primary"
                           href="<?=$urlManager->createUrl(['admin/game/char-edit', 'id' => $value['id'], 'video_id' => $video_id])?>">修改</a>

                        <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                           data-url="<?=$urlManager->createUrl(['admin/game/char-del', 'id' => $value['id']])?>">删除</a> -->


                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>

        </table>
        <div class="text-center">
            <?=\yii\widgets\LinkPager::widget(['pagination' => $pagination])?>
            <div class="text-muted"><?=$row_count?>条数据</div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function search() {
        var keyword = $("#title_search").val();
        $.ajax({
            url: "<?=$urlManager->createUrl(['admin/game/char-list'])?>",
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