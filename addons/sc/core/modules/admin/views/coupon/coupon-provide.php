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
$this->title = '优惠券发放';
$this->params['active_nav_group'] = 7;
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl)
    $returnUrl = $urlManager->createUrl(['admin/coupon/list']);
?>
<style xmlns:v-bind="http://www.w3.org/1999/xhtml">
    .user-list .user-item {
        text-align: center;
        width: 120px;
        border: 1px solid #e3e3e3;
        cursor: pointer;
        display: inline-block;
        vertical-align: top;
        border-radius: .15rem;
    }

    .user-list .user-item:hover {
        background: rgba(238, 238, 238, 0.54);
    }

    .user-list .user-item img {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 999px;
    }

    .user-list .user-item.active {
        background: rgba(2, 117, 216, 0.69);
        color: #fff;
    }
</style>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/coupon/list']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">优惠券列表</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="app">
        <form class="form auto-form" method="post" autocomplete="off" data-timeout="5" return="<?= $returnUrl ?>">
            <div class="form-body">
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="">优惠券名称</label>
                    </div>
                    <div class="col-9">
                        <?= $coupon->name ?>
                        <input type="hidden" name="coupon_name" value="<?= $coupon->name ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="">领取方式</label>
                    </div>
                    <?php if ($coupon['draw_type'] == 1): ?>
                        <div class="col-9">
                            免费领取
                            <input type="hidden" name="draw_type" value="<?= $coupon['draw_type'] ?>">
                        </div>
                    <?php else: ?>
                        <div class="col-9">
                            <input type="hidden" name="draw_type" value="<?= $coupon['draw_type'] ?>">
                            购买
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group row">
                    <input type="hidden" name="draw_type" value="<?= $coupon['draw_type'] ?>">
                    <?php if ($coupon['draw_type'] == 1): ?>
                        <div class="form-group-label col-3 text-right">
                            <label class="">满消费（元）</label>
                        </div>
                        <div class="col-9">
                            <?= $coupon['original_cost'] ?>
                            <input type="hidden" name="original_cost" value="<?= $coupon['original_cost'] ?>">
                        </div>
                        <div class="form-group-label col-3 text-right">
                            <label class="">优惠额度（元）</label>
                        </div>
                        <div class="col-9">
                            <?= $coupon['sub_price'] ?>
                            <input type="hidden" name="sub_price" value="<?= $coupon['sub_price'] ?>">
                        </div>
                    <?php else: ?>
                        <div class="form-group-label col-3 text-right">
                            <label class="">原价（元）</label>
                        </div>
                        <div class="col-9">
                            <?= $coupon['cost_price'] ?>
                            <input type="hidden" name="cost_price" value="<?= $coupon['cost_price'] ?>">
                        </div>
                        <div class="form-group-label col-3 text-right">
                            <label class="">劵后价（元）</label>
                        </div>
                        <div class="col-9">
                            <?= $coupon['coupon_price'] ?>
                            <input type="hidden" name="coupon_price" value="<?= $coupon['coupon_price'] ?>">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="">优惠券有效期</label>
                    </div>
                    <div class="col-9">
                        <input type="hidden" name="expire_type" value="<?= $coupon->expire_type ?>">
                        <?php if ($coupon->expire_type == 1): ?>
                            发放后<span class="text-danger"><?= $coupon->expire_day ?>天内</span>可以使用
                            <input type="hidden" name="expire_day" value="<?= $coupon->expire_day ?>">
                        <?php else: ?>
                            <span class="text-danger"><?= date('Y-m-d', $coupon->begin_time) ?></span>至<span
                                class="text-danger"><?= date('Y-m-d', $coupon->end_time) ?></span>
                            <input type="hidden" name="begin_time" value="<?= $coupon->begin_time ?>">
                            <input type="hidden" name="end_time" value="<?= $coupon->end_time ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group-label col-3 text-right">
                        <label class="">发放视频</label>
                    </div>
                    <div class="col-9">
                        <div class="input-group mb-3" style="max-width: 250px">
                            <input class="form-control search-user-keyword" placeholder="请输入视频标题"
                                   onkeydown="if(event.keyCode==13) {search_user();return false;}">
                            <span class="input-group-btn">
                                <a class="btn btn-secondary search-user-btn" onclick="search_user()"
                                   href="javascript:">查找视频</a>
                            </span>
                        </div>

                        <div class="user-list" v-if="user_list">
                            <div v-if="user_list">
                                <div v-for="(user,index) in user_list" class="user-item"
                                     style="width:200px;height:72px;margin-right: 10px;margin-bottom: 72px;">
                                    <label class="user-item" style="width:200px;height:72px;margin-right: 10px;">
                                        <input v-bind:value="user.id" type="checkbox" name="user_id_list[]"
                                               style="display: none" class="user-list-id" onclick="video_id()"
                                               style="height:72px;">
                                        <img data-toggle="tooltip" data-placement="top" v-bind:src="user.pic_url"
                                             style="position:relative;top:5px;">
                                        <div
                                            style="margin-bottom: 2px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">
                                            {{user.title}}
                                        </div>
                                    </label>

                                    <div v-bind:id="'aa'+index" style="display: none" class="bb"
                                         style="position: absolute">
                                        <div class="input-group input-group-sm" id="total_count">
                                            <span class="input-group-addon">总发放数</span>
                                            <input type="number" class="form-control" name="total_count[]">
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">可领数量</span>
                                            <input type="number" class="form-control" name="user_num[]" id="user_num">
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">简短描述</span>
                                            <input type="text" v-bind:id="'bb'+index" class="form-control" name="desc[]"
                                                   placeholder="字数限制在十个以内">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div v-else style="color: #ddd;">请输入视频标题查找</div>
                    </div>
                </div>
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
    var app = new Vue({
        el: "#app",
        data: {
            user_list: []
        }
    });
