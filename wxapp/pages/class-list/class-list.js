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
    app.request({
      url: api.user.class_list_class,
      method: 'get',
      data: {
        page: 1,
      },
      success: function(res) {
        console.log(res)
         if (res.code == 0) {
          page.setData({
            status: true,
            class_list:res.data.list
          });
        } else {
          page.setData({
            status: false,
          });
        }
      },
    });
  },
  edit:function(){
    wx.redirectTo({
      url: '/pages/user-teacher-edit/user-teacher-edit',
    })
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
          if (res.data.user_info.binding) {
            page.setData({
              binding_num: res.data.user_info.binding,
              binding: true
            });
          } else {
            page.setData({
              gainPhone: true,
              handPhone: false,
            });
          }
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


  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})