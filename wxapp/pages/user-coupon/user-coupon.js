// pages/user-coupon/user-coupon.js
var app = getApp();
var api = require('../../api.js');
Page({

    /**
     * 页面的初始数据
     */
    data: {},

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);

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
            title: '加载中',
        })
        app.request({
            url: api.user.user_coupon,
            method: 'GET',
            data: {
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    var ts = Math.round(new Date().getTime() / 1000).toString();
                    var user_coupon = res.data.user_coupon;
                    var length = user_coupon.length;
                    for (var i = 0; i < length; i++) {
                        if (ts > user_coupon[i].endtime) {
                            user_coupon[i].is_expire = 1;
                        }
                    }
                    page.setData({
                        user_coupon: user_coupon
                    });
                    wx.hideLoading()
                } else {
                    wx.showToast({
                        title: "网络错误",
                        image: "/images/icon-warning.png",
                    });
                }
            },
        });
    },
    coupon_qrcode: function (e) {
        var page = this;
        var data = e.currentTarget.dataset;
        app.request({
            url: api.user.user_coupon_qrcode,
            method: 'GET',
            data: {
                user_coupon_id: data.id
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        user_coupon_qrcode: res.data.pic_url,
                        showModel: true
                    });
                } else {
                    wx.showToast({
                        title: "网络错误",
                        image: "/images/icon-warning.png",
                    });
                }
            },
        });

    },
    preventTouchMove: function () {
    },
    hideModal: function () {
        this.setData({
            showModel: false
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

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {

    }
})