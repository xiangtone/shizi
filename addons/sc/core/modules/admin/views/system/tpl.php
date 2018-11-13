<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21
 * Time: 9:55
 */
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
                    <label class="col-form-label">支付模板消息的模板ID</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="text" name="pay_tpl" value="<?=$list['pay_tpl']?>">
                    <div class="fs-sm">
                        <span>用户支付完成后向用户发送消息</span>
                        <a href="javascript:" data-toggle="modal" data-target="#pay_tpl">如何获取支付模板消息</a>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class="col-form-label">退款模板消息的模板ID</label>
                </div>
                <div class="col-9">
                    <input class="form-control" type="text" name="refund_tpl" value="<?=$list['refund_tpl']?>">
                    <div class="fs-sm">
                        <span>退款订单后台处理完成后向用户发送消息</span>
                        <a href="javascript:" data-toggle="modal" data-target="#refund_tpl">如何获取退款模板消息</a>
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
<!-- 支付模板消息教程 -->
<div class="modal fade" id="pay_tpl" data-backdrop="static" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">如何获取支付通知模板消息id</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ol class="pl-3">
                    <li>
                        <div>进入微信小程序官方后台，找到模板库</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/0.png">
                        </div>
                    </li>
                    <li>
                        <div>查找指定模板，点击选用</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pay_tpl/1.png">
                        </div>
                    </li>
                    <li>
                        <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pay_tpl/2.png">
                        </div>
                    </li>
                    <li>
                        <div>复制模板ID</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/pay_tpl/3.png">
                        </div>
                    </li>
                </ol>
            </div>
            <div class="modal-footer">
                <a href="javascript:" class="btn btn-secondary" data-dismiss="modal">关闭</a>
            </div>
        </div>
    </div>
</div>
<!-- 退款模板消息教程 -->
<div class="modal fade" id="refund_tpl" data-backdrop="static" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">如何获取退款通知模板消息id</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ol class="pl-3">
                    <li>
                        <div>进入微信小程序官方后台，找到模板库</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/0.png">
                        </div>
                    </li>
                    <li>
                        <div>查找指定模板，点击选用</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/refund_tpl/1.png">
                        </div>
                    </li>
                    <li>
                        <div>选择下图关键词，并按下图调好顺序；点击提交</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/refund_tpl/2.png">
                        </div>
                    </li>
                    <li>
                        <div>复制模板ID</div>
                        <div style="text-align: center">
                            <img style="max-width: 100%"
                                 src="<?= Yii::$app->request->baseUrl ?>/statics/images/tplmsg/refund_tpl/3.png">
                        </div>
                    </li>
                </ol>
            </div>
            <div class="modal-footer">
                <a href="javascript:" class="btn btn-secondary" data-dismiss="modal">关闭</a>
            </div>
        </div>
    </div>
</div>
