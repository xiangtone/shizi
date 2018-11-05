// pages/collect/collect.js
var app = getApp();
var api = require('../../api.js');
var is_loading_more = false;
var is_no_more = false;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    page: 1,
  
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.pageOnLoad(this);
      is_loading_more = false;
      is_no_more = false;
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
      title: '正在加载',
    });
    is_loading_more = true;
    app.request({
      url: api.user.collect_video,
      success: function (res) {
        if (res.code == 0) {
          page.setData({
            video_list: res.data.list
          });
        }
      },
      complete: function () {
          wx.hideLoading();
          is_loading_more = false;
      }
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
    var page = this;
    if (is_no_more)
      return;
    page.loadMoreGoodsList();
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
      url: api.user.collect_video,
      data: {page: p },
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
  goto:function(e){
    var page = this;
    var video_list = page.data.video_list;
    var index = e.currentTarget.dataset.index;
    var video = video_list[index];
    if(video.status==1){
      wx.showModal({
        title: '提示',
        content: '该内容已下架',
        showCancel:false,
        success:function(e){
          
        }
      });
      return ;
    }
    wx.navigateTo({
      url: '/pages/video/video?id='+video.id,
    })
  }
})