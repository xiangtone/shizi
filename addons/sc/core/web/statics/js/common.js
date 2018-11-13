$.randomString = function (len) {
    len = len || 32;
    var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
    var maxPos = $chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
};

$.myConfirm = function (args) {
    args = args || {};
    var title = args.title || "提示";
    var content = args.content || "";
    var confirmText = args.confirmText || "确认";
    var cancelText = args.cancelText || "取消";
    var confirm = args.confirm || function () {
    };
    var cancel = args.cancel || function () {
    };
    var id = $.randomString();
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<h6 class="modal-title">' + title + '</h6>';
    html += '</div>';
    html += '<div class="modal-body">' + content + '</div>';
    html += '<div class="modal-footer">';
    html += '<button type="button" class="btn btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '<button type="button" class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#" + id).modal("show");
    $(document).on("click", "#" + id + " .alert-confirm-btn", function () {
        $("#" + id).modal("hide");
        confirm();
    });
    $(document).on("click", "#" + id + " .alert-cancel-btn", function () {
        $("#" + id).modal("hide");
        cancel();
    });
};

$.myAlert = function (args) {
    args = args || {};
    var title = args.title || "提示";
    var content = args.content || "";
    var confirmText = args.confirmText || "确认";
    var confirm = args.confirm || function () {
    };
    var id = $.randomString();
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<h6 class="modal-title">' + title + '</h6>';
    html += '</div>';
    html += '<div class="modal-body">' + content + '</div>';
    html += '<div class="modal-footer">';
    html += '<button type="button" class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#" + id).modal("show");
    $(document).on("click", "#" + id + " .alert-confirm-btn", function () {
        $("#" + id).modal("hide");
        confirm();
    });
};

$.myPrompt = function (args) {
    args = args || {};
    var content = args.content || "";
    var confirmText = args.confirmText || "确认";
    var cancelText = args.cancelText || "取消";
    var confirm = args.confirm || function () {
        };
    var cancel = args.cancel || function () {
        };
    var id = $.randomString();
    var html = '';
    html += '<div class="modal fade" data-backdrop="static" id="' + id + '">';
    html += '<div class="modal-dialog modal-sm" role="document">';

    html += '<div class="modal-content">';
    if (args.title) {
        html += '<div class="modal-title"><b>' + args.title + '</b></div>';
    }
    html += '  <div class="modal-body">';
    html += '    <div>' + content + '</div>';
    html += '    <div class="mt-3"><input class="form-control"></div>';
    html += '  </div>';
    html += '  <div class="modal-footer text-right">';
    html += '    <button class="btn btn-secondary alert-cancel-btn">' + cancelText + '</button>';
    html += '    <button class="btn btn-primary alert-confirm-btn">' + confirmText + '</button>';
    html += '  </div>';
    html += '</div>';

    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#" + id).modal("show");
    $(document).on("click", "#" + id + " .alert-confirm-btn", function () {
        $("#" + id).modal("hide");
        var val = $("#" + id).find(".form-control").val();
        confirm(val);
    });
    $(document).on("click", "#" + id + " .alert-cancel-btn", function () {
        $("#" + id).modal("hide");
        var val = $("#" + id).find(".form-control").val();
        cancel(val);
    });
};

$.myLoading = function (args) {
    args = args || {};
    var title = args.title || "Loading";
    var html = '';
    html += '<div class="modal" data-backdrop="static" id="myLoading" aria-hidden="true">';
    html += '<div class="modal-dialog modal-sm" role="document">';
    html += '<div style="text-align: center;color: #fff">';
    html += '<div style="width: 5rem;height: 5rem;display: inline-block"><svg class="lds-spinner" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="background:none"><g transform="rotate(0 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="-0.6124999999999999s" repeatCount="indefinite"></animate></rect></g><g transform="rotate(45 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="-0.5249999999999999s" repeatCount="indefinite"></animate></rect></g><g transform="rotate(90 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="-0.4375s" repeatCount="indefinite"></animate></rect></g><g transform="rotate(135 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="-0.35s" repeatCount="indefinite"></animate></rect></g><g transform="rotate(180 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="-0.26249999999999996s" repeatCount="indefinite"></animate></rect></g><g transform="rotate(225 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="-0.175s" repeatCount="indefinite"></animate></rect></g><g transform="rotate(270 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="-0.0875s" repeatCount="indefinite"></animate></rect></g><g transform="rotate(315 50 50)"><rect x="47" y="10" rx="8.93" ry="1.9000000000000001" width="6" height="24" fill="#f19d3b"><animate attributeName="opacity" values="1;0" times="0;1" dur="0.7s" begin="0s" repeatCount="indefinite"></animate></rect></g></svg></div>';
    html += '<div>' + title + '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").append(html);
    $("#myLoading").modal("show");
};
$.myLoadingHide = function () {
    $(document).on("hidden.bs.modal", "#myLoading", function () {
        $(this).remove();
    });
    $("#myLoading").modal("hide");
};

