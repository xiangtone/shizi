<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/20
 * Time: 14:09
 */
defined('YII_RUN') or exit('Access Denied');
use \app\models\Level;

/* @var \app\models\Level $level */
$urlManager = Yii::$app->urlManager;
$this->title = '学校编辑';
$this->params['active_nav_group'] = 3;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/classes/index']) ?>">班级设置</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>
<div class="main-body p-3">
    <div class="form bg-white">
        <div class="form-title" style="border: 0;">
            <nav class="nav nav-tabs" id="myTab" role="tablist">
                <a class="nav-item nav-link active" id="nav-level-edit" data-toggle="tab" href="#level-edit"
                   role="tab"
                   aria-controls="level-edit" aria-selected="true">学校设置</a>

            </nav>

        </div>
        <div class="tab-content mt-4" id="nav-tabContent">
            <div class="tab-pane fade show active" id="level-edit" role="tabpanel" aria-labelledby="nav-level-edit">

                <form method="post" class="auto-submit-form" autocomplete="off"
                      data-return="<?= $urlManager->createUrl(['admin/school/index']) ?>">
                    <div class="form-body">
                        <input type="hidden" name="scene" value="edit">
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">用户名</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="username" value="<?= $school->username ? $school->username : '' ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">密码</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="password" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-9 offset-sm-3">
                                <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                                <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                                <a class="btn btn-primary submit-btn-1" href="javascript:">保存</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    //JS保存按钮相关处理

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

        if (!$("input[name='username']").val()) {
            btn.btnReset();
            $.myAlert({
                content: '请输入用户名'
            });
            return;
        }
        if (!$("input[name='password']").val()) {
            btn.btnReset();
            $.myAlert({
                content: '请输入密码'
            });
            return;
        }

        console.log('提交表单-->>'+form.serialize());
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