<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/19
 * Time: 14:36
 */
defined('YII_RUN') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '发放券列表';
$this->params['active_nav_group'] = 7;
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
    <div class="float-right">

        <form method="get">
            <?php $_s = ['keyword','coupon_name'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>
            <div class="input-group">
                <input class="form-control" placeholder="优惠券名称" name="coupon_name" autocomplete="off"
                       value="<?= isset($_GET['coupon_name']) ? trim($_GET['coupon_name']) : null ?>" id="title_search">
                &nbsp;&nbsp;&nbsp;
                <input class="form-control" placeholder="发放视频" name="keyword" autocomplete="off"
                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>" id="title_search">
                <span class="input-group-btn"><button class="btn btn-primary" >搜索</button></span>
            </div>
        </form>

    </div>
    <div class="mt-4">
        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>发放视频</th>
                <th>优惠券名称</th>
                <th>领取方式</th>
                <th>使用形式</th>
                <th>有效时间</th>
                <th>总发放数</th>
                <th>用户可领数量</th>
                <th>描述</th>
                <th>发放时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= $value['video_title'] ?></td>
                    <td><?= $value['coupon_name'] ?></td>
                    <td>
                        <?php if ($value['draw_type'] == 1): ?>
                            <span>免费</span>
                        <?php else: ?>
                            <span>购买</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($value['draw_type'] == 1): ?>
                            <span>满消费:<?= $value['original_cost'] ?> - 优惠额度:<?= $value['sub_price'] ?></span>
                        <?php else: ?>
                            <span>原价:<?= $value['cost_price'] ?> - 售价:<?= $value['coupon_price'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($value['expire_type'] == 1): ?>
                            <?php if ($value['expire_day'] == 0): ?>
                                <span>领取当天23:59:59过期</span>
                            <?php else: ?>
                                <span>领取<?= $value['expire_day'] ?>天过期</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if (date('Y-m-d', $value['begin_time']) == date('Y-m-d',$value['end_time'])) : ?>
                                <span>领取当天23:59:59过期</span>
                            <?php else: ?>
                                <span><?= date('Y-m-d', $value['begin_time']) ?>-<?= date('Y-m-d',$value['end_time']) ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>

                    <td><?= $value['count'] ?></td>
                    <td><?= $value['user_num'] ?></td>
                    <td><?= $value['desc'] ?></td>
                    <td><?= date('Y-m-d H:i:s',$value['addtime']) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['admin/coupon/provide-coupon-edit', 'id' => $value['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                           data-url="<?= $urlManager->createUrl(['admin/coupon/provide-coupon-del', 'id' => $value['id']]) ?>">删除</a>
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
</div>
<script type="text/javascript">

    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var video_id = [];
        $("input[name='id']:checked").each(function(i){//把所有被选中的复选框的值存入数组
            video_id[i] =$(this).val();
        });

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