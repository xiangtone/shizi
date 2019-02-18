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
    is_loading_more = false;
    is_no_more = false;
    var id = options.id;
    if (options.scene) {
      id = decodeURIComponent(options.scene);
    }
    var page = this;
    var time = util.formatdate(new Date());
    if (id) {

    }
    share.init(page);
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
    //this.loadMoreComment();
  },
  load: function() {
    if (this.data.page == this.data.page_count) {
      return;
    }
    //this.loadMoreComment();
  },
  inputCardId: function(e) {
    this.setData({
      card_id: e.detail.value
    });
  },
  inputPassword: function(e) {
    this.setData({
      password: e.detail.value
    });
  },
  useCard: function(e) {
    let page = this;
    var class_name = this.data.class_name;
    var form_id = e.detail.formId;
    if (!(this.data.card_id && this.data.card_id.length > 0 && this.data.password && this.data.password.length > 0)) {
      wx.showModal({
        title: '提示',
        content: '请输入卡号密码',
        showCancel: false
      })
      return;
    }
    wx.showLoading({
      title: '正在提交',
      mask: true
    });
    app.request({
      url: api.user.user_card,
      method: 'GET',
      data: {
        card_id: this.data.card_id,
        password: this.data.password,
      },
      success: function(res) {
        wx.hideLoading();
        console.log(res)
        if (res.code == 0) {
          wx.showModal({
            title: '提示',
            content: res.msg,
            //cancelText: '继续用卡',
            confirmText: '进入课程',
            showCancel: false,
            success: function() {
              wx.redirectTo({
                url: '/pages/cat-second/cat-second?cat_id=' + res.data,
              })
            },
            cancel: function(res) {
              this.setData({
                password: "",
                card_id: "",
              });
            }
          })
          // wx.redirectTo({
          //   url: '/pages/class-user-list/class-user-list?class_id=' + res.class_id,
          // })
        } else {
          wx.showModal({
            title: '提示',
            content: res.msg,
            showCancel: false
          })
        }
      },
      complete: function(res) {
        wx.hideLoading();
      }
    });
  },

  backIndex: function(e) {
    wx.redirectTo({
      url: '/pages/cover/cover',
    })
  }
})