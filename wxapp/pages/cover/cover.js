var app = getApp();
var api = require('../../api.js');
var util = require('../../utils/util.js');
Page({
  data: {
    setting: [],
    shareinfo: [],
    app_uid: "",
    parentid: "",
    url: "",
    show_app: !1,
    show_btn: !1
  },
  onLoad: function (options) {
    app.pageOnLoad(this);
    app.storeChannel(options)
  },
  onShareAppMessage: function(res) {
    var user_info = wx.getStorageSync('user_info');
    var result = {
      title: "",
      path: "/pages/cover/cover",
      success: function(res) {},
      fail: function(res) {
        // 转发失败
      }
    };
    if (user_info && user_info.id) {
      result.path = "/pages/cover/cover?fd=" + user_info.id
    }
    return result;
  },
  onShow: function() {
    let t = this
    app.request({
      url: api.user.index,
      methos: 'POST',
      success: function(res) {
        console.log(res)
        if (res.data.user_info) {
          t.data.last_video = res.data.user_info.last_video
          wx.setStorageSync('user_info', res.data.user_info)
        }
      }
    });
  },
  GoIndex: function() {
    wx.navigateTo({
      url: '/pages/index/index',
    })
  },
  GoUser: function() {
    wx.navigateTo({
      url: '/pages/user/user',
    })
  },
  GoCat: function() {
    wx.navigateTo({
      url: '/pages/cat/cat',
    })
  },
  GoClass: function() {
    wx.navigateTo({
      url: '/pages/class/class',
    })
  },
  GoLesson: function() {
    if (this.data.last_video) {
      wx.navigateTo({
        url: '/pages/video1/video1?id=' + this.data.last_video,
      })
    } else {
      wx.navigateTo({
        url: '/pages/cat/cat',
      })
    }
  },
});