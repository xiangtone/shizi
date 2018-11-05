// pages/order-refund/order-refund.js
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
            order_id:options.order_id
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
            url: api.order.refund_prew,
            method: 'GET',
            data: {
                order_id: page.data.order_id
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        video: res.data.video,
                        order: res.data.order
                    });
                }
            },
            complete: function () {
                wx.hideLoading();
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
    formSubmit: function (e) {
        var page = this;
        var order = page.data.order;
        var content = page.data.content;
        if(!content || content == undefined){
            wx.showModal({
                title: '提示',
                content: '请输入退款理由',
                showCancel:false
            });
            return ;
        }
        wx.showLoading({
            title: '申请提交中'
        });
        app.request({
            url: api.order.refund,
            method: 'POST',
            data: {
                order_id: order.id,
                desc:content,
                formId:e.detail.formId
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    wx.showToast({
                        title: '申请成功',
                        icon: 'success'
                    });
                    setTimeout(function(){
                        wx.redirectTo({
                            url: '/pages/order/order?status=1',
                        })
                    },2000);
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
    formConfirm:function(e){
        var page = this;
        page.setData({
            content:e.detail.value
        });
    }
})