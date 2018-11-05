<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 9:22
 */
defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '小程序页面';
$this->params['active_nav_group'] = 1;
$list = [
    [
        'name' => '视频首页',
        'route' => 'pages/index/index',
        'params' => [],
    ],
    [
        'name' => '分类',
        'route' => 'pages/cat/cat',
        'params' => [],
    ],
    [
        'name' => '分类下视频',
        'route' => 'pages/cat-second/cat-second',
        'params' => [
            [
                'name' => 'cat_id',
                'desc' => '分类ID',
            ],
        ],
    ],
    [
        'name' => '我的',
        'route' => 'pages/user/user',
        'params' => [],
    ],
    [
        'name' => '会员中心',
        'route' => 'pages/member/member',
        'params' => [],
    ],
    [
        'name' => '优惠券',
        'route' => 'pages/user-coupon/user-coupon',
        'params' => [],
    ],
    [
        'name' => '已购买内容',
        'route' => 'pages/user-video/user-video',
        'params' => [],
    ],
    [
        'name' => '订单列表',
        'route' => 'pages/order/order',
        'params' => [
            [
                'name' => 'status',
                'desc' => '订单状态[待使用=1,已完成=2,退款=3]',

            ],
        ],
    ],
    [
        'name' => '评论过我的',
        'route' => 'pages/user-comment/user-comment',
        'params' => [],
    ],
    [
        'name' => '我的收藏',
        'route' => 'pages/collect/collect',
        'params' => [],
    ],
    [
        'name' => '首页搜索',
        'route' => 'pages/search/search',
        'params' => [],
    ],
    [
        'name' => '视频详情',
        'route' => 'pages/video/video',
        'params' => [
            [
                'name' => 'id',
                'desc' => '视频ID',
            ],
        ],
    ],
    [
        'name' => '评论视频',
        'route' => 'pages/comment/comment',
        'params' => [
            [
                'name' => 'video_id',
                'desc' => '视频ID',
            ],
        ],
    ],
];
?>
<style>
    .page-list > li {
        margin-bottom: 1rem;
    }
</style>
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
<div class="main-body p-3">
    <div class="alert alert-warning rounded-0">
        页面参数使用示例：订单列表，需要查询状态是已完成的订单，则拼接参数后的路径为<span class="text-danger">pages/order/order?status=3</span>
    </div>
    <div class="panel">
        <div class="panel-header"><?= $this->title ?></div>
        <div class="panel-body">
            <ul class="page-list">
                <?php foreach ($list as $item): ?>
                    <li>
                        <div class="text-primary"><?= $item['name'] ?></div>
                        <div>
                            <span>路径：</span>
                            <span class="text-primary"><?= $item['route'] ?></span>
                        </div>
                        <?php if (is_array($item['params']) && count($item['params'])): ?>
                            <div>
                                <span>参数：</span>
                                <?php foreach ($item['params'] as $i => $p): ?>
                                    <span class="text-primary"><?= $p['name'] ?></span>
                                    <span class="text-muted mr-3"><?= $p['desc'] ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>