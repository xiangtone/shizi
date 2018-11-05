// pages/order/order.js
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
        if (!options.status) {
            options.status = -1;
        }
        var store = wx.getStorageSync('store');
        this.setData({
            status: options.status,
            store: store
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
        this.loadData();
    },

    loadData: function () {
        var page = this;
        wx.showLoading({
            title: '加载中',
        });
        is_loading_more = true;
        app.request({
            url: api.order.list,
            method: "GET",
            data: {
                status: page.data.status,
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        order_list: res.data.order_list,
                        page_count: res.data.page_count
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
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        console.log(11);
        if (this.data.page >= this.data.page_count) {
            return;
        }
        this.loadMore();
    },
    loadMore: function () {
        var page = this;
        if (is_loading_more) {
            return;
        }
        var p = page.data.page;
        is_loading_more = true;
        page.setData({
            show_loading_bar: true
        });
        app.request({
            url: api.order.list,
            method: 'GET',
            data: {
                status: page.data.status,
                page: p + 1
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    var order_list = page.data.order_list.concat(res.data.order_list);
                    page.setData({
                        order_list: order_list,
                        page_count: res.data.page_count,
                        page: p + 1,
                        show_loading_bar: false
                    });
                }
            },
            complete: function () {
                is_loading_more = false;
            }
        });
    },
    getQrcode: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var order_list = page.data.order_list;
        wx.showLoading({
            title: '加载中',
        });
        app.request({
            url: api.order.qrcode,
            method: 'GET',
            data: {
                scene: order_list[index].order_no,
                path: 'pages/clerk/clerk'
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    page.setData({
                        url: res.file_path,
                        show: true
                    });
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    });
                }
            }
        });
    },
    qrcodeHide: function () {
        this.setData({
            show: false,
            url: ''
        });
    },
    goto: function (e) {
        var page = this;
        var order_list = page.data.order_list;
        var index = e.currentTarget.dataset.index;
        var order = order_list[index];
        if (order.is_use == 2) {
            wx.navigateTo({
                url: '/pages/order-refund-detail/order-refund-detail?order_id=' + order.id,
            })
        } else {
            wx.navigateTo({
                url: '/pages/order-detail/order-detail?order_id=' + order.id,
            })
        }
    },
    refund: function (e) {
        var page = this;
        var order_list = page.data.order_list;
        var index = e.currentTarget.dataset.index;
        wx.navigateTo({
            url: '/pages/order-refund/order-refund?order_id=' + order_list[index].id,
        })
    },
    formSubmit: function (e) {
        var page = this;
        var order_list = page.data.order_list;
        var index = e.currentTarget.dataset.index;
        wx.showLoading({
            title: '取消提交中',
        });
        app.request({
            url: api.order.refund,
            method: 'POST',
            data: {
                order_id: order_list[index].id,
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    wx.showToast({
                        title: '取消成功',
                        icon: 'success'
                    });
                    setTimeout(function () {
                        page.onLoad()
                    }, 2000);
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    });
                }
            }
        });
    }
})