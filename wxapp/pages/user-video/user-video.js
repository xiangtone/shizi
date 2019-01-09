// pages/user-video/user-video.js
var app = getApp();
var api = require('../../api.js');
var is_loading_more = false;
var is_no_more = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        page: 1
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        is_no_more = false;
        is_loading_more = false;
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
        this.loadData();
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
        var page = this;
        console.log(0);
        if (page.data.page >= page.data.page_count) {
            return;
        }
        console.log(1);
        page.loadMoreData();
    },
    loadData: function () {
        var page = this;
        wx.showLoading({
            title: '加载中',
        })
        if (is_loading_more) {
            return;
        }
        is_loading_more = true;
        app.request({
            url: api.user.buy_video,
            method: 'GET',
            data: {
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData(res.data);
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    })
                }
            },
            complete: function (res) {
                wx.hideLoading();
                is_loading_more = false;
            }
        });
    },
    loadMoreData: function () {
        var page = this;
        page.setData({
            show_loading_bar: true
        });
        var p = page.data.page;
        if (is_loading_more) {
            return;
        }
        console.log(2);
        is_loading_more = true;
        app.request({
            url: api.user.buy_video,
            method: 'GET',
            data: {
                page: (p + 1)
            },
            success: function (res) {
                if (res.code == 0) {
                    var video_list = page.data.video_list.concat(res.data.video_list);
                    page.setData({
                        video_list: video_list,
                        page_count: res.data.page_count,
                        row_count: res.data.row_count,
                        page: p + 1
                    });
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    })
                }
            },
            complete: function (res) {
                page.setData({
                    show_loading_bar: false
                });
                is_loading_more = false;
            }
        });
    },
    goto: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var video = page.data.video_list[index];
        if (video.status == 1 || video.is_delete == 1) {
            wx.showModal({
                title: '提示',
                content: '该是内容已经下架！！',
                showCancel: false
            })
            return;
        }
        wx.navigateTo({
            // url: '/pages/video1/video1?id=' + video.id,
          url: '/pages/cat-second/cat-second?cat_id=' + video.id,
        })
    }
})