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
  onLoad: function (options) {
    app.pageOnLoad(this);
    is_loading_more = false;
    is_no_more = false;
    var id = options.id;
    if (options.scene) {
      id = decodeURIComponent(options.scene);
    }
    var page = this;
    var time = util.formatdate(new Date());
    if(id){

    }
    share.init(page);
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

    var user_info = wx.getStorageSync('user_info');
    if (user_info.is_member == 1) {
      page.setData({
        show_modal: false,
      });
    }
    var video_id = page.data.video_id;

  },
  chooseImg: function () {
    var page = this;
    wx.chooseImage({
      count: 1,
      success: function (res) {
        
        let img_list = res.tempFilePaths;
        console.log(img_list);
        page.setData({
          img_list: img_list
        });
      },
    })
  },
  chakan: function () {
    this.setData({
      flag: 1,
    });
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
    app.pageOnHide(this);

    var page = this;
    var old = wx.createVideoContext("video");
    old.seek(0);
    old.pause();
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
    if (this.data.page == this.data.page_count) {
      return;
    }
    //this.loadMoreComment();
  },
  load: function () {
    if (this.data.page == this.data.page_count) {
      return;
    }
    //this.loadMoreComment();
  },
  inputClassName: function (e) {
    var value = e.detail.value;
    this.setData({
      class_name: value
    });
  },
  createClass: function (e) {
    var img_list = this.data.img_list;
    var upload_img = [];
    this.setData({
      focus: false
    });
    let page = this;
    var class_name = this.data.class_name;
    console.log(e)
    var form_id = e.detail.formId;
    if (!class_name && img_list.length == 0) {
      wx.showModal({
        title: '提示',
        content: '请输入班级名称或图片',
        showCancel: false
      })
      return;
    }
    wx.showLoading({
      title: '正在提交',
      mask: true
    });
    uploadImg(0);
    function uploadImg(i) {
      if (i == img_list.length) {
        return submit();
      }
      if (i >= 5) {
        return submit();
      }
      wx.uploadFile({
        url: api.default.upload_img,
        filePath: img_list[i],
        name: 'image',
        complete: function (res) {
          if (res.statusCode == 200) {
            var data = JSON.parse(res.data);
            if (data.code == 0) {
              upload_img = upload_img.concat(data.data.url);
            }
          }
          i++;
          if (i != img_list.length) {
            return uploadImg(i);
          } else {
            return submit();
          }
        }
      })
    }
    function submit() {
      console.log('submit')
      let class_id = 0
      if (page.data.id){
        class_id = page.data.id
      }
      app.request({
        url: api.user.class_edit,
        method: 'POST',
        data: {
          class_id: class_id,
          class_name: page.data.class_name,
          upload_img: JSON.stringify(upload_img),
          form_id: form_id
        },
        success: function (res) {
          wx.hideLoading();
          console.log(res)
          if (res.code == 0) {
            wx.showToast({
              title: '班级创建成功',
            });
          } else{
            wx.showModal({
              title: '提示',
              content: res.msg,
              showCancel: false
            })
          }
        },
        complete: function (res) {
          wx.hideLoading();
        }
      });
    }
  },
  loadComment: function (id) {
    var page = this;
    is_loading_more = true;
    app.request({
      url: api.user.comment_list,
      data: {
        video_id: id,
        page: 1
      },
      success: function (res) {
        if (res.code == 0) {
          page.setData({
            comment_list: res.data.list,
            comment_count: res.data.row_count,
            page_count: res.data.page_count,
            cc_count: res.data.cc_count
          });
        }
      },
      fail: function (res) {
        wx.showModal({
          title: '警告',
          content: res.msg,
          showCancel: false
        })
      },
      complete: function () {
        wx.hideLoading();
        is_loading_more = false;
      }
    });
  },
  loadMoreComment: function () {
    var page = this;
    var comment_list = page.data.comment_list;
    var p = page.data.page;
    if (is_loading_more) {
      return;
    }
    is_loading_more = true;
    wx.showLoading({
      title: '加载中',
    });
    app.request({
      url: api.user.comment_list,
      data: {
        video_id: page.data.video.id,
        page: (p + 1)
      },
      success: function (res) {
        wx.hideLoading();
        if (res.code == 0) {
          if (res.data.list.length > 0) {
            comment_list = comment_list.concat(res.data.list);
            page.setData({
              page: (p + 1),
              comment_list: comment_list,
              page_count: res.data.page_count,
              row_count: res.data.row_count,
              cc_count: res.data.cc_count
            });
          } else {
            is_no_more = true;
          }
        } else {
          wx.showModal({
            title: '提示',
            content: res.msg,
          });
        }
      },
      complete: function () {
        is_loading_more = false;
      }
    });
  },
  thump: function (e) {
    var page = this;
    var index = e.currentTarget.dataset.index;
    var comment_list = page.data.comment_list;
    var id = comment_list[index].id;
    var thump_count = comment_list[index].thump_count;
    page.setData({
      thump_loading: index
    });

    app.request({
      url: api.user.thump,
      data: {
        c_id: id
      },
      success: function (res) {
        if (res.code == 0) {
          var thump = !comment_list[index].thump;
          var title = '取消点赞';
          if (thump) {
            title = '点赞成功';
            thump_count++;
          } else {
            thump_count--
          }
          comment_list[index].thump = thump;
          comment_list[index].thump_count = thump_count;
          page.setData({
            comment_list: comment_list,
            toast_text: title,
            isShowToast: true
          });
          setTimeout(function () {
            page.setData({
              isShowToast: false
            });
          }, 1500);
        } else {
          wx.showModal({
            title: '提示',
            content: res.msg,
            showCancel: false
          })
        }
      },
      complete: function () {
        page.setData({
          thump_loading: -1
        });
      }
    });
  },
  previewImg: function (e) {
    var page = this;
    var comment_list = page.data.comment_list;
    var index = e.currentTarget.dataset.index;
    var i = e.currentTarget.dataset.i;
    var img_list = comment_list[index].img;
    var current = img_list[i];
    wx.previewImage({
      current: current,
      urls: img_list,
    })
  },
  previewImg_c: function (e) {
    var page = this;
    var comment_list = page.data.comment_list;
    var index = e.currentTarget.dataset.index;
    var i = e.currentTarget.dataset.i;
    var pic = e.currentTarget.dataset.pic;
    var img_list = comment_list[index].children[i].img;
    var current = img_list[pic];
    wx.previewImage({
      current: current,
      urls: img_list,
    })
  },


  pause: function () {
    var page = this;
    var video_play = wx.createVideoContext('video');
    video_play.pause();
    page.setData({
      play: false
    });
  },
  timeupdate: function (e) {
    var page = this;
    var video_pay = page.data.video_pay;
    if (page.data.video.is_pay == 0) {
      return;
    }
    var video = wx.createVideoContext('video');
    if (e.detail.currentTime >= video_pay.time) {
      video.seek(0);
      video.pause();
      page.setData({
        play: false
      });
    }
  },

  showModal: function () {
    var page = this;
    page.setData({
      show_modal: true
    });
  },
  closeModal: function () {
    this.setData({
      show_modal: false
    });
  },
  buyVip: function () {
    wx.navigateTo({
      url: '/pages/member/member',
    })
  },
  backIndex: function (e) {
    wx.redirectTo({
      url: '/pages/cover/cover',
    })
  }
})