// pages/order-refund-detail/order-refund-detail.js
var app = getApp();
var api = require('../../api.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {

    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        var page = this;
        page.setData({
            order_id: options.order_id
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
            url: api.order.refund_detail,
            method: 'GET',
            data: {
                order_id: page.data.order_id
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
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

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        app.pageOnUnload(this);
        var page = this;
        var old = wx.createVideoContext("video");
        old.seek(0);
        old.pause();
    },
    playVideo: function () {
        var page = this;
        var video_play = wx.createVideoContext('video');
        var video = page.data.video;
        if (video.status == 1) {
            wx.showModal({
                title: '提示',
                content: '该视频已下架',
                showCancel: false
            })
        } else {
            wx.getNetworkType({
                success: function (res) {
                    if (res.networkType != 'wifi') {
                        wx.showModal({
                            title: '提示',
                            content: '当前网络状态不是WiFi，是否播放视频',
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
    }

})