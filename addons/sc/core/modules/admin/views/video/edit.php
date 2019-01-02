<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 19:37
 */
defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '内容编辑';
$this->params['active_nav_group'] = 2;
?>
<link href="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" rel="stylesheet">
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/system/index']) ?>">系统</a>
            <a class="breadcrumb-item" href="<?= $urlManager->createUrl(['admin/video/list']) ?>">视频列表</a>
            <span class="breadcrumb-item active"><?= $this->title ?></span>
        </nav>
    </div>
    <div>
        <?= $this->render('/layouts/nav-right') ?>
    </div>
</div>
<div class="main-body p-3 bg-white" id="app">
    <div class="form-title" style="border: 0;">
        <nav class="nav nav-tabs" id="myTab" role="tablist">
            <a class="nav-item nav-link active" id="nav-level-edit" data-toggle="tab" href="#level-edit" role="tab"
               aria-controls="level-edit" aria-selected="true"><?= $this->title ?></a>
            <!-- <a class="nav-item nav-link" id="nav-content-edit" data-toggle="tab" href="#content-edit" role="tab"
               aria-controls="content-edit" aria-selected="false">预约设置</a> -->
            <a class="nav-item nav-link is_pay" id="nav-pay-edit" data-toggle="tab" href="#pay-edit" role="tab"
               aria-controls="pay-edit" aria-selected="false" <?= $list['style'] == 2 ? "hidden" : "" ?>>付费设置</a>
        </nav>

    </div>

    <form class="auto-submit-form-1 mt-4" method="post" autocomplete="off" style="max-width: 70rem;"
          data-return="<?= $urlManager->createUrl(['admin/video/list']) ?>">
        <div class="form-body">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="level-edit" role="tabpanel" aria-labelledby="nav-level-edit">
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">分类</label>
                        </div>
                        <div class="col-9">
                            <select class="form-control" name="model[cat_id]" size="4">
                                <?php foreach ($cat_list as $index => $value): ?>
                                    <option
                                        value="<?= $value['id'] ?>" <?= ($list['cat_id'] == $value['id']) ? "selected" : "" ?>><?= $value['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">标题</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" type="text" name="model[title]" value="<?= $list['title'] ?>">
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
                                'width' => 750,
                                'height' => 420,
                            ]) ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">类型</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio style">
                                    <input id="radio2" <?= $list['style'] == 0 ? 'checked' : null ?>
                                           value="0"
                                           name="model[style]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">视频</span>
                                </label>
                                <label class="custom-control custom-radio style">
                                    <input id="radio1" <?= $list['style'] == 1 ? 'checked' : null ?>
                                           value="1"
                                           name="model[style]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">音频</span>
                                </label>
                                <label class="custom-control custom-radio style">
                                    <input id="radio1" <?= $list['style'] == 2 ? 'checked' : null ?>
                                           value="2"
                                           name="model[style]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">文章</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row type-hide" <?= $list['style'] == 0 ? "" : "hidden" ?>>
                        <div class="col-3 text-right">
                            <label class=" col-form-label">视频来源</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio type">
                                    <input id="radio2" <?= $list['type'] == 0 ? 'checked' : null ?>
                                           value="0"
                                           name="model[type]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">源地址</span>
                                </label>
                                <label class="custom-control custom-radio type">
                                    <input id="radio1" <?= $list['type'] == 1 ? 'checked' : null ?>
                                           value="1"
                                           name="model[type]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">腾讯</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row style-2" <?= $list['style'] == 2 ? "hidden" : "" ?>>
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">多媒体480P链接</label>
                        </div>
                        <div class="col-9">
                            <div class="video-picker" data-url="<?= $urlManager->createUrl(['upload/video']) ?>">
                                <div class="input-group">
                                    <input class="video-picker-input video form-control" name="model[video_url]"
                                           value="<?= $list['video_url'] ?>" placeholder="请输入多媒体链接源地址或者选择上传多媒体">
                                    <a href="javascript:" class="btn btn-secondary video-picker-btn">选择多媒体</a>
                                </div>
                                <!-- <a class="video-check"
                                   href="<?= $list['video_url'] ? $list['video_url'] : "javascript:" ?>"
                                   target="_blank">预览</a> -->
                                <video id="myVideo" hidden
                                       src="<?= $list['video_url'] ? $list['video_url'] : "" ?>"></video>
                                <div class="video-preview"></div>
                                <div><span class="text-info">支持格式MP4，MP3;支持编码H.264;不支持中文名文件上传</span></div>
                                <div class="text-danger video-type-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row style-2" <?= $list['style'] == 2 ? "hidden" : "" ?>>
                        <div class="col-3 text-right">
                            <label class=" col-form-label">720P链接</label>
                        </div>
                        <div class="col-9">
                            <div class="video-picker" data-url="<?= $urlManager->createUrl(['upload/video']) ?>">
                                <div class="input-group">
                                    <input class="video-picker-input video form-control" name="model[video_720]"
                                           value="<?= $list['video_720'] ?>" placeholder="请输入多媒体链接源地址或者选择上传多媒体">
                                    <a href="javascript:" class="btn btn-secondary video-picker-btn">选择多媒体</a>
                                </div>
                                <!-- <a class="video-check"
                                   href="<?= $list['video_720'] ? $list['video_720'] : "javascript:" ?>"
                                   target="_blank">预览</a> -->
                                <video id="myVideo" hidden
                                       src="<?= $list['video_720'] ? $list['video_720'] : "" ?>"></video>
                                <div class="video-preview"></div>
                                <div><span class="text-info">支持格式MP4，MP3;支持编码H.264;不支持中文名文件上传</span></div>
                                <div class="text-danger video-type-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row style-2" <?= $list['style'] == 2 ? "hidden" : "" ?>>
                        <div class="col-3 text-right">
                            <label class=" col-form-label">1080P链接</label>
                        </div>
                        <div class="col-9">
                            <div class="video-picker" data-url="<?= $urlManager->createUrl(['upload/video']) ?>">
                                <div class="input-group">
                                    <input class="video-picker-input video form-control" name="model[video_1080]"
                                           value="<?= $list['video_1080'] ?>" placeholder="请输入多媒体链接源地址或者选择上传多媒体">
                                    <a href="javascript:" class="btn btn-secondary video-picker-btn">选择多媒体</a>
                                </div>
                                <!-- <a class="video-check"
                                   href="<?= $list['video_1080'] ? $list['video_1080'] : "javascript:" ?>"
                                   target="_blank">预览</a> -->
                                <video id="myVideo" hidden
                                       src="<?= $list['video_1080'] ? $list['video_1080'] : "" ?>"></video>
                                <div class="video-preview"></div>
                                <div><span class="text-info">支持格式MP4，MP3;支持编码H.264;不支持中文名文件上传</span></div>
                                <div class="text-danger video-type-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row style-2" <?= $list['style'] == 2 ? "hidden" : "" ?>>
                        <div class="col-3 text-right">
                            <label class="col-form-label">时长</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group">
                                <input class="video_time form-control" type="number" readonly name="model[video_time]"
                                       value="<?= $list['video_time'] ? $list['video_time'] : "" ?>">
                                <span class="input-group-addon">秒</span>
                                <a href="javascript:" class="btn btn-secondary hand-btn">手动填写</a>
                            </div>
                            <div class="text-danger video-time-error"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label required">简介(生字)</label>
                        </div>
                        <div class="col-9">
                    <textarea class="form-control" name="model[content]"
                              style="min-height: 150px;"><?= $list['content'] ?></textarea>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">浏览量</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" name="model[page_view]" type="number"
                                   value="<?= $list['page_view'] ? $list['page_view'] : 100 ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class="col-form-label">排序</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" name="model[sort]" type="number"
                                   value="<?= $list['sort'] ? $list['sort'] : 100 ?>">
                            <div class="text-danger">按照升序排列</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">图文详情</label>
                        </div>
                        <div class="col-9">
                            <textarea class="short-row" id="editor"
                                      name="model[detail]"><?= $list['detail'] ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row" hidden>
                        <div class="col-3 text-right">
                            <label class="col-form-label">是否设为轮播</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio">
                                    <input id="radio1" <?= $list['is_show'] == 1 ? 'checked' : null ?>
                                           value="1"
                                           name="model[is_show]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">是</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input id="radio2" <?= $list['is_show'] == 0 ? 'checked' : null ?>
                                           value="0"
                                           name="model[is_show]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">否</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" <?= $list['is_show'] == 1 ? "" : "hidden" ?> hidden>
                        <div class="col-3 text-right">
                            <label class="col-form-label">介绍</label>
                        </div>
                        <div class="col-9">
                            <input class="form-control" name="model[introduce]" value="<?= $list['introduce'] ?>">
                            <div></div>
                        </div>
                    </div>
                    <div class="form-group row" <?= $list['is_show'] == 1 ? "" : "hidden" ?> hidden>
                        <div class="col-3 text-right">
                            <label class=" col-form-label required">轮播图</label>
                        </div>
                        <div class="col-9">
                            <?= \app\widgets\ImageUpload::widget([
                                'name' => 'model[banner_url]',
                                'value' => $list['banner_url'],
                                'width' => 750,
                                'height' => 590,
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show" id="content-edit" role="tabpanel" aria-labelledby="nav-content-edit"><div class="form-group row">
                        <div class="col-3 text-right">
                            <label class=" col-form-label">是否开启预约</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio order">
                                    <input id="radio1" <?= $list['order'] == 1 ? 'checked' : null ?>
                                           value="1"
                                           name="model[order]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">开启</span>
                                </label>
                                <label class="custom-control custom-radio order">
                                    <input id="radio2" <?= $list['order'] == 0 ? 'checked' : null ?>
                                           value="0"
                                           name="model[order]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">关闭</span>
                                </label>
                            </div>
                        </div>
                        <div></div>
                    </div>

                    <div class="form-group row introduce" <?= $list['order'] == 1 ? "" : "hidden" ?>>
                        <div class="col-3 text-right">
                            <label class="col-form-label">预定金额</label>
                        </div>
                        <div class="col-9">
                            <div class="input-group">
                                <input class="form-control" type="number" name="model[money]"
                                       value="<?= $list['money'] ? $list['money'] : 0 ?>">
                                <span class="input-group-addon">元</span>
                            </div>
                            <div>预定金额为0则不用支付</div>
                        </div>
                    </div>
                    <div class="form-group row introduce" <?= $list['order'] == 1 ? "" : "hidden" ?>>
                        <div class="col-3 text-right">
                            <label class=" col-form-label">是否开启取消预约</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio">
                                    <input id="radio1" <?= $list['refund'] == 1 ? 'checked' : null ?>
                                           value="1"
                                           name="model[refund]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">开启</span>
                                </label>
                                <label class="custom-control custom-radio">
                                    <input id="radio2" <?= $list['refund'] == 0 ? 'checked' : null ?>
                                           value="0"
                                           name="model[refund]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">关闭</span>
                                </label>
                            </div>
                        </div>
                        <div class="fs-sm"></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3 text-right required">
                            <label class="col-form-label">表单名称</label>
                        </div>
                        <div class="col-9">
                            <input type="text" class="form-control"
                                   name="model[form_name]" value="<?= $form_name ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 text-right required">
                            <label class="col-form-label">自定义表单</label>
                        </div>
                        <div class="col-9">
                            <table class="table">
                                <thead>
                                <tr>
                                    <td>类型</td>
                                    <td>名称</td>
                                    <td>必填</td>
                                    <td>设置</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <col style="width: 15%;">
                                <col style="width: 15%;">
                                <col style="width: 10%;">
                                <col style="width: 30%;">
                                <col style="width: 30%;">
                                <tbody>
                                <template v-for="(item,index) in form_list">
                                    <tr v-if="item.type == 'text'">
                                        <td>
                                            单行文本
                                            <input type="hidden" v-model="item.id" class="form-control"
                                                   :name="'model[form_list]['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'model[form_list]['+index+'][type]'">
                                        </td>
                                        <td><input type="text" v-model="item.name" class="form-control"
                                                   :name="'model[form_list]['+index+'][name]'"></td>
                                        <td><input class="re" :data-index="index" type="checkbox" value="1"
                                                   :checked="item.required==1"
                                                   :name="'model[form_list]['+index+'][required]'"></td>
                                        <td>
                                            <div class="mb-2">
                                                <span class="mr-2">设置默认值</span><input type="text" v-model="item.default"
                                                                                      class="form-control"
                                                                                      :name="'model[form_list]['+index+'][default]'">
                                            </div>
                                            <div class="mb-2">
                                                <span class="mr-2">提示语</span><input type="text" v-model="item.tip"
                                                                                    class="form-control"
                                                                                    :name="'model[form_list]['+index+'][tip]'">
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary form-prev" :data-index="index"
                                               v-if="index>0"
                                               href="javascript:">上移</a>
                                            <a class="btn btn-sm btn-primary form-next" :data-index="index"
                                               v-if="index<form_list.length-1"
                                               href="javascript:">下移</a>
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                        </td>
                                    </tr>
                                    <tr v-if="item.type == 'textarea'">
                                        <td>
                                            多行文本
                                            <input type="hidden" v-model="item.id" class="form-control"
                                                   :name="'model[form_list]['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'model[form_list]['+index+'][type]'">
                                        </td>
                                        <td><input type="text" v-model="item.name" class="form-control"
                                                   :name="'model[form_list]['+index+'][name]'"></td>
                                        <td><input class="re" :data-index="index" type="checkbox" value="1"
                                                   :checked="item.required==1"
                                                   :name="'model[form_list]['+index+'][required]'"></td>
                                        <td>
                                            <div class="mb-2">
                                                <span class="mr-2">设置默认值</span><input type="text" v-model="item.default"
                                                                                      class="form-control"
                                                                                      :name="'model[form_list]['+index+'][default]'">
                                            </div>
                                            <div class="mb-2">
                                                <span class="mr-2">提示语</span><input type="text" v-model="item.tip"
                                                                                    class="form-control"
                                                                                    :name="'model[form_list]['+index+'][tip]'">
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary form-prev" :data-index="index"
                                               v-if="index>0"
                                               href="javascript:">上移</a>
                                            <a class="btn btn-sm btn-primary form-next" :data-index="index"
                                               v-if="index<form_list.length-1"
                                               href="javascript:">下移</a>
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                        </td>
                                    </tr>
                                    <tr v-if="item.type == 'time'">
                                        <td>
                                            时间选择器
                                            <input type="hidden" v-model="item.id" class="form-control"
                                                   :name="'model[form_list]['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'model[form_list]['+index+'][type]'">
                                        </td>
                                        <td><input type="text" v-model="item.name" class="form-control"
                                                   :name="'model[form_list]['+index+'][name]'"></td>
                                        <td><input class="re" :data-index="index" type="checkbox" value="1"
                                                   :checked="item.required==1"
                                                   :name="'model[form_list]['+index+'][required]'"></td>
                                        <td>
                                            <div class="mb-2">
                                                <span class="mr-2">设置默认值</span><input type="time" v-model="item.default"
                                                                                      class="form-control "
                                                                                      :name="'model[form_list]['+index+'][default]'">
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary form-prev" :data-index="index"
                                               v-if="index>0"
                                               href="javascript:">上移</a>
                                            <a class="btn btn-sm btn-primary form-next" :data-index="index"
                                               v-if="index<form_list.length-1"
                                               href="javascript:">下移</a>
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                        </td>
                                    </tr>
                                    <tr v-if="item.type == 'date'">
                                        <td>
                                            日期选择器
                                            <input type="hidden" v-model="item.id" class="form-control"
                                                   :name="'model[form_list]['+index+'][id]'">
                                            <input type="hidden" v-model="item.type"
                                                   :name="'model[form_list]['+index+'][type]'">
                                        </td>
                                        <td><input type="text" v-model="item.name" class="form-control"
                                                   :name="'model[form_list]['+index+'][name]'"></td>
                                        <td><input class="re" :data-index="index" type="checkbox" value="1"
                                                   :checked="item.required==1"
                                                   :name="'model[form_list]['+index+'][required]'"></td>
                                        <td>
                                            <div class="mb-2">
                                                <span class="mr-2">设置默认值</span><input type="date" v-model="item.default"
                                                                                      class="form-control "
                                                                                      :name="'model[form_list]['+index+'][default]'">
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-primary form-prev" :data-index="index"
                                               v-if="index>0"
                                               href="javascript:">上移</a>
                                            <a class="btn btn-sm btn-primary form-next" :data-index="index"
                                               v-if="index<form_list.length-1"
                                               href="javascript:">下移</a>
                                            <a class="btn btn-sm btn-danger form-del" :data-index="index"
                                               href="javascript:">删除</a>
                                        </td>
                                    </tr>
                                </template>
                                <template>
                                    <tr>
                                        <td colspan="2">
                                            <select class="form-control form-add-type">
                                                <option value="text">单行文本</option>
                                                <option value="textarea">多行文本</option>
                                                <option value="time">时间选择器</option>
                                                <option value="date">日期选择器</option>
                                            </select>
                                        </td>
                                        <td colspan="2" style="text-align: right">
                                            <a class="btn btn-sm btn-primary form-add" href="javascript:">添加一个字段</a>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="pay-edit" role="tabpanel" aria-labelledby="nav-pay-edit">
                    <div class="form-group row is_pay" <?= $list['style'] == 2 ? "hidden" : "" ?>>
                        <div class="col-3 text-right">
                            <label class=" col-form-label">是否开启付费观看</label>
                        </div>
                        <div class="col-9">
                            <div class="pt-1">
                                <label class="custom-control custom-radio is_pay">
                                    <input id="radio1" <?= $list['is_pay'] == 1 ? 'checked' : null ?>
                                           value="1"
                                           name="model[is_pay]" type="radio" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">开启</span>
                                </label>
                                <label class="custom-control custom-radio is_pay">
                                    <input id="radio2" <?= $list['is_pay'] == 0 ? 'checked' : null ?>
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
                    <div class="pay" <?= $list['is_pay'] == 1 ? "" : "hidden" ?>>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class="col-form-label">付费金额</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input class="form-control" type="number" name="pay[pay_price]"
                                           value="<?= $pay['pay_price'] ? $pay['pay_price'] : 0 ?>">
                                    <span class="input-group-addon">元</span>
                                </div>
                                <div class="fs-sm text-danger">付费金额不支持退款</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label class="col-form-label">免费时长</label>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input class="form-control" type="number" name="pay[pay_time]"
                                           value="<?= $pay['pay_time'] ? $pay['pay_time'] : 0 ?>">
                                    <span class="input-group-addon">秒</span>
                                </div>
                                <div class="fs-sm text-danger">免费可观看视频的时长</div>
                            </div>
                        </div>
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
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.config.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/ueditor/ueditor.all.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script>
    var ue = UE.getEditor('editor', {
        serverUrl: "<?=$urlManager->createUrl(['upload/ue'])?>",
        enableAutoSave: false,
        saveInterval: 1000 * 3600,
        enableContextMenu: false,
        autoHeightEnabled: false,
    });
</script>
<script>
    (function () {
        $.datetimepicker.setLocale('zh');
        $("input[name='add_time_begin']").datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: $("input[name='add_time_end']").val() ? $("input[name='add_time_end']").val() : false
                })
            },
            timepicker: false,
        });
    })();
