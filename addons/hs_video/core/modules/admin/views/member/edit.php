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
$this->title = '会员编辑';
$this->params['active_nav_group'] = 6;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/member/index']) ?>">会员设置</a>
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
                   aria-controls="level-edit" aria-selected="true">会员设置</a>
                <a class="nav-item nav-link" id="nav-content-edit" data-toggle="tab" href="#content-edit" role="tab"
                   aria-controls="content-edit" aria-selected="false">会员说明</a>
            </nav>

        </div>
        <div class="tab-content mt-4" id="nav-tabContent">
            <div class="tab-pane fade show active" id="level-edit" role="tabpanel" aria-labelledby="nav-level-edit">

                <form method="post" class="auto-submit-form" autocomplete="off"
                      data-return="<?= $urlManager->createUrl(['admin/member/index']) ?>">
                    <div class="form-body">
                        <input type="hidden" name="scene" value="edit">
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">会员描述</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="title" value="<?= $level->title ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">会员价格</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="price" type="number"
                                       value="<?= $level->price ?>">
                                <div class="text-muted fs-sm">用户购买会员的价格</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">续费价格</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="s_price" type="number"
                                       value="<?= $level->s_price ?>">
                                <div class="text-muted fs-sm">用户续费会员的价格</div>
                                <div class="text-muted fs-sm">填0或不填表示没有续费价格</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">会员时间</label>
                            </div>
                            <div class="col-5">
                                <div class="input-group">

                                    <input class="form-control" name="date" type="number"
                                           value="<?= $level->date ?>">
                                    <span class="input-group-addon">天</span>
                                </div>
                                <div class="text-muted fs-sm">用户享受会员的时间</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">排序</label>
                            </div>
                            <div class="col-5">
                                <input class="form-control" name="sort" type="number"
                                       value="<?= $level->sort ?$level->sort : 100 ?>">
                                <div class="text-muted fs-sm">按升序排列</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2 text-right">
                                <label class="col-form-label required">是否设为推荐</label>
                            </div>
                            <div class="col-5">
                                <div class="pt-1">
                                    <label class="custom-control custom-radio">
                                        <input id="radio1"
                                               value="1" <?= $level->is_groom == 1 ? "checked" : "" ?>
                                               name="is_groom" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">是</span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input id="radio2"
                                               value="0" <?= $level->is_groom == 0 ? "checked" : "" ?>
                                               name="is_groom" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">否</span>
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
            <div class="tab-pane fade show" id="content-edit" role="tabpanel" aria-labelledby="nav-content-edit">
                <form method="post" class="auto-submit-form" autocomplete="off">
                    <div class="form-body">
                        <div class="form-group row">
                            <div class="col-2 text-right required">
                                <label class="col-form-label">会员使用说明</label>
                            </div>
                            <div class="col-5">
                                    <textarea class="form-control" name="content"
                                              style="min-height: 200px;"><?=$content?></textarea>
                            </div>
                            <input type="hidden" name="scene" value="content">
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
        </div>
    </div>
</div>
