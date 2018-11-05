<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 16:09
 */
use \app\models\Option;
defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '短信通知';
$this->params['active_nav_group'] = 1;
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
<div class="main-body p-3">
    <form class="form auto-submit-form" method="post" autocomplete="off">
        <div class="form-title">短信通知</div>
        <div class="form-body">
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">是否开启短信验证</label>
                </div>
                <div class="col-9">
                    <div class="pt-1">
                        <label class="custom-control custom-radio">
                            <input id="radio1" <?= $sms_setting->status == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="status" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">开启</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radio2" <?= $sms_setting->status == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="status" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">关闭</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">阿里云AccessKeyId</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="AccessKeyId"
                           value="<?= $sms_setting->AccessKeyId ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">阿里云AccessKeySecret</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="AccessKeySecret"
                           value="<?= $sms_setting->AccessKeySecret ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">模板签名</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="sign"
                           value="<?= $sms_setting->sign ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">模板名称</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="name"
                           value="<?= $sms_setting->name ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">模板ID</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="tpl"
                           value="<?= $sms_setting->tpl ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">模板变量</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="msg"
                           value="<?= $sms_setting->msg ?>">
                    <div class="text-muted fs-sm">例如：“模板内容: 您的验证码为${code}，请勿告知他人。”，则填写“code”</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-9 offset-sm-3">
                    <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
                    <div class="text-success form-success mb-3" style="display: none">成功信息</div>
                    <a class="btn btn-primary submit-btn" href="javascript:">保存</a>
                </div>
            </div>
        </div>
    </form>
</div>
<script>

</script>
