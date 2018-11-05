// pages/order-detail/order-detail.js
var app = getApp();
var api = require('../../api.js');
var WxParse = require('../../wxParse/wxParse.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {
        isShowToast: false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        var page = this;
        var store = wx.getStorageSync('store');
        page.setData({
            order_id: options.order_id,
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
        wx.showLoading({
            title: '加载中',
        });
        app.request({
            url: api.order.detail,
            method: 'GET',
            data: {
                order_id: page.data.order_id
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    var video = wx.createVideoContext('video');
                    var content = res.data.video.detail + '<span> </span>'
                    WxParse.wxParse("detail", "html", content, page, 5);
                    page.setData(res.data);
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        app.pageOnHide(this);

        var page = this;
        var old = wx.createVideoContext("video");
        old.seek(0);
        old.pause();

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

    },
    play: function (options) {
        wx.redirectTo({
            url: '/pages/video/video?id=' + options.currentTarget.dataset.id,
        })
    },
    getQrcode: function (e) {
        var page = this;
        wx.showLoading({
            title: '加载中',
        });
        app.request({
            url: api.order.qrcode,
            method: 'GET',
            data: {
                scene: page.data.order.order_no,
                path: 'pages/clerk/clerk'
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    page.setData({
                        url: res.file_path,
                        share_show: true
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
            share_show: false,
            url: ''
        });
    },
    playVideo: function () {
        var page = this;
        var video_play = wx.createVideoContext('video');
        var video = page.data.video;
        if (video.status == 1) {
            wx.showModal({
                title: '提示',
                content: '该内容已下架',
                showCancel: false
            })
        } else {
            wx.getNetworkType({
                success: function (res) {
                    if (res.networkType != 'wifi') {
                        wx.showModal({
                            title: '提示',
                            content: '当前网络状态不是WiFi，是否播放',
                            success: function (e) {
                                if (e.confirm) {
                                    page.setData({
                                        play: true
                                    });
                                    video_play.play();
                                }
                            }
                        })
                    } else {
                        page.setData({
                            play: true
                        });
                        video_play.play();
                    }
                },
            })
        }
    },
    refund: function (e) {
        var page = this;
        var order = page.data.order;
        wx.navigateTo({
            url: '/pages/order-refund/order-refund?order_id=' + order.id,
        })
    },
    formSubmit: function (e) {
        var page = this;
        var order = page.data.order;
        wx.showLoading({
            title: '取消提交中',
        });
        app.request({
            url: api.order.refund,
            method: 'POST',
            data: {
                order_id: order.id,
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    wx.showToast({
                        title: '取消成功',
                        icon: 'success'
                    });
                    setTimeout(function () {
                        wx.redirectTo({
                            url: '/pages/order/order?status=3',
                        })
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