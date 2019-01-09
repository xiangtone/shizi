<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 20:43
 * @var \app\models\Cat $list
 */
defined('YII_RUN') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '分类编辑';
$this->params['active_nav_group'] = 2;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/video/cat']) ?>">分类管理</a>
            <span class="breadcrumb-item active">
                <?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3" id="app">
    <div class="form-title" style="border: 0;">
        <nav class="nav nav-tabs" id="myTab" role="tablist">
            <a class="nav-item nav-link active" id="nav-level-edit" data-toggle="tab" href="#level-edit" role="tab"
                aria-controls="level-edit" aria-selected="true">
                <?= $this->title ?></a>
            <a class="nav-item nav-link is_pay" id="nav-pay-edit" data-toggle="tab" href="#pay-edit" role="tab"
                aria-controls="pay-edit" aria-selected="false" <?= $list['is_display'] == 0 ? "hidden" : "" ?>>付费设置</a>
        </nav>
    </div>
    <form class="form auto-submit-form" method="post" autocomplete="off" data-return="<?= $urlManager->createUrl(['admin/video/cat']) ?>">
        <div class="form-body">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="level-edit" role="tabpanel" aria-labelledby="nav-level-edit">
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">分类名称</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="model[name]" value="<?= $list['name'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">封面图片</label>
                        </div>
                        <div class="col-9">
                            <?= \app\widgets\ImageUpload::widget([
                        'name' => 'model[pic_url]',
                        'value' => $list['pic_url'],
                        'width' => 360,
                        'height' => 200,
                    ]) ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">分类排序</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="number" name="model[sort]" value="<?= $list['sort'] ? $list['sort'] : 100 ?>">
                            <div class="text-danger">按照升序排列</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">是否显示</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio">
                                    <input id="radio3" <?=$list['is_display']==1 ? 'checked' : null ?>
                                    value="1"
                                    name="model[is_display]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">是</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input id="radio4" <?=$list['is_display']==0 ? 'checked' : null ?>
                                    value="0"
                                    name="model[is_display]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">否</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">是否放置首页</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio cover-show">
                                    <input id="radio1" <?=$list['is_show']==1 ? 'checked' : null ?>
                                    value="1"
                                    name="model[is_show]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">是</span>
                                </label>
                                <label class="custom-control custom-radio cover-show">
                                    <input id="radio2" <?=$list['is_show']==0 ? 'checked' : null ?>
                                    value="0"
                                    name="model[is_show]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">否</span>
                                </label>
                            </div>
                            <div class="text-danger fs-sm">需要在自定义设置中开启才能展示</div>
                        </div>
                    </div>
                    <div class="form-group row cover" <?=$list['is_show']==1 ? "" : "hidden" ?>>
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">首页缩略图</label>
                        </div>
                        <div class="col-9">
                            <?= \app\widgets\ImageUpload::widget([
                        'name' => 'model[cover_url]',
                        'value' => $list['cover_url'],
                        'width' => 246,
                        'height' => 246,
                    ]) ?>
                        </div>
                    </div>
                </div>
                <!-- 付费 -->
                <div class="tab-pane fade show" id="pay-edit" role="tabpanel" aria-labelledby="nav-pay-edit" >
                    <div class="form-group row is_pay" >
                        <div class="col-3 text-right">
                            <label class=" col-form-label">是否开启付费观看</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio is_pay">
                                    <input id="radio1" <?=$list['is_pay']==1 ? 'checked' : null ?>
                                    value="1"
                                    name="model[is_pay]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">开启</span>
                                </label>
                                <label class="custom-control custom-radio is_pay">
                                    <input id="radio2" <?=$list['is_pay']==0 ? 'checked' : null ?>
                                    value="0"
                                    name="model[is_pay]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">关闭</span>
                                </label>
                            </div>
                        </div>
                        <div class="fs-sm"></div>
                    </div>
                    <!--视频付费设置-->
                    <div class="pay" <?=$list['is_pay']==1 ? "" : "hidden" ?>>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class="col-form-label">付费金额</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input class="form-control" type="number" name="pay[pay_price]" value="<?= $pay['pay_price'] ? $pay['pay_price'] : 0 ?>">
                                    <span class="input-group-addon">元</span>
                                </div>
                                <div class="fs-sm text-danger">付费金额不支持退款</div>
                            </div>
                        </div>


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
    $(document).on('click', '.cover-show', function () {
        var is_show = $(this).children('input').val();
        if (is_show == 1) {
            $('.cover').prop('hidden', false);
        } else {
            $('.cover').prop('hidden', true);
        }
    });
</script>
<script>
    //是否开启付费观看
    $(document).on('click', '.is_pay .custom-control-input', function () {
        if ($(this).val() == 1) {
            $('.pay').prop('hidden', false)
        } else {
            $('.pay').prop('hidden', true)
        }
    });
</script>