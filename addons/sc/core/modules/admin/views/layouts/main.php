<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 * @var \yii\web\View $this
 */
$urlManager = Yii::$app->urlManager;
$this->params['active_nav_group'] = isset($this->params['active_nav_group']) ? $this->params['active_nav_group'] : 0;
$version = '1.0.0';
$is_auth = Yii::$app->cache->get('IS_AUTH');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title><?= $this->title ?></title>
    <link href="//at.alicdn.com/t/font_438938_rctv01mmoi1i3sor.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/common.css?version=<?= $version ?>" rel="stylesheet">
    <link href="<?= Yii::$app->request->baseUrl ?>/statics/css/flex.css?version=<?= $version ?>" rel="stylesheet">


    <link href="<?= Yii::$app->request->baseUrl ?>/statics/admin/css/common.css?version=<?= $version ?>" rel="stylesheet">
    <style>
    </style>
    <script>
        var _csrf = "<?=Yii::$app->request->csrfToken?>";
    </script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/jquery.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/vue.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/tether.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/bootstrap.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/plupload.full.min.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/js/common.js?version=<?= $version ?>"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/statics/admin/js/uploadVideo.js?version=<?= $version ?>"></script>
</head>
<body>
<?= $this->render('/components/pick-file.php') ?>
<?= $this->render('/components/pick-link.php') ?>
<div class="sidebar">
    <div class="sidebar-nav">
        <div class="sidebar-logo" style="">
            <a class="text-overflow-ellipsis"
               href="<?= $urlManager->createUrl(['admin/system']) ?>"><?= $this->context->store->name ?></a>
            <div style="font-size: .85rem;color: #bbb;display: none">
                <a href="http://cloud.zjhejiang.com/we7/mall/" target="_blank">禾匠视频</a>
                <span>v<?= $this->context->version ?></span>
            </div>
        </div>
        <div class="nav-group <?= $this->params['active_nav_group'] == 1 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-shezhi"></i>设置</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/system/setting']) ?>">系统设置</a>
                <a href="<?= $urlManager->createUrl(['admin/system/home']) ?>">自定义设置</a>
                <a href="<?= $urlManager->createUrl(['admin/system/tpl']) ?>">模板消息</a>
                <a href="<?= $urlManager->createUrl(['admin/advertisement/edit']) ?>">广告位</a>
                <a href="<?= $urlManager->createUrl(['admin/system/wxapp']) ?>">小程序发布</a>
                <a href="<?= $urlManager->createUrl(['admin/system/wxapp-pages']) ?>">小程序页面</a>
                <?php if ($this->context->is_admin): ?>
                    <a href="<?= $urlManager->createUrl(['admin/system/upload']) ?>">上传设置</a>
                <?php endif; ?>
                <!--<a href="<?= $urlManager->createUrl(['admin/path/index']) ?>">补丁区</a>-->
                <a href="<?= $urlManager->createUrl(['admin/system/sms']) ?>">短信通知</a>
            </div>
        </div>

        <div class="nav-group <?= $this->params['active_nav_group'] == 2 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-shipinicon"></i>视频管理</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/video/banner']) ?>">轮播图列表</a>
                <a href="<?= $urlManager->createUrl(['admin/video/cat']) ?>">视频分类</a>
                <a href="<?= $urlManager->createUrl(['admin/video/list']) ?>">视频列表</a>
            </div>
        </div>



        <div class="nav-group <?= $this->params['active_nav_group'] == 7 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-person"></i>营销管理</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/coupon/list']) ?>">优惠券</a>
                <a href="<?= $urlManager->createUrl(['admin/coupon/provide-coupon']) ?>">发放券管理</a>
                <a href="<?= $urlManager->createUrl(['admin/coupon/user-coupon']) ?>">用户优惠券</a>
            </div>
        </div>


        <div class="nav-group <?= $this->params['active_nav_group'] == 3 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-person"></i>用户管理</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/user/index']) ?>">用户列表</a>
                <a href="<?= $urlManager->createUrl(['admin/user/clerk']) ?>">核销员列表</a>
            </div>
        </div>
        <div class="nav-group <?= $this->params['active_nav_group'] == 4 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-comment"></i>评论管理</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/comment/index']) ?>">评论列表</a>
            </div>
        </div>
        <div class="nav-group <?= $this->params['active_nav_group'] == 5 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-dingdan"></i>订单管理</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/order/index']) ?>">订单列表</a>
                <a href="<?= $urlManager->createUrl(['admin/order/refund']) ?>">售后订单</a>
                <a href="<?= $urlManager->createUrl(['admin/order/video']) ?>">视频记录</a>
                <a href="<?= $urlManager->createUrl(['admin/order/member']) ?>">会员购买记录</a>
            </div>
        </div>
        <div class="nav-group <?= $this->params['active_nav_group'] == 6 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-huiyuan"></i>会员管理</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/member/index']) ?>">会员设置</a>
            </div>
        </div>
        <!--添加班级管理-->
        <div class="nav-group <?= $this->params['active_nav_group'] == 8 ? 'active' : null ?>">
            <a href="javascript:"><i class="iconfont icon-huiyuan"></i>班级管理</a>
            <div class="sub-nav-list">
                <a href="<?= $urlManager->createUrl(['admin/classes/index']) ?>">班级设置</a>
            </div>
        </div>
    </div>
</div>
<div class="main">
    <?= $content ?>
</div>

<script>
    $(document).on("click", ".sidebar-nav .nav-group > a", function () {
        var group = $(this).parents(".nav-group");
        if (group.hasClass("active")) {
            group.removeClass("active");
        } else {
            $(this).parents(".nav-group").addClass("active").siblings().removeClass("active");
        }
    });

    $(document).on("click", ".input-hide .tip-block", function () {
        $(this).hide();
    });


    $(document).on("click", ".input-group .dropdown-item", function () {
        var val = $.trim($(this).text());
        $(this).parents(".input-group").find(".form-control").val(val);
    });
</script>
<script>
    $(document).on('click','.del',function(){
        var a = $(this);
        $.myConfirm({
            title:'提示',
            content: a.data('content'),
            confirm:function(){
                $.ajax({
                    url: a.data('url'),
                    type:'get',
                    dataType:'json',
                    success:function(res){
                        if(res.code == 0){
                            window.location.reload();
                        }else{
                            $.myAlert({
                                title:res.msg
                            });
                        }
                    }
                });
            }
        });
        return false;
    });
</script>
</body>
</html>