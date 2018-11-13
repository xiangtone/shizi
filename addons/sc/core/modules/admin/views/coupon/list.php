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
$this->title = '优惠券列表';
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
    <a href="<?= $urlManager->createUrl(['admin/coupon/edit']) ?>" class="btn btn-primary">
        添加</a>
    <div class="float-right">

        <form method="get">
            <?php $_s = ['keyword'] ?>
            <?php foreach ($_GET as $_gi => $_gv):if (in_array($_gi, $_s)) continue; ?>
                <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
            <?php endforeach; ?>
            <div class="input-group">
                <input class="form-control" placeholder="名称" name="keyword" autocomplete="off"
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
                <th>名称</th>
                <th>领取方式</th>
                <th>使用形式</th>
                <th>有效时间</th>
                <th>添加时间</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= $value['name'] ?></td>
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
                                <span>添加当天领取当天23:59:59过期</span>
                            <?php else: ?>
                                <span>领取<?= $value['expire_day'] ?>天过期</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if (date('Y-m-d', $value['begin_time']) == date('Y-m-d',$value['end_time'])) : ?>
                                <span>添加当天领取当天23:59:59过期</span>
                            <?php else: ?>
                                <span><?= date('Y-m-d', $value['begin_time']) ?>-<?= date('Y-m-d',$value['end_time']) ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td><?= date('Y-m-d', $value['addtime']) ?></td>
                    <td><?= $value['sort'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['admin/coupon/provide', 'id' => $value['id']]) ?>">发放</a>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['admin/coupon/edit', 'id' => $value['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del" href="javascript:" data-content="是否删除？"
                           data-url="<?= $urlManager->createUrl(['admin/coupon/del', 'id' => $value['id']]) ?>">删除</a>
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
    function search() {
        var keyword = $("#title_search").val();
        $.ajax({
            url: "<?=$urlManager->createUrl(['admin/coupon/search-coupon'])?>",
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