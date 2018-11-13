<?php
defined('YII_RUN') or exit('Access Denied');

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 10:18
 */
use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '发放券修改';
$this->params['active_nav_group'] = 7;

$returnUrl = $urlManager->createUrl(['admin/coupon/videoCouponList']);
?>
<style xmlns:v-bind="http://www.w3.org/1999/xhtml">

</style>
<link href="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet">
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/coupon/provide-coupon']) ?>">发放券列表</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3 bg-white" id="app">
    <div class="panel-body">
        <form class="form auto-form" method="post" autocomplete="off" data-timeout="5" return="<?= $returnUrl ?>">
            <div class="input-group">
                <span class="input-group-addon">总发放数</span>
                <input type="text" class="form-control" name="total_count" value="<?= $list->count ?>">
            </div>
            <div class="input-group">
                <span class="input-group-addon">可领数量</span>
                <input type="text" class="form-control" name="user_num" value="<?= $list->user_num ?>">
            </div>
            <div class="input-group">
                <span class="input-group-addon">简短描述</span>
                <input type="text" class="form-control" name="desc" value="<?= $list->desc ?>">
            </div>
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                </div>
                <div class="col-9">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on("click", ".auto-form-btn", "click", function () {
        var form = $(this).parents("form");
        $.ajax({
            type: form.attr("method"),
            data: form.serialize(),
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    $.myAlert({
                        content: res.msg
                    });
                    if (return_url) {
                        if (timeout)
                            timeout = 1000 * parseInt(timeout);
                        else
                            timeout = 1500;
                        setTimeout(function () {
                            location.href = return_url;
                        }, timeout);

                    } else {
                        btn.btnReset();
                    }
                }
                if (res.code == 1) {
                    $.myAlert({
                        'content':res.msg
                    })
                    error.html(res.msg).show();
                    btn.btnReset();
                }
            },
        });
        return false;
    });
//</script>