</script>
<script>
    var app = new Vue({
        el: "#app",
        data: {
            form_list: <?=$form_list?>
        }
    });
</script>
<script>
    $(document).on('click', '.order .custom-control-input', function () {
        if ($(this).val() == 1) {
            $('.introduce').prop('hidden', false)
        } else {
            $('.introduce').prop('hidden', true)
        }
    });
    $(document).on('change', '.video', function () {
        var type = $("input[name='model[type]']:checked").val();
        var url = this.value;
        if (type == 1) {
            url = getvideo(url);
        }
        $('.video-check').attr('href', url);
        $('#myVideo').prop('src', url);
        $('.video-time-error').prop('hidden', false).html('视频时长获取中，请稍后...');
        var int = setInterval(function () {
            var time = document.getElementById('myVideo').duration;
            if (time && time != 'NaN') {
                $('.video_time').val(time);
                $('.video-time-error').prop('hidden', true);
                window.clearInterval(int);
            }
        }, 1000);
    });
    $(document).on('click', '.type .custom-control-input', function () {
        var type = $(this).val();
        var url = $('.video').val();
        if (type == 1) {
            url = getvideo(url);
        }
        $('.video-check').attr('href', url);
        $('#myVideo').prop('src', url);
        $('.video-time-error').prop('hidden', false).html('视频时长获取中，请稍后...');
        var int = setInterval(function () {
            var time = document.getElementById('myVideo').duration;
            if (time && time != 'NaN') {
                $('.video_time').val(time);
                $('.video-time-error').prop('hidden', true);
                window.clearInterval(int);
            }
        }, 1000);
    });
    function getvideo(url) {
        $.ajax({
            url: "<?=$urlManager->createUrl(['admin/video/get-video'])?>",
            type: 'get',
            dataType: 'json',
            async: false,
            data: {
                url: url
            },
            success: function (res) {
                if (res.code == 0) {
                    url = res.url
                } else {
                    $('.video-type-error').prop('hidden', false).html(res.msg);
                }
            }
        });
        return url;
    }
    $(document).ready(function () {
        function time() {
            var url = $('.video');
            if (url.val() == '') {
                return true;
            }
            if ($('.video_time').val() != '') {
                return true;
            }
            $('#myVideo').prop('src', url.val());
            $('.video-time-error').prop('hidden', false).html('视频时长获取中，请稍后...');
            var int = setInterval(function () {
                var time = document.getElementById('myVideo').duration;
                if (time && time != 'NaN') {
                    $('.video_time').val(time);
                    $('.video-time-error').prop('hidden', true);
                    window.clearInterval(int);
                }
            }, 1000);
        }

        time();
    });
    $(document).on('click', '.hand-btn', function () {
        $('.video_time').attr('readonly', false);
    })
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
        var form_list = app.form_list;
        var is_submit = true;
        for (var i in form_list) {
            if (!form_list[i].name || form_list[i] == undefined) {
                is_submit = false;
                break;
            }
        }
        if (!is_submit) {
            btn.btnReset();
            $.myAlert({
                content: '请填写字段名称'
            });
            return;
        }
        $.ajax({
            url: form.attr("action"),
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
<script>
    $(document).on('click', '.form-del', function () {
        var index = $(this).data('index');
        app.form_list.splice(index, 1);
    });
    $(document).on('click', '.form-prev', function () {
        var index = $(this).data('index');
        if (index == 0) {
            return;
        }
        var middle = app.form_list[index];
        var prev = app.form_list[index - 1];
        app.form_list.splice(index - 1, 2, middle, prev);
    });
    $(document).on('click', '.form-next', function () {
        var index = $(this).data('index');
        if (index == app.form_list.length - 1) {
            return;
        }
        var middle = app.form_list[index];
        var next = app.form_list[index + 1];
        app.form_list.splice(index, 2, next, middle);
    });
</script>
<script>
    $(document).on('click', '.form-add', function () {
        var aa = {};
        aa.type = $('.form-add-type').val();
        app.form_list.push(aa);
    });
    $(document).on('click', '.re', function () {
        var check = $(this).prop('checked');
        var index = $(this).data('index');
        if (check) {
            app.form_list[index].required = 1;
        } else {
            app.form_list[index].required = 0;
        }
    });
</script>
<script>
    //音频和视频切换
    $(document).on('click', '.style .custom-control-input', function () {
        if ($(this).val() == 0) {
            $('.type-hide').prop('hidden', false);
            $('.style-2').prop('hidden', false);
            $('.is_pay').prop('hidden', false);
        }
        if ($(this).val() == 1) {
            $('.type-hide').prop('hidden', true);
            $('.style-2').prop('hidden', false);
            $('.is_pay').prop('hidden', false);
        }
        if ($(this).val() == 2) {
            $('.type-hide').prop('hidden', true);
            $('.style-2').prop('hidden', true);
            $('.is_pay').prop('hidden', true);
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