</script>
<script>
    $(document).on("change", "input[name=expire_type]", function () {
        $(".expire-type").hide();
        $(".expire-type-" + this.value).show();
    });
    $(document).on("change", "input[name='user_id_list[]']", function () {
        console.log($(this).parents("label"));
        if ($(this).prop("checked")) {
            $(this).parents("label").addClass("active");
        } else {
            $(this).parents("label").removeClass("active");
        }
    });
    function video_id() {
        var boxes = $(".user-list-id");
        for (i = 0; i < boxes.length; i++) {
            if (boxes[i].checked == true) {
                $('#aa' + i).css('display', '');
            } else {
                $('#aa' + i + ' input').val("");
                $('#aa' + i).css('display', 'none');
            }
        }
    }
    function search_user() {
        var btn = $(".search-user-btn");
        var keyword = $(".search-user-keyword").val();
        btn.btnLoading("正在查找");

        $.ajax({
            url: "<?=$urlManager->createUrl(['admin/coupon/search-video'])?>",
            dataType: "json",
            data: {
                keyword: keyword,
            },
            success: function (res) {
                btn.btnReset();
                if (res.code == 0) {
                    app.user_list = res.data.list;
                }
            }
        });
    }

    $(document).on("click", ".auto-form-btn", "click", function () {
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
        var user_list_id = $(".user-list-id").val();
        if (!user_list_id) {
            btn.btnReset();
            $.myAlert({
                content: '请选择视频'
            });
            return;
        }
        var end_time = $("input[name='end_time']").val()
        var tmp = Date.parse(new Date()).toString();
        tmp = tmp.substr(0, 10);
        if (end_time < tmp) {
            btn.btnReset();
            $.myAlert({
                content: '优惠券已过期'
            });
            return;
        }

        function getByteLen(val) {
            var len = 0;
            for (var i = 0; i < val.length; i++) {
                var a = val.charAt(i);
                if (a.match(/[^\x00-\xff]/ig) != null) {
                    len += 2;
                }
                else {
                    len += 1;
                }
            }
            return len;
        }

        var boxes = $(".user-list-id");
        for (i = 0; i < boxes.length; i++) {
            if (boxes[i].checked == true) {
                var desc = $('#bb' + i).val();
                if (getByteLen(desc) > 20) {
                    btn.btnReset();
                    $.myAlert({
                        content: '描述字数超出限制'
                    });
                    return
                }
            }
        }
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
                        'content': res.msg
                    })
                    error.html(res.msg).show();
                    btn.btnReset();
                }
            },
        });
        return false;
    });
</script>