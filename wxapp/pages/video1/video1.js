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
    tryEnd:false,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
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
    const systemInfo = wx.getSystemInfoSync()
    if (systemInfo.system && systemInfo.system.toUpperCase().indexOf('IOS')!=-1){
      page.setData({
        isIOS :true,
      })
    }
  },


  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {
    app.pageOnReady(this);

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    app.pageOnShow(this);
    if (app.checkLoginWithoutRedirect()) {
      this.setData({
        isLogin: true
      })
    }
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
    console.log("用户信息=", user_info);
    var video_id = page.data.video_id;
    //获取视频相关信息 视频价格
    app.request({
      url: api.user.video,
      data: {
        video_id: video_id
      },
      success: function(res) {
        console.log('视频相关信息', res);
        page.loadComment(video_id);
        if (res.code == 0) {
          var video = res.data.video;
          if (video.status == 1) {
            wx.showModal({
              title: '提示',
              content: '该内容已下架',
              showCancel: false,
              confirmText: '去首页',
              success: function(res) {
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
            if (res.data.video.ex_types) {
              let splitArr2 = res.data.video.ex_types.split(',')
              for (let j in splitArr2) {
                res.data.video["e" + splitArr2[j]] = true
              }
            }
            page.setData({
              video: res.data.video,
              storeFromVideo: res.data.store,
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
      complete: function(res) {}
    });
  },
  chakan: function() {
    this.setData({
      flag: 1,
    });
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {
    app.pageOnHide(this);

    var page = this;
    var old = wx.createVideoContext("video");
    old.seek(0);
    old.pause();
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {
    app.pageOnUnload(this);

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {
    if (this.data.page == this.data.page_count) {
      return;
    }
    this.loadMoreComment();
  },
  load: function() {
    if (this.data.page == this.data.page_count) {
      return;
    }
    this.loadMoreComment();
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function(res) {
    var video = this.data.video;
    var result = {
      title: video.title,
      path: "/pages/video1/video1?id=" + video.id,
      imageUrl: video.pic_url,
      success: function(res) {
        wx.showToast({
          title: '转发成功',
        });
      },
      fail: function(res) {
        // 转发失败
      }
    };
    return result;
  },

  // 领取优惠券
  coupon_receive: function(e) {
    var page = this;
    var data = e.currentTarget.dataset;
    var video_coupon = page.data.video_coupon;
    var video_coupon_check = video_coupon.find(function(v) {
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
      success: function(res) {
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
  collect: function(option) {
    var page = this;
    var video = page.data.video;
    page.setData({
      collect_loading: true
    });
    app.request({
      url: api.user.collect,
      method: "GET",
      data: {
        video_id: video.id
      },
      success: function(res) {
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
          setTimeout(function() {
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
      complete: function(res) {
        page.setData({
          collect_loading: false
        });
      }
    });
  },
  play: function(options) {
    wx.redirectTo({
      url: '/pages/video/video?id=' + options.currentTarget.dataset.id,
    })
  },
  loadComment: function(id) {
    var page = this;
    is_loading_more = true;
    app.request({
      url: api.user.comment_list,
      data: {
        video_id: id,
        page: 1
      },
      success: function(res) {
        if (res.code == 0) {
          page.setData({
            comment_list: res.data.list,
            comment_count: res.data.row_count,
            page_count: res.data.page_count,
            cc_count: res.data.cc_count
          });
        }
      },
      fail: function(res) {
        wx.showModal({
          title: '警告',
          content: res.msg,
          showCancel: false
        })
      },
      complete: function() {
        wx.hideLoading();
        is_loading_more = false;
      }
    });
  },
  loadMoreComment: function() {
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
      success: function(res) {
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
      complete: function() {
        is_loading_more = false;
      }
    });
  },
  thump: function(e) {
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
      success: function(res) {
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
          setTimeout(function() {
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
      complete: function() {
        page.setData({
          thump_loading: -1
        });
      }
    });
  },
  previewImg: function(e) {
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
  previewImg_c: function(e) {
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
  pause: function() {
    var page = this;
    var video_play = wx.createVideoContext('video');
    video_play.pause();
    page.setData({
      play: false
    });
  },
  timeupdate: function(e) {
    var page = this;
    var video_pay = page.data.video_pay;
    if (page.data.video.is_pay == 0 || video_pay.time==0) {
      return;
    }
    var video = wx.createVideoContext('video');
    if (e.detail.currentTime >= video_pay.time) {
      video.seek(0);
      video.pause();
      page.setData({
        play: false,
        tryEnd:true,
      });
    }
  },
  /**
   * 购买视频
   */
  buyVideo: function() {
    var page = this;
    var video = page.data.video;
    wx.showModal({
      title: '提示',
      content: '确认购买？',
      success: function(e) {
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
            success: function(res) {
              if (res.code == 0) {
                app.request({
                  url: api.order.get_pay_data,
                  method: 'POST',
                  data: {
                    order_id: res.data,
                    pay_type: 'WECHAT_PAY'
                  },
                  success: function(result) {
                    wx.hideLoading();
                    if (result.code == 0) {
                      var pay_data = result.data;
                      wx.requestPayment({
                        timeStamp: pay_data.timeStamp,
                        nonceStr: pay_data.nonceStr,
                        package: pay_data.package,
                        signType: pay_data.signType,
                        paySign: pay_data.paySign,
                        success: function(res) {
                          wx.showToast({
                            title: '订单支付成功',
                            icon: 'success'
                          });
                          setTimeout(function() {
                            video.is_pay = 0;
                            page.setData({
                              video: video
                            });
                            page.playVideo();
                          }, 2000)
                        },
                        fail: function(res) {
                          wx.showToast({
                            title: '订单未支付3',
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
            },
            complete: function () {
              wx.hideLoading();
            }
          });
        }
      }
    })
  },
  login: function (e) {
    var page = this;
    var data = e.detail;
    console.log('user login', e);
    if (data.errMsg == 'getUserInfo:fail auth deny') {
      wx.hideLoading();
      wx.showModal({
        title: '提示',
        content: '登录失败',
        showCancel: false
      })
      return;
    }
    if (data.errMsg != 'getUserInfo:ok') {
      wx.hideLoading();
      wx.showModal({
        title: '提示',
        content: '登录失败' + data.errMsg,
        showCancel: false
      })
      return;
    }
    wx.showLoading({
      title: "正在登陆",
      mask: true
    });
    wx.login({
      success: function (res) {
        if (res.code) {
          var code = res.code;
          console.log(res)
          let fd = wx.getStorageSync("fd");
          console.info('login fd', fd)
          let cd = wx.getStorageSync("cd");
          console.info('login cd', cd)
          app.request({
            url: api.passport.login,
            method: "POST",
            data: {
              code: code,
              user_info: data.rawData,
              encrypted_data: data.encryptedData,
              iv: data.iv,
              signature: data.signature,
              fd: fd,
              cd: cd,
            },
            success: function (result) {
              console.log('===>1212 result', result)
              wx.hideLoading();
              if (result.code == 0) {
                wx.setStorageSync("access_token", result.data.access_token);
                wx.setStorageSync("user_info", result.data);
                page.setData({
                  user_info: result.data
                });
                page.buyCat();
              } else {
                wx.showModal({
                  title: '警告，请重试',
                  content: result.msg,
                  showCancel: false,
                })
              }
            }
          });
        } else {
          wx.showToast({
            title: res.msg
          });
        }
      },
      fail: function (e) {
        console.log(e);
      }
    });
  },
  /**
   * 购买分类
   */
  buyCat: function() {
    console.log('arguments', arguments)
    var page = this;
    var video = page.data.video;
    console.log('buyCat-->cat_id' + video.cat_id);
    console.log('buyCat-->price' + page.data.video_pay.price);
    console.log('buyCat-->video_id' + video.id);
    wx.showModal({
      title: '提示',
      content: '确认购买？',
      success: function(e) {
        if (e.confirm) {
          wx.showLoading({
            title: '提交中',
          })
          app.request({
            url: api.order.cat,
            method: 'POST',
            data: {
              cat_id: video.cat_id,
              video_id: video.id,
              price: page.data.video_pay.price
            },
            success: function(res) {
              if (res.code == 0) {
                app.request({
                  url: api.order.get_pay_data,
                  method: 'POST',
                  data: {
                    order_id: res.data,
                    pay_type: 'WECHAT_PAY'
                  },
                  success: function(result) {
                    wx.hideLoading();
                    if (result.code == 0) {
                      var pay_data = result.data;
                      wx.requestPayment({
                        timeStamp: pay_data.timeStamp,
                        nonceStr: pay_data.nonceStr,
                        package: pay_data.package,
                        signType: pay_data.signType,
                        paySign: pay_data.paySign,
                        success: function(res) {
                          wx.showToast({
                            title: '订单支付成功',
                            icon: 'success'
                          });
                          setTimeout(function() {
                            video.is_pay = 0;
                            page.setData({
                              video: video
                            });
                            page.playVideo();
                          }, 2000)
                        },
                        fail: function(res) {
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
                page.onShow()
              }
            },
            complete: function () {
              wx.hideLoading();
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
  goNext: function (option) {
    let page = this;
    app.request({
      url: api.user.next_video,
      data: {
        video_id: this.data.video.id,
        cat_id: this.data.video.cat_id,
        direction: option.currentTarget.dataset.direction,
        sort: this.data.video.sort,
      },
      success: function(res) {
        console.log('视频相关信息', res);
        if (res.code == 0) {
          // wx.navigateTo({
          //   url: '/pages/video1/video1?id='+res.data.next_video.id,
          // })
          page.setData({
            video_id: res.data.next_video.id,
          });
          page.onShow()
        }else{
          wx.showModal({
            title: '提示',
            content: res.msg,
            showCancel: false,
            confirmText: '确认',
            success: function (res) {
            }
          })
        }
      },
      complete: function(res) {}
    });
  },
  charRead:function(){
    wx.navigateTo({
      url: '/pages/read/read' + "?video_id=" + this.data.video.id ,
    })
  },
  redicalGame: function () {
    wx.navigateTo({
      url: '/pages/redical/redical' + "?video_id=" + this.data.video.id,
    })
  },
  cyllkGame: function() {
    //console.log("词语连连看webview-->>");
    var page = this;
    if (app.checkLogin() == true) {
      //console.log("已经登陆过了");
      var video = page.data.video;
      var user_info = wx.getStorageSync('user_info');
      var redirect_url = '/pages/zuci-web-view/zuci-web-view' + "?video_id=" + video.id + "&user_id=" + user_info.id;
      //console.log("跳转地址->"+redirect_url);
      wx.navigateTo({
        url: redirect_url,
      })

    } else {
      console.log("去登陆");
    }
  },
  wlcgGame: function() {
    console.log("我来闯关webview-->>");
    var page = this;
    if (app.checkLogin() == true) {
      //console.log("已经登陆过了");
      var video = page.data.video;
      var user_info = wx.getStorageSync('user_info');
      var redirect_url = '/pages/zuju-web-view/zuju-web-view' + "?video_id=" + video.id + "&user_id=" + user_info.id;
      //console.log("跳转地址->"+redirect_url);
      wx.navigateTo({
        url: redirect_url,
      })

    } else {
      console.log("去登陆");
    }
  },
  showModal: function() {
    var page = this;
    page.setData({
      show_modal: true
    });
  },
  closeModal: function() {
    this.setData({
      show_modal: false
    });
  },
  buyVip: function() {
    wx.navigateTo({
      url: '/pages/member/member',
    })
  },
  backIndex: function(e) {
    wx.redirectTo({
      url: '/pages/cover/cover',
    })
  }
})