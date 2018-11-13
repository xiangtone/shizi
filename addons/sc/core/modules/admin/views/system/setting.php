<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 10:14
 * @var \yii\web\View $this
 */

use \app\models\Option;

defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '系统设置';
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
        <div class="form-title">系统设置</div>
        <div class="form-body">
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class="col-form-label required">名称</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="text" name="name" value="<?= $store->name ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">小程序AppId</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="text" name="app_id" value="<?= $wechat_app->app_id ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">小程序AppSecret</label>
                </div>
                <div class="col-9">
                    <?php if ($wechat_app->app_secret): ?>
                        <div class="input-hide">
                            <input class="form-control" type="text" name="app_secret"
                                   value="<?= $wechat_app->app_secret ?>">
                            <div class="tip-block">已隐藏AppSecret，点击查看或编辑</div>
                        </div>
                    <?php else: ?>
                        <input class="form-control" type="text" name="app_secret"
                               value="<?= $wechat_app->app_secret ?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">微信支付商户号</label>
                </div>
                <div class="col-9">
                    <input autocomplete="off" class="form-control" type="text" name="mch_id"
                           value="<?= $wechat_app->mch_id ?>">
                    <div class="text-muted fs-sm">如果无需支付请填写0</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">微信支付Api密钥</label>
                </div>
                <div class="col-9">
                    <?php if ($wechat_app->key): ?>
                        <div class="input-hide">
                            <input class="form-control" type="text" name="key"
                                   value="<?= $wechat_app->key ?>">
                            <div class="tip-block">已隐藏Key，点击查看或编辑</div>
                        </div>
                    <?php else: ?>
                        <input class="form-control" type="text" name="key"
                               value="<?= $wechat_app->key ?>">
                    <?php endif; ?>
                    <div class="text-muted fs-sm">如果无需支付请填写0</div>

                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">微信支付apiclient_cert.pem</label>
                </div>
                <div class="col-9">
                    <?php if ($wechat_app->cert_pem): ?>
                        <div class="input-hide">
                            <textarea class="form-control" type="text"
                                      rows="3"
                                      placeholder="请将apiclient_cert.pem文件里面的内容粘贴到此处"
                                      name="cert_pem"><?= $wechat_app->cert_pem ?></textarea>
                            <div class="tip-block">已隐藏Key，点击查看或编辑</div>
                        </div>
                    <?php else: ?>
                        <textarea class="form-control" type="text"
                                  rows="3"
                                  placeholder="请将apiclient_cert.pem文件里面的内容粘贴到此处"
                                  name="cert_pem"><?= $wechat_app->cert_pem ?></textarea>
                    <?php endif; ?>
                    <div class="text-muted fs-sm">如果无需退款请填写0</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">微信支付apiclient_key.pem</label>
                </div>
                <div class="col-9">
                    <?php if ($wechat_app->key_pem): ?>
                        <div class="input-hide">
                            <textarea class="form-control" type="text"
                                      rows="3"
                                      placeholder="请将apiclient_cert.pem文件里面的内容粘贴到此处"
                                      name="key_pem"><?= $wechat_app->key_pem ?></textarea>
                            <div class="tip-block">已隐藏Key，点击查看或编辑</div>
                        </div>
                    <?php else: ?>
                        <textarea class="form-control" type="text"
                                  rows="3"
                                  placeholder="请将apiclient_key.pem文件里面的内容粘贴到此处"
                                  name="key_pem"><?= $wechat_app->key_pem ?></textarea>
                    <?php endif; ?>
                    <div class="text-muted fs-sm">如果无需退款请填写0</div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">联系电话</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="text" name="contact_tel"
                           value="<?= $store->contact_tel ?>">
                </div>
            </div>
            <!-- 客服开始 -->
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">开启在线客服</label>
                </div>
                <div class="col-9">
                    <div class="pt-1">
                        <label class="custom-control custom-radio">
                            <input id="radio1" <?= $store->show_customer_service == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="show_customer_service" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">开启</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radio2" <?= $store->show_customer_service == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="show_customer_service" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">关闭</span>
                        </label>
                        <label class=" col-form-label">
                    </div>
                </div>
                <div class="col-3 text-right">
                    <label class=" col-form-label">客服图标</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'customer_service_pic',
                        'value' => $store->customer_service_pic,
                        'width' => 100,
                        'height' => 100,
                    ]) ?>
                </div>
            </div>
            <!-- 客服结束 -->
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">底部版权图片</label>
                </div>
                <div class="col-9">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'copyright_pic_url',
                        'value' => $store->copyright_pic_url,
                        'width' => 120,
                        'height' => 60,
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">底部版权文字</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="copyright"
                           value="<?= $store->copyright ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">底部版权页面链接</label>
                </div>
                <div class="col-9">
                    <div class="input-group page-link-input">
                        <input class="form-control link-input"
                               name="copyright_url"
                               value="<?= $store->copyright_url ?>">
                        <span class="input-group-btn">
                            <a class="btn btn-secondary pick-link-btn" href="javascript:" open-type="navigate">选择链接</a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">是否开启取消预约</label>
                </div>
                <div class="col-9">
                    <div class="pt-1">
                        <label class="custom-control custom-radio">
                            <input id="radio1" <?= $store->refund == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="refund" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">开启</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radio2" <?= $store->refund == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="refund" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">关闭</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">是否开启类型标签</label>
                </div>
                <div class="col-9">
                    <div class="pt-1">
                        <label class="custom-control custom-radio style-1">
                            <input id="radio1" <?= $store->pic_style == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="pic_style" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">开启</span>
                        </label>
                        <label class="custom-control custom-radio style-1">
                            <input id="radio2" <?= $store->pic_style == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="pic_style" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">关闭</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row pic_icon" <?= $store->pic_style == 1 ? "" : "hidden" ?>>
                <div class="col-3 text-right">
                    <label class=" col-form-label">视频标签</label>
                </div>
                <div class="col-2">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'video_icon',
                        'value' => $store->video_icon,
                        'width' => 112,
                        'height' => 112,
                    ]) ?>
                </div>
                <div class="col-2 text-right">
                    <label class=" col-form-label">音频标签</label>
                </div>
                <div class="col-3">
                    <?= \app\widgets\ImageUpload::widget([
                        'name' => 'audio_icon',
                        'value' => $store->audio_icon,
                        'width' => 112,
                        'height' => 112,
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">是否开启会员功能</label>
                </div>
                <div class="col-9">
                    <div class="pt-1">
                        <label class="custom-control custom-radio">
                            <input id="radio1" <?= $store->member == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="member" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">开启</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radio2" <?= $store->member == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="member" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">关闭</span>
                        </label>
                    </div>
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
    $(document).on('click', '.style-1 .custom-control-input', function () {
        if ($(this).val() == 1) {
            $('.pic_icon').prop('hidden', false);
        } else {
            $('.pic_icon').prop('hidden', true);
        }
    });
</script>