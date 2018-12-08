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
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>

<div class="main-body p-3" id="app">
    <form class="form auto-submit-form" method="post" autocomplete="off"
          data-return="<?= $urlManager->createUrl(['admin/video/cat']) ?>">
        <div class="form-title"><?= $this->title ?></div>
        <div class="form-body">

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
                        'width' => 300,
                        'height' => 300,
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label">分类排序</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="number" name="model[sort]" value="<?= $list['sort']?$list['sort']:100 ?>">
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
                            <input id="radio3" <?= $list['is_display'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="model[is_display]" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">是</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radio4" <?= $list['is_display'] == 0 ? 'checked' : null ?>
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
                            <input id="radio1" <?= $list['is_show'] == 1 ? 'checked' : null ?>
                                   value="1"
                                   name="model[is_show]" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">是</span>
                        </label>
                        <label class="custom-control custom-radio cover-show">
                            <input id="radio2" <?= $list['is_show'] == 0 ? 'checked' : null ?>
                                   value="0"
                                   name="model[is_show]" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">否</span>
                        </label>
                    </div>
                    <div class="text-danger fs-sm">需要在自定义设置中开启才能展示</div>
                </div>
            </div>
            <div class="form-group row cover" <?=$list['is_show'] == 1?"":"hidden"?>>
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
    $(document).on('click','.cover-show',function(){
        var is_show = $(this).children('input').val();
        if(is_show == 1){
            $('.cover').prop('hidden',false);
        }else{
            $('.cover').prop('hidden',true);
        }
    });
</script>


