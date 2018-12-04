// pages/user/user.js
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
  onLoad: function(options) {
    app.pageOnLoad(this);
    var contact_tel = wx.getStorageSync('contact_tel');
    this.setData({
      contact_tel: contact_tel,
      store: wx.getStorageSync("store")
    });
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
    app.request({
      url: api.user.index,
      method: 'GET',
      success: function(res) {
        if (res.code == 0) {
          page.setData({
            user_info: res.data.user_info
          });
          wx.setStorageSync('user_info', res.data.user_info);
        }
      }
    });
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
  goTeacher:function(){
    var page = this;
    app.request({
      url: api.user.teacher,
      method: 'get',
      data: {
        page: 1,
      },
      success: function (res) {
        console.log(res)
        if (res.code == -3) {
          wx.showModal({
            title: '提示',
            content: res.msg,
            confirmText: '前往',
            showCancel: false,
            success: function (res) {
              if (res.confirm) {
                wx.navigateTo({
                  url: '/pages/user-binding/user-binding',
                })
              }
            }
          })
        } else if (res.code == -4) {
          wx.showModal({
            title: '提示',
            content: res.msg,
            confirmText: '前往',
            showCancel: false,
            success: function (res) {
              if (res.confirm) {
                wx.navigateTo({
                  url: '/pages/user-teacher-edit/user-teacher-edit',
                })
              }
            }
          })
        } else if (res.code == 0) {
          if (res.data.teacher_info.status){
            wx.navigateTo({
              url: '/pages/user-teacher/user-teacher',
            })
          }else{
            wx.showModal({
              title: '提示',
              content: '等待审核',
              showCancel: false
            })
          }
        } else {
          page.setData({
            status: false,
          });
        }
      },
    });
    
  },
  tel: function(option) {
    var tel = option.currentTarget.dataset.tel;
    wx.makePhoneCall({
      phoneNumber: tel //仅为示例，并非真实的电话号码
    })
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
              } else {
                wx.showModal({
                  title: '警告，请重试',
                  content: result.msg,
                  showCancel: false
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