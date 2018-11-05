// pages/user-coupon/user-coupon.js
var app = getApp();
var api = require('../../api.js');
var WxParse = require('../../wxParse/wxParse.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
      app.pageOnLoad(this);
      var page = this;
      var scene = decodeURIComponent(options.scene);
      console.log("option=>" + options.scene);
      var arr = scene.split(":");
      page.setData({
          coupon_id:arr[1]
      });
      page.loadData()
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

  loadData: function (e) {
    var page = this;
    var coupon_id = page.data.coupon_id;
    app.request({
        url: api.user.cancel_coupon,
        method: 'GET',
        data: {
            coupon_id: coupon_id
        },
        success: function (res) {
            if (res.code == 0) {
                wx.showModal({
                    title: '确认核销',
                    success: function (e) {
                        if (e.confirm) {
                            app.request({
                                url: api.user.clerk,
                                method: 'GET',
                                data: {
                                    coupon_id: coupon_id
                                },
                                success: function (res) {
                                    if (res.code == 0){
                                        wx.showModal({
                                            title: '核销成功',
                                            content: '返回首页',
                                            showCancel: false,
                                            success: function (e) {
                                                if (e.confirm) {
                                                    wx.switchTab({
                                                        url: '/pages/index/index',
                                                        complete: function (e) {
                                                            console.log(e)
                                                        }
                                                    })
                                                }
                                            }
                                        });
                                    }else{
                                        wx.showToast({
                                            title: "网络错误",
                                            image: "/images/icon-warning.png",
                                        });
                                    }
                                },
                            });                            
                        }
                    }
                });
            } else if(res.code == 1){
                wx.showModal({
                    title: '不是核销员',
                    content: '返回首页',
                    showCancel:false,
                    success:function(e){
                        if (e.confirm) {
                            wx.switchTab({
                                url: '/pages/index/index',
                                complete: function (e) {
                                    console.log(e)
                                }
                            })
                        }
                    }
                });
            }else if(res.code == 2){
                wx.showModal({
                    title: '已核销',
                    content: '返回首页',
                    showCancel: false,
                    success: function (e) {
                        if (e.confirm) {
                            wx.switchTab({
                                url: '/pages/index/index',
                                complete: function (e) {
                                    console.log(e)
                                }
                            })
                        }
                    }
                });
            }
        },
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