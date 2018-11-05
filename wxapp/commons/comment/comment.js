var api = require('../../api.js');
var app = getApp();
var comment = {
    init: function (page) {
        var _this = this;
        _this.page = page;
        _this.page.show = function (e) {
            var video = wx.createVideoContext('video');
            video.pause();
            var user_info = wx.getStorageSync('user_info');
            if (user_info.is_comment == 1) {
                wx.showModal({
                    title: '提示',
                    content: '您已被管理员禁言！若要解除，请联系管理员',
                    showCancel: false
                })
                return;
            }
            var c_id = e.currentTarget.dataset.id;
            var name = '发表评论';
            if (c_id != 0) {
                name = '回复@' + e.currentTarget.dataset.name + '的评论';
            }
            var old_c_id = _this.page.data.c_id;
            if (c_id != old_c_id) {
                _this.page.setData({
                    content: ''
                });
            }
            _this.page.setData({
                show: true,
                c_id: c_id,
                name: name,
                focus: true
            });
        };
        _this.page.input = function (e) {
            var value = e.detail.value;
            _this.page.setData({
                content: value
            });
        };

        _this.page.hide = function (e) {
            _this.page.setData({
                show: false,
            });
        };

        _this.page.chooseImg = function (e) {

            var max_count = 5;
            var img_list = _this.page.data.img_list;
            var current_count = img_list.length;
            if (current_count >= max_count) {
                return;
            }
            wx.chooseImage({
                count: (max_count - current_count),
                success: function (res) {
                    img_list = img_list.concat(res.tempFilePaths);
                    _this.page.setData({
                        img_list: img_list
                    });
                },
            })
        };

        _this.page.deleteImg = function (e) {
            wx.showModal({
                title: '提示',
                content: '是否删除图片？',
                success: function (res) {
                    if (res.confirm) {
                        var index = e.currentTarget.dataset.index;
                        var img_list = _this.page.data.img_list;
                        img_list.splice(index, 1);
                        _this.page.setData({
                            img_list: img_list
                        });
                    }
                }
            })
        };

        _this.page.submit = function (e) {
            var img_list = _this.page.data.img_list;
            var upload_img = [];
            _this.page.setData({
                focus: false
            });
            var content = _this.page.data.content;
            var form_id = e.detail.formId;
            if (!content && img_list.length == 0) {
                wx.showModal({
                    title: '提示',
                    content: '请输入内容或上传图片',
                    showCancel: false
                })
                return;
            }
            wx.showLoading({
                title: '正在提交',
                mask: true
            });
            uploadImg(0);
            function uploadImg(i) {
                if (i == img_list.length) {
                    return submit();
                }
                if (i >= 5) {
                    return submit();
                }
                wx.uploadFile({
                    url: api.default.upload_img,
                    filePath: img_list[i],
                    name: 'image',
                    complete: function (res) {
                        if (res.statusCode == 200) {
                            var data = JSON.parse(res.data);
                            if (data.code == 0) {
                                upload_img = upload_img.concat(data.data.url);
                            }
                        }
                        i++;
                        if (i != img_list.length) {
                            return uploadImg(i);
                        } else {
                            return submit();
                        }
                    }
                })
            }
            function submit() {
                app.request({
                    url: api.user.comment,
                    method: 'POST',
                    data: {
                        video_id: _this.page.data.video_id,
                        c_id: _this.page.data.c_id,
                        content: _this.page.data.content,
                        upload_img: JSON.stringify(upload_img),
                        form_id: form_id
                    },
                    success: function (res) {
                        wx.hideLoading();
                        if (res.code == 0) {
                            wx.showToast({
                                title: '评论成功',
                            });
                            _this.page.loadComment(_this.page.data.video_id);
                            _this.page.hide();
                            _this.page.setData({
                                img_list: [],
                                page: 1
                            });
                        } else if (res.code == 2) {
                            wx.showModal({
                                title: '提示',
                                content: res.msg,
                                showCancel: false
                            })
                        }
                    },
                    complete:function(res){
                        wx.hideLoading();
                    }
                });
            }
        }

    }
}
module.exports = comment;