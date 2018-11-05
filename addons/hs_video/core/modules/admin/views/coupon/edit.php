<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 19:37
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '优惠券编辑';
$this->params['active_nav_group'] = 7;

$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl)
    $returnUrl = $urlManager->createUrl(['admin/coupon/list']);

?>
<link href="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet">
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">优惠券列表</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3 bg-white" id="app">
    <form class="auto-submit-form-1 mt-4" method="post" autocomplete="off" style="max-width: 70rem;"
          data-return="<?= $urlManager->createUrl(['admin/coupon/list']) ?>">
        <div class="form-body">
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">优惠券名称</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="name" value="<?= $coupon->name ? $coupon->name : '' ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">领取方式</label>
                </div>
                <div class="col-9 pt-1">
                    <label class="radio-label">
                        <input name="draw_type"
                            <?= $coupon->draw_type == null || $coupon->draw_type == 1 ? 'checked' : null ?>
                               type="radio"
                               value="1"
                               id="optionsRadios1">免费
                    </label>
                    <label class="radio-label">
                        <input name="draw_type"
                            <?= $coupon->draw_type == 2 ? 'checked' : null ?>
                               type="radio"
                               value="2"
                               id="optionsRadios2">购买
                    </label>
                </div>
            </div>
            <div class="form-group row draw-type draw-type-1"
                 style="<?= $coupon->draw_type != null && $coupon->draw_type != 1 ? 'display:none' : null ?>">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">免费</label>
                </div>
                <div class="col-9">
                    <div class="input-group">
                        <span class="input-group-addon">满消费：</span>
                        <input class="form-control" type="number" min="-1"
                               name="original_cost"
                               value="<?= $coupon->original_cost ? $coupon->original_cost : 0 ?>">
                        <span class="input-group-addon">优惠金额：</span>
                        <input class="form-control" type="number" min="-1"
                               name="sub_price"
                               value="<?= $coupon->sub_price ? $coupon->sub_price : 0 ?>">
                    </div>
                </div>
            </div>
            <div class="form-group row draw-type draw-type-2"
                 style="<?= $coupon->draw_type != 2 ? 'display:none' : null ?>">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">购买</label>
                </div>
                <div class="col-9">
                    <div class="input-group">
                        <span class="input-group-addon">原价：</span>
                        <input class="form-control" type="number"
                               id="original_cost"
                               name="cost_price"
                               value="<?= $coupon->cost_price ? $coupon->cost_price : 0 ?>">
                        <span class="input-group-addon">售价：</span>
                        <input class="form-control" type="number"
                               id="sub_price"
                               name="coupon_price"
                               value="<?= $coupon->coupon_price ? $coupon->coupon_price : 0 ?>">
                    </div>
                    <div class="text-danger text-muted">原价为商品本来所值金额</div>
                    <div class="text-danger text-muted">售价即为优惠券售价，最低0.01元。购买该劵可抵用商品原价所值金额，不能低于原价</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">优惠券有效期</label>
                </div>
                <div class="col-9 pt-1">
                    <label class="radio-label">
                        <input name="expire_type"
                            <?= $coupon->expire_type == null || $coupon->expire_type == 1 ? 'checked' : null ?>
                               type="radio"
                               value="1"
                               id="optionsRadios3">领取后N天内有效
                    </label>
                    <label class="radio-label">
                        <input name="expire_type"
                            <?= $coupon->expire_type == 2 ? 'checked' : null ?>
                               type="radio"
                               value="2"
                               id="optionsRadios4">时间段
                    </label>
                </div>
            </div>
            <div class="form-group row expire-type expire-type-1"
                 style="<?= $coupon->expire_type != null && $coupon->expire_type != 1 ? 'display:none' : null ?>">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">有效天数</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="number" step="1" min="1" name="expire_day"
                           value="<?= $coupon->expire_day ? $coupon->expire_day : 1 ?>">
                    <div class="text-danger text-muted">注：0为当天23:59:59过期</div>
                </div>
            </div>
            <div class="form-group row expire-type expire-type-2"
                 style="<?= $coupon->expire_type != 2 ? 'display:none' : null ?>">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">有效期范围</label>
                </div>
                <div class="col-9">
                    <div class="input-group">
                        <span class="input-group-addon">开始日期：</span>
                        <input class="form-control"
                               id="begin_time"
                               name="begin_time"
                               value="<?= $coupon->begin_time ? date('Y-m-d', $coupon->begin_time) : date('Y-m-d') ?>">
                        <span class="input-group-addon">结束日期：</span>
                        <input class="form-control"
                               id="end_time"
                               name="end_time"
                               value="<?= $coupon->end_time ? date('Y-m-d', $coupon->end_time) : date('Y-m-d') ?>">
                    </div>
                    <div class="text-danger text-muted">注：开始日期和结束日期为同一天优惠券为当天23:59:59过期</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                    <label class=" col-form-label required">排序</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="number" step="1" min="1" name="sort"
                           value="<?= $coupon->sort ? $coupon->sort : 100 ?>">
                    <div class="text-danger text-muted">注：排序按升序排列</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-3 text-right">
                </div>
                <div class="col-9">
                    <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                    <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                    <a class="btn btn-primary submit-btn-1" href="javascript:">保存</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script>
    $(document).on("change", "input[name=expire_type]", function () {
        $(".expire-type").hide();
        $(".expire-type-" + this.value).show();
    });

    $(document).on("change", "input[name=draw_type]", function () {
        $(".draw-type").hide();
        $(".draw-type-" + this.value).show();
    });

    (function () {
        $.datetimepicker.setLocale('zh');
        $('#begin_time').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $('#end_time').val() ? $('#end_time').val() : false
                })
            },
            timepicker: false,
        });
        $('#end_time').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    minDate: $('#begin_time').val() ? $('#begin_time').val() : false
                })
            },
            timepicker: false,
        });
    })();
    var ue = UE.getEditor('editor', {
        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
        enableAutoSave: false,
        saveInterval: 1000 * 3600,
        enableContextMenu: false,
        autoHeightEnabled: false,
    });
