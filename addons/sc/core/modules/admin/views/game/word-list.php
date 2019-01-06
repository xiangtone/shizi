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
            <a class="breadcrumb-item" href="<?=$urlManager->createUrl(['admin/system/index'])?>">系统</a>
            <span class="breadcrumb-item active"><?=$this->title?></span>
        </nav>
    </div>
    <div>
        <?=$this->render('/layouts/nav-right')?>
    </div>
</div>
<div class="main-body p-3" id="app">
    <a href="<?=$urlManager->createUrl(['admin/game/word-edit', 'video_id' => $video_id])?>" class="btn btn-primary">
        添加</a>

    <a class="btn btn-primary" href="javascript:" onClick="openwin()">预览</a>
    <div class="float-right">

        <form method="get">
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
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?=$value['id']?></td>
                    <td><?=$value['video_id']?></td>

                    <td><?=$value['new_word']?></td>
                    <td><?=$value['target_word']?></td>
                    <td><?=$value['voice_url']?>
                    <?php if ($value['voice_url']): ?>
                    <audio src="<?=$value['voice_url']?>" controls="controls"></audio>
                    <?php endif;?>
                    </td>
                    <td>

                        <a class="btn btn-sm btn-primary"
                           href="<?=$urlManager->createUrl(['admin/game/word-edit', 'id' => $value['id'], 'video_id' => $video_id])?>">修改</a>

                        <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                           data-url="<?=$urlManager->createUrl(['admin/game/word-del', 'id' => $value['id']])?>">删除</a>


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
            url: "<?=$urlManager->createUrl(['admin/game/word-list'])?>",
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

    function openwin(){
        var openUrl = "<?php echo \Yii::$app->params['zuciUrl'] . '?video_id=' . $video_id; ?>";//弹出窗口的url
        console.log("url="+openUrl);
        var iWidth=500; //弹出窗口的宽度;
        var iHeight=750; //弹出窗口的高度;
        var iTop = (window.screen.availHeight-30-iHeight)/2; //获得窗口的垂直位置;
        var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
        window.open(openUrl,"_blank","height="+iHeight+",width="+iWidth+", top="+iTop+",left="+iLeft+",menubar=no,toolbar=no,status=no,location=no");
        //window.open('http://baidu.com','newwindow', "height=100, width=450, top=200,left=200 , toolbar =no, menubar=no, scrollbars=no, resizeable=no, location=no, status=no");
        //window.open ('http://baidu.com', "newwindow", "height=400, width=550, top=100, left=380, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no");
    }

</script>