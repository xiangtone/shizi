// pages/cat/cat.js
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
        this.loadData();
    },
    loadData: function () {
        var page = this;
        wx.showLoading({
            title: '正在加载',
        })
        is_loading_more = true;
        app.request({
            url: api.default.cat,
            data: {
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        cat_list: res.data.cat_list,
                        page_count: res.data.page_count
                    });
                }
            },
            complete: function () {
                wx.stopPullDownRefresh();
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
        this.loadData();
        is_no_more = false;
        is_loading_more = false;
        this.setData({
            page: 1
        });
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        if (is_no_more) {
            return;
        }
        this.loadMore();
    },
    loadMore: function () {
        var page = this;
        var p = page.data.page;
        if (is_loading_more) {
            return;
        }
        is_loading_more = true;
        wx.showLoading({
            title: '正在加载',
        });
        app.request({
            url: api.default.cat,
            data: {
                page: p + 1
            },
            success: function (res) {
                if (res.data.cat_list.length == 0)
                    is_no_more = true;
                if (res.code == 0) {
                    var cat_list = page.data.cat_list.concat(res.data.cat_list);
                    page.setData({
                        cat_list: cat_list,
                        page: p + 1,
                        page_count: res.data.page_count
                    });
                }
            },
            complete: function () {
                is_loading_more = false;
                wx.hideLoading();
            }
        });

    }
})