<?php
defined('YII_RUN') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 
 * Date: 2017/9/13
 * Time: 9:44
 */

?>
<style>
    .label-help {
        display: inline-block;
        font-size: .65rem;
        background: #555;
        color: #fff;
        border-radius: 999px;
        width: 1rem;
        height: 1rem;
        line-height: 1rem;
        text-align: center;
        text-decoration: none;
        margin-left: .25rem;
    }

    .label-help:hover,
    .label-help:visited {
        color: #fff;
        text-decoration: none;
    }
</style>
<div id="pick_link_modal">
    <div class="modal fade pick-link-modal" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">选择链接</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-addon">可选链接</span>
                        <select class="form-control link-list-select">
                            <option value="">点击选择链接</option>
                            <option v-for="(link,i) in link_list"
                                    v-if="in_array(link.open_type,open_type) != -1"
                                    v-bind:value="i">
                                {{link.name}}
                            </option>
                        </select>
                    </div>
                    <template v-if="selected_link">
                        <template v-if="selected_link.params && selected_link.params.length>0">
                            <div class="form-group row" v-for="(param,i) in selected_link.params">
                                <label class="col-sm-2 text-right col-form-label">{{param.key}}</label>
                                <div class="col-sm-10">
                                    <input class="form-control" v-model="param.value">
                                    <div class="fs-sm text-muted" v-if="param.desc">{{param.desc}}</div>
                                </div>
                            </div>
                        </template>
                        <div v-else class="text-center text-muted">此页面无需配置参数</div>
                    </template>
                    <div v-else class="text-center text-muted">请选择链接</div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary pick-link-confirm" href="javascript:">确定</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    String.prototype._trim = function (char, type) {
        if (char) {
            if (type == 'left') {
                return this.replace(new RegExp('^\\' + char + '+', 'g'), '');
            } else if (type == 'right') {
                return this.replace(new RegExp('\\' + char + '+$', 'g'), '');
            }
            return this.replace(new RegExp('^\\' + char + '+|\\' + char + '+$', 'g'), '');
        }
        return this.replace(/^\s+|\s+$/g, '');
    };

    var pick_link_modal;
    $(document).ready(function () {
        var pick_link_btn;

        pick_link_modal = new Vue({
            el: "#pick_link_modal",
            data: {
                in_array: function (val, arr) {
                    return $.inArray(val, arr);
                },
                open_type: [],
                selected_link: null,
                link_list: [
                    {
                        name: "首页",
                        link: "/pages/index/index",
                        open_type: "switchTab",
                        params: []
                    },
                    {
                        name: "分类",
                        link: "/pages/cat/cat",
                        open_type: "switchTab",
                        params: []
                    },
                    {
                        name: "用户中心",
                        link: "/pages/user/user",
                        open_type: "switchTab",
                        params: []
                    },
                    {
                        name: "视频列表",
                        link: "/pages/cat-second/cat-second",
                        open_type: "navigate",
                        params: [
                            {
                                key: "cat_id",
                                value: "",
                                desc: "cat_id请填写在分类中的ID"
                            }
                        ]
                    },
                    {
                        name: "视频详情",
                        link: "/pages/video/video",
                        open_type: "navigate",
                        params: [
                            {
                                key: "id",
                                value: "",
                                desc: "id请填写在视频列表中相关视频的ID"
                            }
                        ]
                    },
                    {
                        name: "我的收藏",
                        link: "/pages/collect/collect",
                        open_type: "navigate",
                        params: []
                    },
                    {
                        name: "小程序（同一公众号下关联的小程序）",
                        link: "/",
                        open_type: "wxapp",
                        params: [
                            {
                                key: "appId",
                                value: "",
                                desc: "要打开的小程序 appId"
                            },
                            {
                                key: "path",
                                value: "",
                                desc: "打开的页面路径，如pages/index/index，开头请勿加“/”"
                            },
                        ]
                    },

                ]
            }
        });

        $(document).on("change", ".link-list-select", function () {
            var i = $(this).val();
            if (i == "") {
                pick_link_modal.selected_link = null;
                return;
            }
            pick_link_modal.selected_link = pick_link_modal.link_list[i];
        });

        $(document).on("click", ".pick-link-btn", function () {
            pick_link_btn = $(this);
            var open_type = $(this).attr("open-type");
            if (open_type && open_type != "") {
                open_type = open_type.split(",");
            } else {
                open_type = ["navigate", "switchTab", "wxapp"];
            }
            pick_link_modal.open_type = open_type;
            $(".pick-link-modal").modal("show");
        });

        $(document).on("click", ".pick-link-confirm", function () {
            var selected_link = pick_link_modal.selected_link;
            if (!selected_link) {
                $(".pick-link-modal").modal("hide");
                return;
            }
            var link_input = pick_link_btn.parents(".page-link-input").find(".link-input");
            var open_type_input = pick_link_btn.parents(".page-link-input").find(".link-open-type");
            var params = "";
            if (selected_link.params && selected_link.params.length > 0) {
                for (var i in selected_link.params) {
                    params += selected_link.params[i].key + "=" + encodeURIComponent(selected_link.params[i].value) + "&";
                }
            }
            var link = selected_link.link;
            link += "?" + params;
            link = link._trim("&");
            link = link._trim("?");
            link_input.val(link).change();
            open_type_input.val(selected_link.open_type).change();
            $(".pick-link-modal").modal("hide");


        });

    });
</script>