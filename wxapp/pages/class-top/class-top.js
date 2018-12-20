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
  onLoad: function(options) {
    app.pageOnLoad(this);
    if (app.checkLoginWithoutRedirect()) {
      this.setData({
        isLogin: true
      })
    }
    is_loading_more = false;
    is_no_more = false;
    var id = options.id;
    if (options.scene) {
      id = decodeURIComponent(options.scene);
    }
    var page = this;
    var time = util.formatdate(new Date());
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
            show_top: 'lesson',
            list_top: res.data.list_lesson_top,
            list_my_class: res.data.list_my_class,
            list_lesson_top: res.data.list_lesson_top,
            list_ex_top: res.data.list_ex_top,
          });
        } else {
          page.setData({
            status: false,
          });
        }
      },
    });
    // page.setData({
    //     video_id: id,
    //     time: time,
    //     store: wx.getStorageSync('store')
    // });
    //comment.init(page);
    // share.init(page);
  },
  goClass:function(option){
    if (option.currentTarget.dataset.classId){
      wx.navigateTo({
        url: '/pages/class-user-list/class-user-list?class_id=' + option.currentTarget.dataset.classId,
      })
    }
  }
  ,
  choose_top: function(option) {
    console.log(option)

    if (option.currentTarget.dataset.typeName == "ex") {
      this.setData({
        status: true,
        show_top: 'ex',
        list_top: this.data.list_ex_top,
      });
    } else if (option.currentTarget.dataset.typeName == "lesson") {
      this.setData({
        status: true,
        show_top: 'lesson',
        list_top: this.data.list_lesson_top,
      });
    }
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {
    app.pageOnReady(this);

  },
  createClass: function() {
    let user_info = wx.getStorageSync('user_info')
    if (user_info && user_info.id) {
      wx.navigateTo({
        url: '/pages/class-edit/class-edit',
      })
    } else {
      wx.showModal({
        title: '请先登录',
        confirmText: '去登录',
        success: function(e) {
          if (e.confirm) {
            wx.navigateTo({
              url: '/pages/user/user',
            })
          }
        }
      })
    }
  },
  listClass: function() {
    wx.navigateTo({
      url: '/pages/class-list/class-list',
    })
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    app.pageOnShow(this);
    var page = this;

    var user_info = wx.getStorageSync('user_info');
    if (user_info.is_member == 1) {
      page.setData({
        show_modal: false,
      });
    }
    var video_id = page.data.video_id;

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {
    app.pageOnHide(this);

    var page = this;
    // var old = wx.createVideoContext("video");
    // old.seek(0);
    // old.pause();
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
    //this.loadMoreComment();
  },
  load: function() {
    if (this.data.page == this.data.page_count) {
      return;
    }
    //this.loadMoreComment();
  },
})