</script>
<script>
    (function () {
        $.datetimepicker.setLocale('zh');
        $("input[name='add_time_begin']").datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $("input[name='add_time_end']").val() ? $("input[name='add_time_end']").val() : false
                })
            },
            timepicker: false,
        });
    })();
</script>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            form_list: <?=$form_list?>
        }
    });
</script>
<script>
    $(document).on('click', '.order .custom-control-input', function () {
        if ($(this).val() == 1) {
            $('.introduce').prop('hidden', false)
        } else {
            $('.introduce').prop('hidden', true)
        }
    });
    function getvideo(url) {
        $.ajax({
            url: "<?=$urlManager->createUrl(['admin/video/get-video'])?>",
            type: 'get',
            dataType: 'json',
            async: false,
            data: {
                url: url
            },
            success: function (res) {
                if (res.code == 0) {
                    url = res.url
                } else {
                    $('.video-type-error').prop('hidden', false).html(res.msg);
                }
            }
        });
        return url;
    }
    $(document).ready(function () {
        function time() {
            var url = $('.video');
            if (url.val() == '') {
                return true;
            }
            if ($('.video_time').val() != '') {
                return true;
            }
            $('#myVideo').prop('src', url.val());
            $('.video-time-error').prop('hidden', false).html('视频时长获取中，请稍后...');
            var int = setInterval(function () {
                var time = document.getElementById('myVideo').duration;
                if (time && time != 'NaN') {
                    $('.video_time').val(time);
                    $('.video-time-error').prop('hidden', true);
                    window.clearInterval(int);
                }
            }, 1000);
        }

        time();
    });
    $(document).on('click', '.hand-btn', function () {
        $('.video_time').attr('readonly', false);
    })
    $(document).on("click", ".submit-btn-1", "click", function () {

        var form = $(this).parents("form");
        var return_url = form.attr("data-return");
        var timeout = form.attr("data-timeout");
        var btn = $(this);
        var error = form.find(".form-error");
        var success = form.find(".form-success");
        error.hide();
        success.hide();
        $("input[name='_csrf']").val("<?=Yii::$app->request->csrfToken?>");
        btn.btnLoading("正在提交");
        if(!$("input[name='name']").val()){
            btn.btnReset();
            $.myAlert({
                content: '请输入优惠券名称'
            });
            return;
        }
        if($("input[name='draw_type']:checked").val() == 1){
            if(!$("input[name='original_cost']").val()){
                btn.btnReset();
                $.myAlert({
                    content: '请输入满消费金额'
                });
                return;
            }
            if($("input[name='original_cost']").val() < 0){
                btn.btnReset();
                $.myAlert({
                    content: '满消费不能为负数'
                });
                return;
            }
            if(!$("input[name='sub_price']").val()){
                btn.btnReset();
                $.myAlert({
                    content: '请输入优惠金额'
                });
                return;
            }
            if($("input[name='sub_price']").val() < 0){
                btn.btnReset();
                $.myAlert({
                    content: '优惠金额不能为负数'
                });
                return;
            }
        }else{
            if(!$("#original_cost").val()){
                btn.btnReset();
                $.myAlert({
                    content: '请输入原价'
                });
                return;
            }
            if(!$("#sub_price").val()){
                btn.btnReset();
                $.myAlert({
                    content: '请输入售价'
                });
                return;
            }
            if($("#sub_price").val() < 0.01){
                btn.btnReset();
                $.myAlert({
                    content: '售价最低0.01元'
                });
                return;
            }
            if(parseInt($("#sub_price").val()) > parseInt($("#original_cost").val())){
                btn.btnReset();
                $.myAlert({
                    content: '售价不能高于原价'
                });
                return;
            }
        }
        if($("input[name='expire_type']:checked").val() == 1){
            if($("input[name='expire_day']").val() <= -1){
                btn.btnReset();
                $.myAlert({
                    content: '有效天数不能为负数'
                });
                return;
            }
        }else{
            var ts = Math.round(new Date().getTime() / 1000).toString();
            function timestampToTime(timestamp) {
                var date = new Date(timestamp * 1000);
                Y = date.getFullYear() + '-';
                M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
                D = (date.getDate() < 10 ? '0'+(date.getDate()):date.getDate());
                return Y+M+D;
            }
            var str = $('#begin_time').val().replace('-','');
            var str2 = str.replace('-','');
//            var str5 = $('#end_time').val().replace('-','');
//            var str6 = str5.replace('-','');
            var str3 = timestampToTime(ts).replace('-','');
            var str4 = str3.replace('-','');
            if(parseInt(str2) < parseInt(str4)){
                btn.btnReset();
                $.myAlert({
                    content: '有效期范围不能小于当前时间'
                });
                return
            }
        }
        $.ajax({
            type: form.attr("method"),
            data: form.serialize(),
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    success.html(res.msg).show();
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
                    error.html(res.msg).show();
                    btn.btnReset();
                }
            }
        });
        return false;
    });
</script>
<script>
    $(document).on('click', '.form-del', function () {
        var index = $(this).data('index');
        app.form_list.splice(index, 1);
    });
    $(document).on('click', '.form-prev', function () {
        var index = $(this).data('index');
        if (index == 0) {
            return;
        }
        var middle = app.form_list[index];
        var prev = app.form_list[index - 1];
        app.form_list.splice(index - 1, 2, middle, prev);
    });
    $(document).on('click', '.form-next', function () {
        var index = $(this).data('index');
        if (index == app.form_list.length - 1) {
            return;
        }
        var middle = app.form_list[index];
        var next = app.form_list[index + 1];
        app.form_list.splice(index, 2, next, middle);
    });
</script>
<script>
    $(document).on('click', '.form-add', function () {
        var aa = {};
        aa.type = $('.form-add-type').val();
        app.form_list.push(aa);
    });
    $(document).on('click', '.re', function () {
        var check = $(this).prop('checked');
        var index = $(this).data('index');
        if (check) {
            app.form_list[index].required = 1;
        } else {
            app.form_list[index].required = 0;
        }
    });
</script>