// pages/user-binding/user-binding.js
var app = getApp();
var api = require('../../api.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    second: 60,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    app.pageOnLoad(this);
    var page = this;
    app.storeChannel(options)
    if (app.checkLoginWithoutRedirect()) {
      this.setData({
        isLogin: true
      })
    }
    app.request({
      url: api.user.class_info,
      method: 'get',
      data: {
        class_id: options.class_id,
        page: 1,
      },
      success: function(res) {
        console.log(res)
        if (res.code == 0) {
          page.setData({
            user_list: res.data.list,
            class: res.data.class,
            data: res.data
            // teacher_info:res.data.teacher_info
          });
          wx.setNavigationBarTitle({
            title: res.data.class.class_name,
          });
          page.joinStatus()
          page.checkInClass()
        } else {}
      },
    });
  },
  joinClass: function() {
    var page = this;
    console.log('try join')
    app.request({
      url: api.user.class_join,
      method: 'get',
      data: {
        class_id: this.data.class.id,
      },
      success: function(res) {
        console.log(res)
        if (res.code == 0) {
          wx.reLaunch({
            url: '/pages/class-user-list/class-user-list?class_id=' + page.data.class.id,
          })
        } else {
          wx.showModal({
            title: '提示',
            content: res.msg,
            showCancel: false,
            success: function(e) {
              if (e.confirm) {
                wx.reLaunch({
                  url: '/pages/class-user-list/class-user-list?class_id=' + page.data.class.id,
                })
              }
            }
          })
        }
      },
    });
  },
  checkInClass: function() {
    let user_info = wx.getStorageSync('user_info')
    if (app.checkLoginWithoutRedirect()) {
      for (let i in this.data.user_list) {
        if (this.data.user_list[i].id == user_info.id) {
          this.setData({
            isInClass: true,
          })
          return;
        }
      }
      this.setData({
        isInClass: false,
      })
    } else {
      this.setData({
        isInClass: false,
      })
    }
  },
  joinStatus: function() {
    let user_info = wx.getStorageSync('user_info')
    console.log(user_info);
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
    var page = this;
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {
    app.pageOnHide(this);

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

  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {
    var info = this.data.class;
    var result = {
      title: '邀请您加入' + this.data.class.class_name,
      path: "/pages/class-user-list/class-user-list?class_id=" + this.data.class.id,
      //imageUrl: video.pic_url,
      success: function(res) {
        wx.showToast({
          title: '转发成功',
        });
      },
      fail: function(res) {
        // 转发失败
      }
    };
    let user_info = wx.getStorageSync("user_info")
    if (user_info && user_info.id) {
      result.title = user_info.nickname + result.title
      result.path = result.path + "&fd=" + user_info.id
    }
    return result;
  },
  login: function(e) {
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
      success: function(res) {
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
            success: function(result) {
              console.log('===>1212 result', result)
              wx.hideLoading();
              if (result.code == 0) {
                wx.setStorageSync("access_token", result.data.access_token);
                wx.setStorageSync("user_info", result.data);
                page.setData({
                  user_info: result.data
                });
                page.joinClass();
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
      fail: function(e) {
        console.log(e);
      }
    });
  },
})