<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 20:20
 */
defined('YII_RUN') or exit('Access Denied');

$urlManager = Yii::$app->urlManager;
$this->title = '上传设置';
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

<div class="main-body p-3" id="app">
    <form class="form auto-submit-form" method="post" autocomplete="off"
          data-return="<?= $urlManager->createUrl(['admin/system/upload']) ?>">
        <div class="form-title"><?= $this->title ?></div>
        <div class="form-body">

            <div class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">上传存储方式</label>
                </div>
                <div class="col-9">
                    <select class="form-control" name="storage_type" v-model="storage_type">
                        <option value="">无（当前服务器）</option>
                        <option value="aliyun">阿里云OSS</option>
                        <option value="qcloud" hidden>腾讯云COS</option>
                        <option value="qiniu">七牛云存储</option>
                    </select>
                </div>
            </div>

            <div v-bind:hidden="storage_type!='aliyun'?true:false" class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">阿里云OSS配置</label>
                </div>
                <div class="col-9">
                    <label>存储空间名称（Bucket）</label>
                    <input class="form-control mb-3" name="aliyun[bucket]"
                           value="<?= $model->aliyun['bucket'] ?>">
                    <label>Endpoint（或自定义域名）</label>
                    <input class="form-control" name="aliyun[domain]"
                           value="<?= $model->aliyun['domain'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：http://oss-cn-hangzhou.aliyuncs.com，<span class="text-danger">请加上http://或https://，结尾不需要/</span></div>
                    <label>是否开启自定义域名</label>
                    <div>
                        <label class="custom-control custom-radio">
                            <input id="radio1"
                                   value="0" <?=$model->aliyun['CNAME'] == 0?"checked":""?>
                                   name="aliyun[CNAME]" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">否</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radio2"
                                   value="1" <?=$model->aliyun['CNAME'] == 1?"checked":""?>
                                   name="aliyun[CNAME]" type="radio" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">是</span>
                        </label>

                    </div>

                    <label>Access Key ID</label>
                    <input class="form-control mb-3" name="aliyun[access_key]"
                           value="<?= $model->aliyun['access_key'] ?>">
                    <label>	Access Key Secret</label>
                    <input class="form-control mb-3" name="aliyun[secret_key]"
                           value="<?= $model->aliyun['secret_key'] ?>">
                    <label>图片样式接口（选填）</label>
                    <input class="form-control" name="aliyun[style_api]"
                           value="<?= $model->aliyun['style_api'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：sample.jpg?x-oss-process=style/stylename</div>
                </div>
            </div>

            <div v-bind:hidden="storage_type!='qcloud'?true:false" class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">腾讯云COS配置</label>
                </div>
                <div class="col-9">
                    <input class="form-control" name="qcloud[]" value="">
                </div>
            </div>

            <div v-bind:hidden="storage_type!='qiniu'?true:false" class="form-group row">
                <div class="col-3 text-right">
                    <label class=" col-form-label required">七牛云存储配置</label>
                </div>
                <div class="col-9">
                    <label>存储空间名称（Bucket）</label>
                    <input class="form-control mb-3" name="qiniu[bucket]"
                           value="<?= $model->qiniu['bucket'] ?>">
                    <label>绑定域名（或测试域名）</label>
                    <input class="form-control" name="qiniu[domain]"
                           value="<?= $model->qiniu['domain'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：http://abstehdsdw.bkt.clouddn.com，<span class="text-danger">请加上http://或https://，结尾不需要/</span></div>
                    <label>AccessKey（AK）</label>
                    <input class="form-control mb-3" name="qiniu[access_key]"
                           value="<?= $model->qiniu['access_key'] ?>">
                    <label>SecretKey（SK）</label>
                    <input class="form-control mb-3" name="qiniu[secret_key]"
                           value="<?= $model->qiniu['secret_key'] ?>">
                    <label>图片样式接口（选填）</label>
                    <input class="form-control" name="qiniu[style_api]"
                           value="<?= $model->qiniu['style_api'] ?>">
                    <div class="text-muted fs-sm mb-3">例子：imageView2/0/w/1080/h/1080/q/85|imageslim</div>
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
    var app = new Vue({
        el: "#app",
        data: {
            storage_type: "<?=$model->storage_type ?>",
        },
    });
</script>
