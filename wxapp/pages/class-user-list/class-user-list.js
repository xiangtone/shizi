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
      url: api.user.class_info,
      method: 'get',
      data: {
        class_id:options.class_id,
        page: 1,
      },
      success: function(res) {
        console.log(res)
        if (res.code == 0) {
          page.setData({
            status: true,
            user_list:res.data.list,
            class:res.data.class,
            data:res.data
            // teacher_info:res.data.teacher_info
          });
          wx.setNavigationBarTitle({
            title: res.data.class.class_name,
          });
          page.joinStatus()
          page.checkInClass()
        } else {
          page.setData({
            status: false,
          });
        }
      },
    });
  },
  joinClass:function(){
    var page = this;
    if (app.checkLogin()){
      console.log('try join')
      app.request({
        url: api.user.class_join,
        method: 'get',
        data: {
          class_id: this.data.class.id,
        },
        success: function (res) {
          console.log(res)
          if (res.code == 0) {
            wx.reLaunch({
              url: '/pages/class-user-list/class-user-list?class_id='+page.data.class.id,
            })
          } else {
            page.setData({
              status: false,
            });
          }
        },
      });
    }
  },
  checkInClass:function(){
    let user_info = wx.getStorageSync('user_info')
    if (app.checkLoginWithoutRedirect()){
      for (let i in this.data.user_list){
        if (this.data.user_list[i].id == user_info.id){ 
          this.setData({
            isInClass: true,
          }) 
          return;
        }
      }
      this.setData({
        isInClass: false,
      }) 
    }else{
      this.setData({
        isInClass:false,
      })
    }
  },
  joinStatus:function(){
    let user_info =  wx.getStorageSync('user_info')
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

  }
})