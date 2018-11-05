// pages/search/search.js
var app = getApp();
var api = require('../../api.js');
var is_more = true;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        show_search: true,
        show_result: false,
        show_input: false,
        history_list: [],
        hot_list: [],
        video_list: [],
        input_list: [],
        title_list: []
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        var page = this;
        app.request({
            url: api.default.hot_search,
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        title_list: res.data.video_title,
                        hot_list: res.data.hot_list
                    });
                }
            }
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
        this.setData({
            history_list: wx.getStorageSync("search_history_list") || []
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
        if (!is_more) {
            return;
        }
        this.getMore();
    },
    /**
     * 输入框聚焦
     */
    inputfocus: function (e) {
        var value = e.detail.value;
        if (value) {
            var title_list = this.data.title_list;
            var input_list = [];
            for (var i = title_list.length - 1; i >= 0; i--) {
                if (title_list[i].title.toLowerCase().indexOf(value.toLowerCase()) > -1) {
                    input_list.push(title_list[i]);
                }
                if (input_list.length >= 10) {
                    break;
                }
            }
            this.setData({
                show_search: false,
                show_result: false,
                show_input: true,
                input_list: input_list
            });
        } else {
            this.setData({
                show_search: true,
                show_result: false,
                show_input: false
            });
        }
    },
    /**
     * 输入框失去焦点
     */
    inputblur: function () {

    },
    /**
     * 输入框完成输入
     */
    inputconfirm: function (e) {
        var page = this;
        var keyword = e.detail.value;
        if (keyword.length == 0)
            return;
        page.setData({
            page: 1,
            keyword: keyword,
        });
        page.setHistory(keyword);
        page.getVideosList();
    },
    /**
     * 设置历史搜索缓存
     */
    setHistory: function (keyword) {
        var page = this;
        var history_list = wx.getStorageSync("search_history_list") || [];
        keyword = keyword.substr(0, 5);
        var is_m = true;
        for (var j = history_list.length - 1; j >= 0; j--) {
            if (history_list[j].keyword == keyword) {
                is_m = false;
                break;
            }
        }
        if (is_m) {
            history_list.push({
                keyword: keyword,
            });
        }
        for (var i in history_list) {
            if (history_list.length <= 20)
                break;
            history_list.splice(i, 1);
        }
        wx.setStorageSync('search_history_list', history_list);
        page.setData({
            history_list: history_list
        });
    },
    /**
     * 删除历史搜索缓存
     */
    deletehistory: function () {
        var page = this;
        page.setData({
            history_list: [],
        });
        wx.removeStorageSync("search_history_list");
    },
    /**
     * 根据关键字查询
     */
    getVideosList: function () {
        var page = this;
        page.setData({
            show_search: false,
            show_result: true,
            show_input: false
        });
        page.setData({
            page: 1,
        });
        page.setData({
            video_list: [],
        });
        wx.showLoading({
            title: '正在加载',
        });
        app.request({
            url: api.default.search,
            data: {
                page: page.data.page,
                keyword: page.data.keyword
            },
            success: function (res) {
                if (res.code == 0) {
                    is_more = true;
                    page.setData({
                        video_list: res.data.video_list,
                        hot_list: res.data.hot_list
                    })
                }
            },
            complete: function () {
                wx.hideLoading();
            }
        });
    },
    /**
     * 点击输入提示
     */
    searchclick: function (e) {
        var keyword = e.currentTarget.dataset.keyword;
        this.setData({
            page: 1,
            keyword: keyword,
        });
        this.getVideosList();
    },
    /**
     * 加载更多
     */
    getMore: function () {
        var page = this;
        var data = {};
        if (!page.data.keyword) {
            return;
        }
        data.keyword = page.data.keyword;
        var pages = page.data.page || 1;
        wx.showLoading({
            title: '加载中',
        });
        data.page = pages + 1;
        page.setData({
            page: data.page
        });
        app.request({
            url: api.default.search,
            data: data,
            success: function (res) {
                if (res.code == 0) {
                    var video_list = page.data.video_list;
                    if (res.data.video_list.length > 0) {
                        page.setData({
                            video_list: video_list.concat(res.data.video_list)
                        });
                    } else {
                        is_more = false;
                        page.setData({
                            page: data.page - 1
                        });
                    }
                }
            },
            complete: function () {
                wx.hideLoading();
            }
        });
    },
    cancel: function () {
        wx.navigateBack({
            delta: 1,
        });
    }
})