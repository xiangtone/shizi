// pages/user-comment/user-comment.js
var app = getApp();
var api = require('../../api.js');
var is_loading_more = false;
var is_no_more = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        page: 1,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        is_loading_more = false;
        is_no_more = false;

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
        wx.showLoading({
            title: '加载中',
        });
        is_loading_more = true;
        app.request({
            url: api.user.user_comment,
            data: {
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        comment_list: res.data.list,
                        page_count: res.data.page_count,
                        row_count: res.data.row_count
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
            url: api.user.user_comment,
            data: {
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
                            row_count: res.data.row_count
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
    goto: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var comment_list = page.data.comment_list;
        var video_id = comment_list[index].video_id;
        wx.navigateTo({
            url: '/pages/video/video?id=' + video_id,
        })
    }
})