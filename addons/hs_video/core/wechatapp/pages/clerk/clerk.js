// pages/clerk/clerk.js
var app = getApp();
var api = require('../../api.js');
var WxParse = require('../../wxParse/wxParse.js');
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
            scene: decodeURIComponent(options.scene)
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
        app.request({
            url: api.order.clerk_detail,
            method: 'GET',
            data: {
                order_no: page.data.scene
            },
            success: function (res) {
                if (res.code == 0) {
                    var content = res.data.video.detail + '<span> </span>'
                    WxParse.wxParse("detail", "html", content, page, 5);
                    page.setData(res.data);
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                wx.switchTab({
                                    url: '/pages/index/index',
                                });
                            }
                        }
                    })
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
    clerk: function () {
        var page = this;
        wx.showModal({
            title: '提示',
            content: '是否核销订单',
            success: function (e) {
                if (e.confirm) {
                    wx.showLoading({
                        title: '订单核销中',
                    })
                    app.request({
                        url: api.order.clerk,
                        method: 'GET',
                        data: {
                            order_id: page.data.order.id
                        },
                        success: function (res) {
                            wx.hideLoading();
                            if(res.code == 0){
                                wx.showModal({
                                    title: '提示',
                                    content: '核销成功，即将跳转首页',
                                    showCancel:false,
                                    confirmText:'立即前往',
                                    success:function(result){
                                        if(result.confirm){
                                            wx.switchTab({
                                                url: '/pages/index/index',
                                            });
                                        }
                                    }
                                });
                                setTimeout(function () {
                                    wx.switchTab({
                                        url: '/pages/index/index',
                                    });
                                },3000);
                            }else{
                                wx.showModal({
                                    title: '提示',
                                    content: res.msg,
                                    showCancel:false
                                })
                            }
                        }
                    });
                }
            }
        })
    }
})