//index.js
//获取应用实例
var app = getApp();
var api = require('../../api.js');
var is_loading_more = false;
var is_no_more = false;
var share = require('../../commons/share/share.js');
Page({
    data: {
        hide: -1,
        page: 1,
        collect_loading: -1
    },
    video: '',
    onLoad: function (options) {
        app.pageOnLoad(this);
        is_loading_more = false;
        is_no_more = false;
        var store = wx.getStorageSync("store");
        var page = this;
        if (store && store.name) {
            wx.setNavigationBarTitle({
                title: store.name,
            });
            page.setData({
                store: store
            });
        }
        app.getCallBack = function (res) {
            if (typeof res == 'object') {
                page.setData(res);
            }
            if (res.store && res.store.name) {
                wx.setNavigationBarTitle({
                    title: res.store.name,
                });
            }
        }
    },
    onShow: function () {
        app.pageOnShow(this);
        var page = this;
        share.init(page);
        if (page.data.share_show) {
            return;
        }
        this.loadData();
    },

    loadData: function () {
        var page = this;
        var user_info = wx.getStorageSync("user_info");
        app.request({
            url: api.default.index,
            success: function (res) {
                if (res.code == 0) {
                    page.setData(res.data);
                }
            }
        });
        wx.showLoading({
            title: '正在加载',
        });
        is_loading_more = true;
        app.request({
            url: api.user.video_list,
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        video_list: res.data.list,
                    });
                }
            }, complete: function () {
                wx.hideLoading();
                wx.stopPullDownRefresh();
                is_loading_more = false;
            }
        });
    },
    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        app.pageOnHide(this);
        var page = this;
        var index = page.data.hide;
        if (index == -1) {
            return;
        }
        page.video.seek(0);
        page.video.pause();
        page.setData({
            hide: -1
        });
    },
    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {
        if (this.data.share_show) {
            wx.stopPullDownRefresh();
            return;
        }
        app.request({
            url: api.user.index,
            methos: 'POST',
            success: function (res) {
                wx.setStorageSync('user_info', res.data)
            }
        });
        app.getStore();
        var store = wx.getStorageSync('store');
        this.setData({
            page: 1,
            store: store
        });
        is_no_more = false;
        this.loadData();
    },
    play: function (option) {
        var page = this;
        var index = option.currentTarget.dataset.index;
        var video_list = page.data.video_list;
        var video = video_list[index];
        if (video.is_pay == 1) {
            var text = video.pay.time != 0 ? "抢先试看" : "取消";
            wx.showModal({
                title: '提示',
                content: '付费内容，需要￥' + video.pay.price + '购买才可以观看！',
                cancelText: text,
                confirmText: '立即购买',
                success: function (e) {
                    if (e.confirm) {
                        page.buyVideo(option);
                    }
                    if (e.cancel) {
                        if (video.pay.time != 0) {
                            page.is_wifi(option);
                        }
                    }
                }
            })
            return;
        }
        page.is_wifi(option);
    },
    is_wifi: function (option) {
        var page = this;
        wx.getNetworkType({
            success: function (res) {
                if (res.networkType != 'wifi') {
                    wx.showModal({
                        title: '提示',
                        content: '当前网络状态不是WiFi，是否播放',
                        success: function (e) {
                            if (e.confirm) {
                                page.play_1(option);
                            }
                        }
                    })
                } else {
                    page.play_1(option);
                }
            },
        })
    },
    play_1: function (option) {
        var page = this;
        var index = page.data.hide;
        if (index != -1) {
            page.video.seek(0);
            page.video.pause();
        }
        var new_index = option.currentTarget.dataset.index;
        page.setData({
            hide: new_index,
            show_buy: false
        });
        page.video = wx.createVideoContext("video_" + new_index);
        new_video.play();
    },

    play_2: function (e) {
        var page = this;
        var hide = page.data.hide;
        var index = e.currentTarget.dataset.index;
        if (index != hide) {
            page.video.pause();
        }
    },
    loadMoreGoodsList: function () {
        var page = this;
        if (is_loading_more)
            return;
        page.setData({
            show_loading_bar: true,
        });
        is_loading_more = true;
        var p = page.data.page;
        app.request({
            url: api.user.video_list,
            method: "GET",
            data: {
                page: p,
            },
            success: function (res) {
                if (res.data.list.length == 0)
                    is_no_more = true;
                var video_list = page.data.video_list.concat(res.data.list);
                page.setData({
                    video_list: video_list,
                    page: (p + 1),
                });
            },
            complete: function () {
                is_loading_more = false;
                page.setData({
                    show_loading_bar: false,
                });
            }
        });
    },
    more: function (option) {
        var index = option.currentTarget.dataset.index;
        var page = this;
        var show = page.data.show;
        var video_list = page.data.video_list;
        if (video_list[index].show != -1) {
            video_list[index].show = -1;
            page.setData({
                video_list: video_list,
            });
        } else {
            video_list[index].show = 1;
            page.setData({
                video_list: video_list,
            });
        }
    },
    collect: function (option) {
        var page = this;
        var index = option.currentTarget.dataset.index;
        var video_list = page.data.video_list;
        page.setData({
            collect_loading: index
        });
        app.request({
            url: api.user.collect,
            method: "GET",
            data: { video_id: video_list[index].id },
            success: function (res) {
                if (res.code == 0) {
                    video_list[index].collect = res.data.collect;
                    video_list[index].collect_count = res.data.collect_count;
                    page.setData({
                        video_list: video_list,
                        collect_loading: -1
                    });
                    var title = "取消收藏";
                    if (res.data.collect == 0) {
                        title = "已收藏";
                    }
                    wx.showToast({
                        title: title,
                    });
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                    })
                }
            },
            complete: function (res) {
                page.setData({
                    collect_loading: -1
                });
            }
        });
    },
    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

        var page = this;
        if (is_no_more)
            return;
        page.loadMoreGoodsList();
    },
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function (res) {
        var result = {
            title: "",
            path: "/pages/index/index",
            success: function (res) {
                wx.showToast({
                    title: '转发成功',
                });
            },
            fail: function (res) {
                // 转发失败
            }
        };
        if (res.from == 'button') {
            var share = this.data.share;
            result.title = share.title;
            result.path = "/pages/video/video?id=" + share.id;
            result.imageUrl = share.pic_url;
        }
        return result;
    },
    videoplay: function (options) {
        wx.navigateTo({
            url: '/pages/video/video?id=' + options.currentTarget.dataset.id,
        })
    },
    comment: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var video_list = page.data.video_list;
        var user_info = wx.getStorageSync('user_info');
        wx.navigateTo({
            url: '/pages/comment/comment?video_id=' + video_list[index].id,
        })
    },
    bannerTo: function (e) {
        var page = this;
        var url = e.currentTarget.dataset.url;
        if (!url) {
            return;
        }
        wx.navigateTo({
            url: url,
            fail: function (e) {
                wx.switchTab({
                    url: url,
                })
            }
        })
    },
    pause: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        page.video.pause();
        page.setData({
            hide: -1
        });
    },
    gotocat: function (e) {
        var index = e.currentTarget.dataset.index;
        var cat = this.data.cat_list[index];
        wx.navigateTo({
            url: '/pages/cat-second/cat-second?cat_id=' + cat.id,
        })
    },
    gotoadvertisement: function (e) {
        var appid = this.data.advertisement.appid;
        var path = this.data.advertisement.path;
        wx.navigateToMiniProgram({
            appId: appid,
            path: path,
            extraData: {
                foo: 'bar'
            },
            envVersion: 'develop',
            success(res) {
                // 打开成功
            }
        })
    },
    timeUpdate: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var video = page.data.video_list[index];
        if (video.is_pay == 0) {
            return;
        }
        if (e.detail.currentTime >= video.pay.time) {
            page.video.seek(0);
            page.video.pause();
            page.setData({
                show_buy: true
            });
        }
    },
    buyVideo: function (option) {
        var page = this;
        var index = option.currentTarget.dataset.index;
        var video_list = page.data.video_list;
        var video = video_list[index];
        wx.showLoading({
            title: '提交中',
        })
        app.request({
            url: api.order.video,
            method: 'POST',
            data: {
                video_id: video.id,
                price: video.pay.price
            },
            success: function (res) {
                if (res.code == 0) {
                    app.request({
                        url: api.order.get_pay_data,
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
                                            video.is_pay = 0;
                                            video_list[index] = video;
                                            page.setData({
                                                video_list: video_list
                                            });
                                            page.play(option);
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
                                    title: '警告',
                                    content: result.msg,
                                    showCancel: false
                                });
                            }
                        },
                    });
                } else {
                    wx.showModal({
                        title: '警告',
                        content: res.msg,
                        showCancel: false
                    })
                }
            }
        });
    },
    backTop: function (e) {
        if (wx.pageScrollTo) {
            wx.pageScrollTo({
                scrollTop: 0,
                duration: 300
            })
        } else {
            // 如果希望用户在最新版本的客户端上体验您的小程序，可以这样子提示
            wx.showModal({
                title: '提示',
                content: '当前微信版本过低，无法使用该功能，请升级到最新微信版本后重试。'
            })
        }
    }
})
