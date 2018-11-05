// pages/video/video.js
var app = getApp();
var api = require('../../api.js');
var comment = require('../../commons/comment/comment.js');
var share = require('../../commons/share/share.js');
var is_loading_more = false;
var is_no_more = false;
var WxParse = require('../../wxParse/wxParse.js');
var util = require('../../utils/util.js');
Page({

    /**
     *
     * 页面的初始数据
     */
    data: {
        isShowToast: false,
        collect_loading: false,
        c_id: 0,
        img_list: [],
        content: '',
        comment_count: 0,
        goods_loading: -1,
        page: 1,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        is_loading_more = false;
        is_no_more = false;
        var id = options.id;
        if (options.scene) {
            id = decodeURIComponent(options.scene);
        }
        var page = this;
        var time = util.formatdate(new Date());
        page.setData({
            video_id: id,
            time: time,
            store: wx.getStorageSync('store')
        });
        comment.init(page);
        share.init(page);
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
        var user_info = wx.getStorageSync('user_info');
        if (user_info.is_member == 1) {
            page.setData({
                show_modal: false,
            });
        }
        var video_id = page.data.video_id;
        app.request({
            url: api.user.video,
            data: {video_id: video_id},
            success: function (res) {
                page.loadComment(video_id);
                if (res.code == 0) {
                    var video = res.data.video;
                    if (video.status == 1) {
                        wx.showModal({
                            title: '提示',
                            content: '该内容已下架',
                            showCancel: false,
                            confirmText: '去首页',
                            success: function (res) {
                                if (res.confirm) {
                                    wx.switchTab({
                                        url: '/pages/index/index',
                                    })
                                }
                            }
                        })
                    } else {
                        var video = wx.createVideoContext('video');
                        var content = res.data.video.detail + '<span> </span>'
                        WxParse.wxParse("detail", "html", content, page, 5);
                        var video_coupons = res.data.video_coupon;
                        var video_coupon = [];
                        for (var i in video_coupons) {
                            video_coupon.push(video_coupons[i])
                        }
                        var ts = Math.round(new Date().getTime() / 1000).toString();
                        var list = [];
                        for (var i in video_coupon) {
                            list.push(video_coupon[i]);
                        }
                        video_coupon = list;
                        var length = video_coupon.length;
                        var draw_type = [];
                        for (var i = 0; i < length; i++) {
                            if (video_coupon[i].expire_type == 2) {
                                if (video_coupon[i].end_time < ts) {
                                    delete video_coupon[i]
                                }
                            }
                            if (video_coupon[i].draw_type == 2) {
                                draw_type.push(video_coupon[i]);
                            }
                        }
                        if (draw_type) {
                            var video_coupon_length = draw_type.length;
                            page.setData({
                                buy_coupon_length: video_coupon_length,
                                video_coupon_length: video_coupon_length - 2,
                                draw_type: draw_type,
                                flag: 0,
                            })
                        }
                        page.setData({
                            video: res.data.video,
                            video_list: res.data.video_list,
                            video_id: res.data.video.id,
                            video_pay: res.data.video_pay,
                            video_coupon: video_coupon,
                        });
                        wx.setNavigationBarTitle({
                            title: res.data.video.title,
                        })
                    }
                }
            },
            complete: function (res) {
            }
        });
    },
    chakan: function () {
        this.setData({
            flag: 1,
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
        if (this.data.page == this.data.page_count) {
            return;
        }
        this.loadMoreComment();
    },
    load: function () {
        if (this.data.page == this.data.page_count) {
            return;
        }
        this.loadMoreComment();
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function (res) {
        var video = this.data.video;
        var result = {
            title: video.title,
            path: "/pages/video/video?id=" + video.id,
            imageUrl: video.pic_url,
            success: function (res) {
                wx.showToast({
                    title: '转发成功',
                });
            },
            fail: function (res) {
                // 转发失败
            }
        };
        return result;
    },

    // 领取优惠券
    coupon_receive: function (e) {
        var page = this;
        var data = e.currentTarget.dataset;
        var video_coupon = page.data.video_coupon;
        var video_coupon_check = video_coupon.find(function (v) {
            return v.id == data.id
        })
        if (video_coupon_check.total_count <= 0) {
            wx.showToast({
                title: '优惠券已领取完',
                image: '/images/icon-warning.png'
            });
            return
        }
        video_coupon_check.total_count -= 1;
        wx.showLoading({
            title: '领取中',
            mask: true,
        })
        app.request({
            url: api.user.coupon_receive,
            method: "GET",
            data: {
                coupon_id: data.id
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {

                    video_coupon_check.num = parseInt(video_coupon_check.num);
                    video_coupon_check.num += 1;
                    if (video_coupon_check.user_num == video_coupon_check.num) {
                        video_coupon_check.type = 1;
                    }
                    page.setData({
                        video_coupon: video_coupon,
                    });
                    wx.showToast({
                        title: "已领取",
                    });
                } else {
                    wx.showModal({
                        content: res.msg,
                    });
                }
            }
        });
    },
    buyNow: function (option) {
        var page = this;
        var data = option.currentTarget.dataset;
        var video_coupon = page.data.draw_type;
        var video_coupon_check = video_coupon.find(function (v) {
            return v.id == data.id
        })
        if (video_coupon_check.total_count <= 0) {
            wx.showToast({
                title: '优惠券已领取完',
                image: '/images/icon-warning.png'
            });
            return
        }
        video_coupon_check.total_count -= 1;
        wx.showLoading({
            title: '提交中',
        })
        app.request({
            url: api.order.video_coupon,
            method: 'POST',
            data: {
                video_coupon_id: video_coupon_check.coupon_id,
                price: video_coupon_check.coupon_price,
                video_id: video_coupon_check.video_id,
                coupon_id: video_coupon_check.id
            },
            success: function (res) {
                if (res.code == 0) {
                    app.request({
                        url: api.order.get_pay_data,
                        method: 'POST',
                        data: {
                            order_id: res.data,
                            pay_type: 'WECHAT_PAY',
                            pay_data_type: 'video_coupon'
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
                                            video_coupon_check.num += 1;
                                            if (video_coupon_check.user_num == video_coupon_check.num) {
                                                video_coupon_check.type = 1;
                                            }
                                            var count = video_coupon_check.count;
                                            var percentage = video_coupon_check.percentage;
                                            var percentageint = parseInt(percentage);
                                            var c = (parseInt(1) / parseInt(count)) * 100;
                                            video_coupon_check.percentage = (percentageint - c);
                                            video_coupon_check.percentage = Math.round(video_coupon_check.percentage)
                                            video_coupon_check.percentage = video_coupon_check.percentage + '%'
                                            page.setData({
                                                draw_type: video_coupon,
                                            });
                                            wx.setStorageSync("video_coupon", video_coupon);
                                            console.log(video_coupon)
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

    collect: function (option) {
        var page = this;
        var video = page.data.video;
        page.setData({
            collect_loading: true
        });
        app.request({
            url: api.user.collect,
            method: "GET",
            data: {video_id: video.id},
            success: function (res) {
                if (res.code == 0) {
                    var title = "已收藏";
                    if (res.data.collect == 1) {
                        title = "取消收藏"
                    }
                    video.collect = res.data.collect;
                    video.collect_count = res.data.collect_count;
                    page.setData({
                        video: video,

                        toast_text: title,
                        isShowToast: true,
                        collect_loading: false
                    });
                    setTimeout(function () {
                        page.setData({
                            isShowToast: false
                        });
                    }, 1500);
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                    })
                }
            },
            complete: function (res) {
                page.setData({
                    collect_loading: false
                });
            }
        });
    },
    play: function (options) {
        wx.redirectTo({
            url: '/pages/video/video?id=' + options.currentTarget.dataset.id,
        })
    },
    loadComment: function (id) {
        var page = this;
        is_loading_more = true;
        app.request({
            url: api.user.comment_list,
            data: {
                video_id: id,
                page: 1
            },
            success: function (res) {
                if (res.code == 0) {
                    page.setData({
                        comment_list: res.data.list,
                        comment_count: res.data.row_count,
                        page_count: res.data.page_count,
                        cc_count: res.data.cc_count
                    });
                }
            },
            fail: function (res) {
                wx.showModal({
                    title: '警告',
                    content: res.msg,
                    showCancel: false
                })
            },
            complete: function () {
                wx.hideLoading();
                is_loading_more = false;
            }
        });
    },
    loadMoreComment: function () {
        var page = this;
        var comment_list = page.data.comment_list;
        var p = page.data.page;
        if (is_loading_more) {
            return;
        }
        is_loading_more = true;
        wx.showLoading({
            title: '加载中',
        });
        app.request({
            url: api.user.comment_list,
            data: {
                video_id: page.data.video.id,
                page: (p + 1)
            },
            success: function (res) {
                wx.hideLoading();
                if (res.code == 0) {
                    if (res.data.list.length > 0) {
                        comment_list = comment_list.concat(res.data.list);
                        page.setData({
                            page: (p + 1),
                            comment_list: comment_list,
                            page_count: res.data.page_count,
                            row_count: res.data.row_count,
                            cc_count: res.data.cc_count
                        });
                    } else {
                        is_no_more = true;
                    }
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                    });
                }
            },
            complete: function () {
                is_loading_more = false;
            }
        });
    },
    thump: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var comment_list = page.data.comment_list;
        var id = comment_list[index].id;
        var thump_count = comment_list[index].thump_count;
        page.setData({
            thump_loading: index
        });

        app.request({
            url: api.user.thump,
            data: {
                c_id: id
            },
            success: function (res) {
                if (res.code == 0) {
                    var thump = !comment_list[index].thump;
                    var title = '取消点赞';
                    if (thump) {
                        title = '点赞成功';
                        thump_count++;
                    } else {
                        thump_count--
                    }
                    comment_list[index].thump = thump;
                    comment_list[index].thump_count = thump_count;
                    page.setData({
                        comment_list: comment_list,
                        toast_text: title,
                        isShowToast: true
                    });
                    setTimeout(function () {
                        page.setData({
                            isShowToast: false
                        });
                    }, 1500);
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    })
                }
            },
            complete: function () {
                page.setData({
                    thump_loading: -1
                });
            }
        });
    },
    previewImg: function (e) {
        var page = this;
        var comment_list = page.data.comment_list;
        var index = e.currentTarget.dataset.index;
        var i = e.currentTarget.dataset.i;
        var img_list = comment_list[index].img;
        var current = img_list[i];
        wx.previewImage({
            current: current,
            urls: img_list,
        })
    },
    previewImg_c: function (e) {
        var page = this;
        var comment_list = page.data.comment_list;
        var index = e.currentTarget.dataset.index;
        var i = e.currentTarget.dataset.i;
        var pic = e.currentTarget.dataset.pic;
        var img_list = comment_list[index].children[i].img;
        var current = img_list[pic];
        wx.previewImage({
            current: current,
            urls: img_list,
        })
    },
    formInput: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        var video = page.data.video
        var form_list = video.form_list;
        form_list[index].value = e.detail.value;
        video.form_list = form_list;
        page.setData({
            video: video
        });
    },
    formSubmit: function (e) {
        var page = this;
        var formId = e.detail.formId;
        wx.showModal({
            title: '提示',
            content: '是否立即预约？',
            success: function (e) {
                if (e.confirm) {
                    var video = page.data.video;
                    var form_list = video.form_list;
                    for (var i in form_list) {
                        if (form_list[i].required == 1) {
                            if (!form_list[i].value || form_list[i].value == undefined) {
                                wx.showToast({
                                    title: '请输入' + form_list[i].name,
                                    image: '/images/icon-warning.png'
                                })
                                return;
                            }
                        }
                    }
                    wx.showLoading({
                        title: '提交中',
                    });
                    app.request({
                        url: api.order.prew,
                        method: 'POST',
                        data: {
                            formId: formId,
                            form_list: JSON.stringify(form_list),
                            video_id: video.id,
                            price: parseFloat(video.money)
                        },
                        success: function (res) {
                            if (res.code == 0) {
                                if (video.money == 0) {
                                    wx.hideLoading();
                                    wx.redirectTo({
                                        url: '/pages/order/order?status=1',
                                    });
                                } else {
                                    app.request({
                                        url: api.order.get_pay_data,
                                        method: 'POST',
                                        data: {
                                            order_id: res.data,
                                            pay_type: 'WECHAT_PAY',
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
                                                                url: '/pages/order/order?status=1',
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
                                }
                            } else {
                                wx.hideLoading();
                                wx.showToast({
                                    title: res.msg,
                                    image: '/images/icon-warning.png'
                                });
                            }
                        }
                    });
                }
            }
        })
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
    pause: function () {
        var page = this;
        var video_play = wx.createVideoContext('video');
        video_play.pause();
        page.setData({
            play: false
        });
    },
    timeupdate: function (e) {
        var page = this;
        var video_pay = page.data.video_pay;
        if (page.data.video.is_pay == 0) {
            return;
        }
        var video = wx.createVideoContext('video');
        if (e.detail.currentTime >= video_pay.time) {
            video.seek(0);
            video.pause();
            page.setData({
                play: false
            });
        }
    },
    buyVideo: function () {
        var page = this;
        var video = page.data.video;
        wx.showModal({
            title: '提示',
            content: '确认购买？',
            success: function (e) {
                if (e.confirm) {
                    wx.showLoading({
                        title: '提交中',
                    })
                    app.request({
                        url: api.order.video,
                        method: 'POST',
                        data: {
                            video_id: video.id,
                            price: page.data.video_pay.price
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
                                                        page.setData({
                                                            video: video
                                                        });
                                                        page.playVideo();
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
                                wx.showModal({
                                    title: '警告',
                                    content: res.msg,
                                    showCancel: false
                                })
                            }
                        }
                    });
                }
            }
        })
    },
    showModal: function () {
        var page = this;
        page.setData({
            show_modal: true
        });
    },
    closeModal: function () {
        this.setData({
            show_modal: false
        });
    },
    buyVip: function () {
        wx.navigateTo({
            url: '/pages/member/member',
        })
    },
    backIndex: function (e) {
        wx.switchTab({
            url: '/pages/index/index',
        })
    }
})