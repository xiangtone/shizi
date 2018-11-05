// pages/comment/comment.js
var app = getApp();
var api = require('../../api.js');
var comment = require('../../commons/comment/comment.js');
var is_loading_more = false;
var is_no_more = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        page: 1,
        img_list: [],
        // show:false
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        is_loading_more = false;
        is_no_more = false;
        var page = this;
        comment.init(page);
        var video_id = options.video_id;
        page.setData({
            video_id: video_id
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {
        app.pageOnReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        app.pageOnShow(this);
        var page = this;
        page.loadComment();
    },
    
    loadComment: function () {
        var page = this;
        var video_id = page.data.video_id;
        wx.showLoading({
            title: '加载中',
        });
        is_loading_more = true;
        app.request({
            url: api.user.comment_list,
            data: {
                video_id: video_id,
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        comment_list: res.data.list,
                        page_count: res.data.page_count,
                        row_count: res.data.row_count,
                        cc_count: res.data.cc_count
                    });
                }
            },
            complete: function () {
                wx.hideLoading();
                is_loading_more = false;
            }
        });
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        app.pageOnHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        app.pageOnUnload(this);

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        if (this.data.page == this.data.page_count) {
            return;
        }
        this.loadMoreComment();
    },
    loadMoreComment: function () {
        var page = this;
        var comment_list = page.data.comment_list;
        var p = page.data.page;
        if (is_loading_more) {
            return;
        }
        is_loading_more = true;
        wx.showLoading({
            title: '加载中',
        });
        app.request({
            url: api.user.comment_list,
            data: {
                video_id: page.data.video_id,
                page: (p + 1)
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    if (res.data.list.length > 0) {
                        comment_list = comment_list.concat(res.data.list);
                        page.setData({
                            page: (p + 1),
                            comment_list: comment_list,
                            page_count: res.data.page_count,
                            row_count: res.data.row_count,
                            cc_count: res.data.cc_count
                        });
                    } else {
                        is_no_more = true;
                    }
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                    });
                }
            },
            complete: function () {
                is_loading_more = false;
            }
        });
    },
    thump: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var comment_list = page.data.comment_list;
        var id = comment_list[index].id;
        var thump_count = comment_list[index].thump_count;
        page.setData({
            thump_loading: index
        });

        app.request({
            url: api.user.thump,
            data: {
                c_id: id
            },
            success: function (res) {
                if (res.code == 0) {
                    var thump = !comment_list[index].thump;
                    var title = '取消点赞';
                    if (thump) {
                        title = '点赞成功';
                        thump_count++;
                    } else {
                        thump_count--
                    }
                    comment_list[index].thump = thump;
                    comment_list[index].thump_count = thump_count;
                    page.setData({
                        comment_list: comment_list,
                        toast_text: title,
                        isShowToast: true
                    });
                    setTimeout(function () {
                        page.setData({
                            isShowToast: false
                        });
                    }, 1500);
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    })
                }
            },
            complete: function () {
                page.setData({
                    thump_loading: -1
                });
            }
        });
    },
    previewImg: function (e) {
        var page = this;
        var comment_list = page.data.comment_list;
        var index = e.currentTarget.dataset.index;
        var i = e.currentTarget.dataset.i;
        var img_list = comment_list[index].img;
        var current = img_list[i];
        wx.previewImage({
            current: current,
            urls: img_list,
        })
    },
    previewImg_c: function (e) {
        var page = this;
        var comment_list = page.data.comment_list;
        var index = e.currentTarget.dataset.index;
        var i = e.currentTarget.dataset.i;
        var pic = e.currentTarget.dataset.pic;
        var img_list = comment_list[index].children[i].img;
        var current = img_list[pic];
        wx.previewImage({
            current: current,
            urls: img_list,
        })
    }
})