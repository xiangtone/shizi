<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 19:36
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '视频列表';
$this->params['active_nav_group'] = 2;
$style = ['视频','音频','文章'];
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
	<?php
        $status = ['已下架', '已上架'];
        ?>
    <a href="<?= $urlManager->createAbsoluteUrl(['admin/video/edit']) ?>" class="btn btn-primary">
        添加</a>

        <a href="javascript:void(0)" class="btn btn-success batch"
       	data-url="<?= $urlManager->createUrl(['admin/video/batch']) ?>" data-content="是否批量上架"
       	data-type="0">批量上架</a>

        <a href="javascript:void(0)" class="btn btn-warning batch"
           data-url="<?= $urlManager->createUrl(['admin/video/batch']) ?>" data-content="是否批量下架"
           data-type="1">批量下架</a>

    	<a href="javascript:void(0)" class="btn btn-danger batch"
       	data-url="<?= $urlManager->createUrl(['admin/video/batch']) ?>" data-content="是否批量删除"
      	data-type="2">批量删除</a>

        <a href="javascript:void(0)" class="btn btn-success is_use"
       	data-url="<?= $urlManager->createUrl(['admin/video/batch']) ?>" data-content="是否全部上架"
       	data-type="4">全部上架</a>

        <a href="javascript:void(0)" class="btn btn-warning is_use"
           data-url="<?= $urlManager->createUrl(['admin/video/batch']) ?>" data-content="是否全部下架"
           data-type="3">全部下架</a>

        <a href="javascript:void(0)" class="btn btn-success is_use"
           data-url="<?= $urlManager->createUrl(['admin/video/batch']) ?>" data-content="是否全部展示到首页"
           data-type="5">批量展示到首页</a>

        <a href="javascript:void(0)" class="btn btn-warning is_use"
           data-url="<?= $urlManager->createUrl(['admin/video/batch']) ?>" data-content="是否全部从首页撤下"
           data-type="6">批量从首页撤下</a>

    <div class="float-right">
        <form method="get">

            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>

            <div class="input-group">
                <input class="form-control" placeholder="标题" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>
    <div class="mt-4">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th style="text-align: left"><span><input type="checkbox" class="goods-all"></span>&nbsp;&nbsp;ID</th>
                <th>视频标题</th>
                <th>状态</th>
                <th>是否展示在首页</th>
                <th>所属分类</th>
                <th>媒体类型</th>
                <th>封面图片</th>
                <th>时长</th>
                <th>浏览量</th>
                <th>收藏数</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
	            <?php foreach ($list as $index => $value): ?>
	                <tr>
	                	<td class="nowrap" style="text-align: left" data-toggle="tooltip"
                        data-placement="bottom" title="<?=$value['id']?>">
                    	<span>
                        <input type="checkbox"
                               class="goods-one" name="id"
                               value="<?= $value['id'] ?>">
                    	</span>&nbsp;&nbsp;<?=$value['id']?>
                    	</td>
                    	<td><?= $value['title'] ?></td>
                    	<td>
                    		<?php if ($value['status'] == 0): ?>
                                    <span class="badge badge-success">已上架</span>
                                    |
                                    <a class="del" href="javascript:" data-content="是否下架？"
                                    data-url="<?= $urlManager->createUrl(['admin/video/status', 'id' => $value['id'],'status'=>1]) ?>">下架</a>
                                <?php else: ?>
                                    <span class="badge badge-danger">已下架</span>
                                    |
                                    <a class="del" href="javascript:" data-content="是否上架？"
                                       data-url="<?= $urlManager->createUrl(['admin/video/status', 'id' => $value['id'],'status'=>0]) ?>">上架</a>
                                <?php endif; ?>
                    	</td>
                        <td>
                            <?php if ($value['is_index'] == 0): ?>
                                <span class="badge badge-success">展示</span>
                                |
                                <a class="del" href="javascript:" data-content="不展示到首页？"
                                   data-url="<?= $urlManager->createUrl(['admin/video/is-index', 'id' => $value['id'],'is_index'=>1]) ?>">不展示</a>
                            <?php else: ?>
                                <span class="badge badge-danger">不展示</span>
                                |
                                <a class="del" href="javascript:" data-content="展示到首页？"
                                   data-url="<?= $urlManager->createUrl(['admin/video/is-index', 'id' => $value['id'],'is_index'=>0]) ?>">展示</a>
                            <?php endif; ?>
                        </td>
                    	<td><?= $value['name'] ?></td>
                    	<td><?=$style[$value['style']]?></td>
                    	<td><img src='<?= $value['pic_url'] ?>' height='30px' ></td>
                    	<td><?= $value['video_time'] ?></td>
                    	<td><?= $value['page_view'] ?></td>
                    	<td><?= $value['collect_count'] ?></td>
                    	<td><?= $value['sort'] ?></td>
                    	<td>
                    		<a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['admin/video/edit', 'id' => $value['id']]) ?>">修改</a>
                            <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                               data-url="<?= $urlManager->createUrl(['admin/video/del', 'id' => $value['id']]) ?>">删除</a>
                            <a class="btn btn-sm btn-primary"
                               href="<?= $urlManager->createUrl(['admin/game/word-list', 'video_id' => $value['id']]) ?>">组词</a>
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


    $(document).on('click', '.goods-all', function () {
        var checked = $(this).prop('checked');
        $('.goods-one').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.goods-one', function () {
        var checked = $(this).prop('checked');
        var all = $('.goods-one');
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
            $('.goods-all').prop('checked', true);
        } else {
            $('.goods-all').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.batch', function () {
        var all = $('.goods-one');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_all = false;
            }
        });
        if (is_all) {
            $.myAlert({
                content: "请先勾选"
            });
        }
    });

    $(document).on('click', '.is_use', function () {
    	var a = $(this);
        var video_id = [];
        if(a.data('type') != 3 || a.data('type') != 4){
            $("input[name='id']:checked").each(function(i){//把所有被选中的复选框的值存入数组
                video_id[i] =$(this).val();
            });
        }

        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url:"<?= $urlManager->createUrl(['admin/video/batch']) ?>",
                    type: 'get',
                    dataType: 'json',
                    data: {
                        video_id: video_id,
                        type: a.data('type'),
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {

                        }
                    },
                    complete: function (res) {
                    	console.log()
                        $.myLoadingHide();
                    }
                });
            }
        })
    });
</script>