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
$this->title = '组句编辑';
$this->params['active_nav_group'] = 2;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/game/sentence-list', 'video_id' => $video_id]) ?>">组句列表</a>
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
                   aria-controls="level-edit" aria-selected="true">组句编辑</a>

            </nav>

        </div>
        <div class="tab-content mt-4" id="nav-tabContent">
            <div class="tab-pane fade show active" id="level-edit" role="tabpanel" aria-labelledby="nav-level-edit">

                <form method="post" class="auto-submit-form" autocomplete="off"
                      data-return="<?= $urlManager->createUrl(['admin/game/sentence-list', 'video_id' => $video_id]) ?>">
                    <div class="form-body">
                        <input type="hidden" name="scene" value="edit">
                        <div class="form-group row">
                            <!-- <div class="col-2 text-right">
                                <label class="col-form-label required">视频id</label>
                            </div> -->
                            <div class="col-5">
                                <input readonly  unselectable="on" type="hidden"  class="form-control" name="video_id" value="<?= $video_id ?>">

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">分段1</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="segment1" value="<?= $exSentence->segment1 ? $exSentence->segment1 : '' ?>">

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">分段2</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="segment2" value="<?= $exSentence->segment2 ? $exSentence->segment2 : '' ?>">

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">分段3</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="segment3" value="<?= $exSentence->segment3 ? $exSentence->segment3 : '' ?>">

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">分段4</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="segment4" value="<?= $exSentence->segment4 ? $exSentence->segment4 : '' ?>">

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class=" col-form-label required">多媒体链接</label>
                            </div>
                            <div class="col-9">
                            <div class="video-picker" data-url="<?= $urlManager->createUrl(['upload/video']) ?>">
                                <div class="input-group">
                                    <input class="video-picker-input video form-control" name="voice_url"
                                           value="<?= $exSentence->voice_url ? $exSentence->voice_url:'' ?>" placeholder="请输入多媒体链接源地址或者选择上传多媒体">
                                    <a href="javascript:" class="btn btn-secondary video-picker-btn">选择多媒体</a>
                                </div>
                                <a class="video-check"
                                   href="<?= $exSentence->voice_url ? $exSentence->voice_url: "javascript:" ?>"
                                   target="_blank">预览</a>
                                <video id="myVideo" hidden
                                       src="<?= $exSentence->voice_url ? $exSentence->voice_url: "" ?>"></video>
                                <div class="video-preview"></div>
                                <div><span class="text-info">支持格式MP3;不支持中文名文件上传</span></div>
                                <div class="text-danger video-type-error"></div>
                            </div>
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

        if (!$("input[name='segment1']").val()) {
            btn.btnReset();
            $.myAlert({
                content: '请输入分段1'
            });
            return;
        }
        if (!$("input[name='segment2']").val()) {
            btn.btnReset();
            $.myAlert({
                content: '请输入分段2'
            });
            return;
        }
        if (!$("input[name='segment3']").val()) {
            btn.btnReset();
            $.myAlert({
                content: '请输入分段3'
            });
            return;
        }
        if (!$("input[name='segment4']").val()) {
            btn.btnReset();
            $.myAlert({
                content: '请输入分段4'
            });
            return;
        }
        /*
        if (!$("input[name='voice_url']").val()) {
            btn.btnReset();
            $.myAlert({
                content: '请输入音频'
            });
            return;
        }
        */
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