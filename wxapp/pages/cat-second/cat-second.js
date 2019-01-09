// pages/cat-second/cat-second.js
var app = getApp();
var api = require('../../api.js');
var util = require('../../utils/util.js');
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
  onLoad: function(options) {
    app.pageOnLoad(this);
    is_loading_more = false;
    is_no_more = false;
    app.storeChannel(options)
    console.log("onLoad", options)
    var page = this;
    if (options.q) {
      let q = decodeURIComponent(options.q)
      console.log("onLoad q ", q)
      let cat_id = util.getUrlParam(q, "cat_id")
      if (cat_id) {
        page.setData({
          cat_id: cat_id
        });
      }
    } else {
      page.setData({
        cat_id: options.cat_id
      });
    }
  },
  goVideo: function(e) {
    if (e.currentTarget.dataset.videoId) {
      wx.navigateTo({
        url: '/pages/video1/video1?id=' + e.currentTarget.dataset.videoId,
      })
    }
  },
  goChar: function(e) {
    if (e.currentTarget.dataset.videoId) {
      wx.navigateTo({
        url: '/pages/read/read?video_id=' + e.currentTarget.dataset.videoId,
      })
    }
  },
  goSentence: function(e) {
    if (e.currentTarget.dataset.videoId) {
      var page = this;
      if (app.checkLogin() == true) {
        //console.log("已经登陆过了");
        var video = page.data.video;
        var user_info = wx.getStorageSync('user_info');
        var redirect_url = '/pages/zuju-web-view/zuju-web-view' + "?video_id=" + e.currentTarget.dataset.videoId + "&user_id=" + user_info.id;
        //console.log("跳转地址->"+redirect_url);
        wx.navigateTo({
          url: redirect_url,
        })
      } else {
        console.log("去登陆");
      }
    }
  },
  goWord: function(e) {
    if (e.currentTarget.dataset.videoId) {
      var page = this;
      if (app.checkLogin() == true) {
        //console.log("已经登陆过了");
        var video = page.data.video;
        var user_info = wx.getStorageSync('user_info');
        var redirect_url = '/pages/zuci-web-view/zuci-web-view' + "?video_id=" + e.currentTarget.dataset.videoId + "&user_id=" + user_info.id;
        //console.log("跳转地址->"+redirect_url);
        wx.navigateTo({
          url: redirect_url,
        })
      } else {
        console.log("去登陆");
      }
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
    if (!this.data.video_list) {
      this.loadData();
    }
  },

  loadData: function() {
    var page = this;
    wx.showLoading({
      title: '正在加载',
    });
    is_loading_more = true;
    app.request({
      url: api.default.cat_video,
      data: {
        cat_id: page.data.cat_id
      },
      success: function(res) {
        console.log('res', res)
        if (res.code == 0) {
          if (!res.data.cat_name) {
            wx.navigateTo({
              url: '/pages/index/index',
            });
            return;
          }
          for (let i in res.data.list) {
            let regexp = /\d+/g
            let splitNum = res.data.list[i].title.match(regexp)
            if (splitNum) {
              let splitArr = res.data.list[i].title.split(splitNum)
              res.data.list[i].title1 = splitArr[0].trim()
              res.data.list[i].title2 = splitArr[1].trim()
              res.data.list[i].titleNum = splitNum
            }
            if (res.data.cat.is_pay == '1' && res.data.list[i].is_pay=='0'){
              res.data.list[i].freeBlink = 1 
            }else{
              res.data.list[i].freeBlink = 0
            }
          }
          page.setData({
            video_list: res.data.list,
            cat_name: res.data.cat_name,
            cat: res.data.cat,
          });

          // wx.setNavigationBarTitle({
          //   title: res.data.cat_name,
          // });
        }
      },

      complete: function() {
        wx.hideLoading();
        wx.stopPullDownRefresh();
        is_loading_more = false;
      }
    });
  },
  testWord: function(ori) {
    console.log('testWord')
    return ori + ':'
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

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {
    this.loadData();
    this.setData({
      page: 1
    });
    is_no_more = false;
    is_loading_more = false;
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function(res) {
    var result = {
      title: "",
      path: "/pages/cover/cover",
      success: function(res) {},
      fail: function(res) {
        // 转发失败
      }
    };
    if (this.data.cat_id) {
      result.path = "/pages/cat-second/cat-second?cat_id=" + this.data.cat_id
    }
    var user_info = wx.getStorageSync('user_info');
    if (user_info && user_info.id) {
      if (result.path.indexOf("?") != -1) {
        result.path = result.path + "&fd=" + user_info.id
      } else {
        result.path = result.path + "?fd=" + user_info.id
      }
    }
    return result;
  },


  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {
    var page = this;
    if (is_no_more)
      return;
    page.loadMoreGoodsList();
  },
  loadMoreGoodsList: function() {
    var page = this;
    if (is_loading_more)
      return;
    page.setData({
      show_loading_bar: true,
    });
    is_loading_more = true;
    var p = page.data.page;
    app.request({
      url: api.default.cat_video,
      data: {
        cat_id: page.data.cat_id,
        page: p
      },
      success: function(res) {
        if (res.data.list.length == 0)
          is_no_more = true;
        for (let i in res.data.list) {
          let regexp = /\d+/g
          let splitNum = res.data.list[i].title.match(regexp)
          if (splitNum) {
            let splitArr = res.data.list[i].title.split(splitNum)
            res.data.list[i].title1 = splitArr[0].trim()
            res.data.list[i].title2 = splitArr[1].trim()
            res.data.list[i].titleNum = splitNum
          }
          if (res.data.cat.is_pay == '1' && res.data.list[i].is_pay == '0') {
            res.data.list[i].freeBlink = 1
          } else {
            res.data.list[i].freeBlink = 0
          }
        }
        var video_list = page.data.video_list.concat(res.data.list);
        page.setData({
          video_list: video_list,
          page: (p + 1),
        });
      },
      complete: function() {
        is_loading_more = false;
        page.setData({
          show_loading_bar: false,
        });
      }
    });
  },
})