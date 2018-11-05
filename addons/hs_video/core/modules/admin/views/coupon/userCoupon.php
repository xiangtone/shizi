<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/19
 * Time: 14:36
 */
defined('YII_RUN') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '用户优惠券列表';
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
                <input class="form-control" placeholder="用户昵称" name="keyword" autocomplete="off"
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
                <th>用户昵称</th>
                <th>优惠券名称</th>
                <th>领取方式</th>
                <th>使用形式</th>
                <th>有效时间</th>
                <th>描述</th>
                <th>是否核销</th>
                <th>核销员</th>
                <th>是否过期</th>
                <th>领取时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list as $index => $value): ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    <td><?= $value['nickname'] ?></td>
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
                        <span><?= date('Y-m-d', $value['addtime']) ?> - <?= date('Y-m-d', $value['end_time']) ?></span>
                    </td>
                    <td><?= $value['desc'] ?></td>
                    <?php if ($value['is_use'] == 0) : ?>
                    <td><span>未核销</span></td>
                    <?php else: ?>
                    <td><span>已核销</span></td>
                    <?php endif; ?>
                    <td><?= $value['clerk'] ?></td>
                    <?php if ($value['exceed'] == 1) : ?>
                        <td><span>已过期</span></td>
                    <?php else: ?>
                        <td><span>未过期</span></td>
                    <?php endif; ?>
                    <td><?= date('Y-m-d H:i:s',$value['addtime']) ?></td>
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
    $(document).on('click', '.goods-all', function () {
        var checked = $(this).prop('checked');
        $('.goods-one').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });

</script>