$.fn.extend({
    btnLoading: function (loadingText, showIcon) {
        loadingText = loadingText || "Loading";
        showIcon = showIcon || true;
        this[0].originalText = this.html();
        this.html(loadingText).addClass("disabled btn-loading");
        this.prop("disabled", true);
        return this;
    },
    btnReset: function () {
        this.html(this[0].originalText);
        this.removeClass("disabled btn-loading");
        this.prop("disabled", false);
        return this;
    },

    plupload: function (args) {
        var $$this = this;
        $$this.each(function () {
            var _this = this;
            var $this = $(this);
            if (_this.uploader) {
                return;
            }
            if ($this.attr("id"))
                var browse_button = $this.attr("id");
            else {
                var browse_button = $.randomString();
                $this.attr("id", browse_button);
            }
            _this.uploader = new plupload.Uploader({
                browse_button: browse_button,
                url: args.url || "",
            });
            _this.uploader.bind("FilesAdded", function (uploader, files) {
                uploader.start();
                if (args.beforeUpload && typeof args.beforeUpload == "function")
                    args.beforeUpload($this, _this);
                uploader.disableBrowse(true);
            });
            _this.uploader.bind("FileUploaded", function (uploader, file, responseObject) {
                if (responseObject.status == 200) {

                }
                var res = JSON.parse(responseObject.response);
                if (args.success && typeof args.success == "function")
                    args.success(res, _this, $this);
            });
            _this.uploader.bind("UploadComplete", function (uploader, files) {
                uploader.destroy();
                _this.uploader = false;
                setTimeout(function () {
                    $(_this).plupload(args);
                }, 1);
            });
            _this.uploader.init();

        });

    }

});


