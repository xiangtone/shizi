// pages/member/member.js
var app = getApp();
var api = require('../../api.js');
var is_no_more = false;
var is_loading = false;
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
        var page = this;
        var user_info = wx.getStorageSync('user_info');
        page.setData({
            user_info: user_info
        });
        is_loading = false;
        is_no_more = false;
        if (is_loading) {
            return;
        }
        is_loading = true;
        wx.showLoading({
            title: '加载中',
        })
        app.request({
            url: api.member.index,
            method: 'GET',
            data: {
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData(res.data);
                    wx.setStorageSync('user_info', res.data.user_info);
                }
            },
            complete: function () {
                wx.hideLoading();
                is_loading = false;
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
        if (is_no_more) {
            return;
        }
        this.loadMore();
    },

    loadMore: function (e) {
        var page = this;
        var p = page.data.page;
        if (is_loading) {
            return;
        }
        is_loading = true;
        wx.showLoading({
            title: '加载中',
        });
        app.request({
            url: api.member.index,
            method: 'GET',
            data: {
                page: (p + 1)
            },
            success: function (res) {
                if (res.code == 0) {
                    var list = page.data.list.concat(res.data.list);
                    page.setData({
                        list: list,
                        page: (p + 1),
                        page_count: res.data.page_count
                    });
                    if (res.data.list.length == 0) {
                        is_no_more = true;
                    }
                }
            },
            complete: function () {
                wx.hideLoading();
                is_loading = false;
            }
        });
    },
    submit: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var list = page.data.list;
        wx.showLoading({
            title: '加载中',
        })
        app.request({
            url: api.order.member_order,
            method: 'POST',
            data: {
                level_id: list[index].id,
            },
            success: function (res) {
                if (res.code == 0) {
                    app.request({
                        url: api.order.get_member_data,
                        method: 'POST',
                        data: {
                            order_id: res.data,
                            pay_type: 'WECHAT_PAY'
                        },
                        success: function (result) {
                            wx.hideLoading();
                            if (result.code == 0) {
                                var pay_data = result.data;
                                wx.requestPayment({
                                    timeStamp: pay_data.timeStamp,
                                    nonceStr: pay_data.nonceStr,
                                    package: pay_data.package,
                                    signType: pay_data.signType,
                                    paySign: pay_data.paySign,
                                    success: function (res) {
                                        wx.showToast({
                                            title: '订单支付成功',
                                            icon: 'success'
                                        });
                                        setTimeout(function () {
                                            wx.redirectTo({
                                                url: '/pages/member/member',
                                            })
                                        }, 2000)
                                    },
                                    fail: function (res) {
                                        wx.showToast({
                                            title: '订单未支付',
                                            image: '/images/icon-warning.png'
                                        });
                                    }
                                });
                            } else {
                                wx.showModal({
                                    title: '提示',
                                    content: result.msg,
                                    showCancel: false
                                });
                            }
                        }
                    });
                } else {
                    wx.hideLoading();
                    wx.showModal({
                        title: '警告',
                        content: res.msg,
                        showCancel: false
                    })
                }
            }
        });
    }
})