$(document).ready(function () {

    //Yii2 POST表单添加_csrf
    $("form[method=post]").each(function () {
        if (this._csrf == undefined)
            $(this).append('<input name="_csrf" value="' + _csrf + '" type="hidden">');
    });

    //元素自动保持比例
    (function () {
        $.toResponsive = function () {
            $("*[data-responsive]").each(function (i) {
                var originWidth = parseFloat($(this).css("width"));
                var responsive = $(this).attr("data-responsive");
                var sizeData = responsive.split(":");
                var width = parseFloat(sizeData[0]);
                var height = parseFloat(sizeData[1]);
                var newHeight = height * originWidth / width;
                $(this).css("height", newHeight)
            });
        };

        $(document).ready(function () {
            $.toResponsive();
        });
        window.onresize = function () {
            $.toResponsive();
        };
    })();

    //表单自动提交
    (function () {
        $(document).on("click", ".auto-submit-form .submit-btn", "click", function () {
            var form = $(this).parents("form");
            var return_url = form.attr("data-return");
            var timeout = form.attr("data-timeout");
            var btn = $(this);
            var error = form.find(".form-error");
            var success = form.find(".form-success");
            error.hide();
            success.hide();
            btn.btnLoading("正在提交");
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

        $(document).on("submit", ".auto-submit-form", function () {
            var form = $(this);
            var return_url = form.attr("data-return");
            var timeout = form.attr("data-timeout");
            var btn = form.find(".submit-btn");
            var error = form.find(".form-error");
            var success = form.find(".form-success");
            error.hide();
            success.hide();
            btn.btnLoading("正在提交");
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
                        btn.btnReset();
                        error.html(res.msg).show();
                    }
                }
            });
            return false;
        });
    })();

    //单图片上传
    (function () {
        var image_picker_list = $(".image-picker");
        if (image_picker_list.length == 0)
            return;
        image_picker_list.each(function (index) {
            var picker = this;
            var el = $(this);
            var btn = el.find(".image-picker-btn");
            var url = el.attr("data-url");
            var input = el.find(".image-picker-input");
            var view = el.find(".image-picker-view");


            function uploaderInit() {

                var el_id = $.randomString(32);
                btn.attr("id", el_id);


                var uploader = new plupload.Uploader({
                    browse_button: el_id,
                    url: url
                });
                uploader.bind("Init", function (uploader) {
                    $(".moxie-shim").find("input").attr("accept", "image/*").prop("multiple", false);
                });
                uploader.bind("FilesAdded", function (uploader, files) {
                    uploader.start();
                    btn.btnLoading("正在上传");
                    uploader.disableBrowse(true);
                });
                uploader.bind("FileUploaded", function (uploader, file, responseObject) {
                    if (responseObject.status == undefined || responseObject.status != 200) {
                        return true;
                    }
                    var res = $.parseJSON(responseObject.response);
                    if (res.code != 0)
                        return true;

                    var _multiple = el.attr("data-multiple");
                    if (_multiple && _multiple == "true") {
                        var _name = el.attr("data-name");
                        var _responsive = el.find(".image-picker-view").attr("data-responsive");
                        var _tip = el.find(".picker-tip").first().text();
                        var _html = '';
                        _html += '<div class="image-picker-view-item">';
                        _html += '<input class="image-picker-input" type="hidden" name="' + _name + '" value="' + (res.data.url) + '" >';
                        _html += '<div class="image-picker-view" data-responsive="' + _responsive + '" style="background-image: url(' + res.data.url + ')">';
                        _html += '<span class="picker-tip">' + _tip + '</span>';
                        _html += '<span class="picker-delete">×</span>';
                        _html += '</div>';
                        _html += '</div>';
                        el.find(".image-picker-view-item").last().after(_html);
                        $.toResponsive();
                        updateEmptyPicker(el);
                    }
                    else {
                        input.val(res.data.url);
                        view.css("background-image", "url('" + res.data.url + "')");
                    }
                });
                uploader.bind("UploadComplete", function (uploader, files) {
                    btn.btnReset();
                    uploader.destroy();
                    uploaderInit();
                });
                uploader.init();
            }

            uploaderInit();
        });

        $(document).on("click", ".image-picker-view .picker-delete", function () {
            var picker = $(this).parents(".image-picker");
            var multiple = picker.attr("data-multiple");
            if (multiple && multiple == "true") {
                $(this).parents(".image-picker-view-item").remove();
                updateEmptyPicker(picker);
            } else {
                var image_picker_view_item = $(this).parents(".image-picker-view-item");
                image_picker_view_item.find(".image-picker-input").val("");
                image_picker_view_item.find(".image-picker-view").css("background-image", "");
            }
        });

        function updateEmptyPicker(picker) {
            if (picker.find(".image-picker-view-item").length > 1) {
                picker.find(".picker-empty-preview").hide();
            } else {
                picker.find(".picker-empty-preview").show();
            }
        }

    })();


    $(".new-image-picker-btn").pickImage({
        success: function (res, _this) {
            var el = $(_this).parents(".image-picker");
            if (el.attr("data-multiple")) {
                var _name = el.attr("data-name");
                var _responsive = el.find(".image-picker-view").attr("data-responsive");
                var _tip = el.find(".picker-tip").first().text();
                var _html = '';
                _html += '<div class="image-picker-view-item">';
                _html += '<input class="image-picker-input" type="hidden" name="' + _name + '" value="' + (res.data.url) + '" >';
                _html += '<div class="image-picker-view" data-responsive="' + _responsive + '" style="background-image: url(' + res.data.url + ')">';
                _html += '<span class="picker-tip">' + _tip + '</span>';
                _html += '<span class="picker-delete">×</span>';
                _html += '</div>';
                _html += '</div>';
                el.find(".image-picker-view-item").last().after(_html);
                $.toResponsive();
                updateEmptyPicker(el);
            } else {
                $(_this).parents(".image-picker").find(".image-picker-input").val(res.data.url);
                $(_this).parents(".image-picker").find(".image-picker-view").css("background-image", "url(" + res.data.url + ")");
            }


            function updateEmptyPicker(picker) {
                if (picker.find(".image-picker-view-item").length > 1) {
                    picker.find(".picker-empty-preview").hide();
                } else {
                    picker.find(".picker-empty-preview").show();
                }
            }
        }
    });

});