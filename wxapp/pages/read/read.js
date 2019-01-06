// pages/cat/cat.js
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
    recordStatus: false,
    tempFilePath:'',
    currentIndex:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    app.pageOnLoad(this);
    is_loading_more = false;
    is_no_more = false;
    if (options.video_id){
      this.setData({
        video_id: options.video_id
       });
    }else{
      this.setData({
        video_id: 13
      });
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
    this.loadData();
  },
  loadData: function() {
    if (!this.data.video_id){
      return
    }
    var page = this;
    wx.showLoading({
      title: '正在加载',
    })
    is_loading_more = true;
    app.request({
      url: api.default.ex_char,
      data: {
        video_id: this.data.video_id
      },
      success: function(res) {
        console.log(res)
        if (res.code == 0) {
          page.setData({
            list: res.data.list,
          });
        }else{
          wx.showModal({
            title: '提示',
            content: res.msg,
            showCancel: false,
            success: function (e) {
              if (e.confirm) {
                wx.navigateBack({
                  
                })
              }
            },
            fail: function () {
              console.log("openfail");
            }
          })
        }
      },
      complete: function() {
        wx.stopPullDownRefresh();
        wx.hideLoading();
        is_loading_more = false;
      }
    });
  },
  next:function(){
    if (this.data.currentIndex < this.data.list.length-1){
      this.setData({
        currentIndex : this.data.currentIndex+1
      })
    }
  },
  done:function(){
    wx.navigateBack({
      
    })
  },
  playTeacher: function() {
    const innerAudioContext = wx.createInnerAudioContext();
    innerAudioContext.autoplay = false;
    innerAudioContext.obeyMuteSwitch = false;
    innerAudioContext.src = this.data.list[this.data.currentIndex].voice_url
    innerAudioContext.play()
  },
  beginRecord: function() {
    var that = this;
    const recorderManager = wx.getRecorderManager()
    recorderManager.onStart(() => {
      console.log('recorder start')
      that.setData({
        recordStatus: true
      })
    })
    recorderManager.onPause(() => {
      console.log('recorder pause')
    })
    recorderManager.onStop((res) => {
      console.log('recorder stop', res)
      const {
        tempFilePath
      } = res
      that.setData({
        recordStatus: false,
        tempFilePath: res.tempFilePath
      })
    })
    recorderManager.onFrameRecorded((res) => {
      const {
        frameBuffer
      } = res
      console.log('frameBuffer.byteLength', frameBuffer.byteLength)
    })
    const options = {
      duration: 6000,
      sampleRate: 44100,
      numberOfChannels: 1,
      encodeBitRate: 192000,
      format: 'mp3',
    }
    //调取小程序新版授权页面
    wx.authorize({
      scope: 'scope.record',
      success() {
        console.log("录音授权成功");
        //第一次成功授权后 状态切换为2
        that.setData({
          status: 2,
        })
        // 用户已经同意小程序使用录音功能，后续调用 wx.startRecord 接口不会弹窗询问
        // wx.startRecord();
        recorderManager.start(options); //使用新版录音接口，可以获取录音文件
      },
      fail() {
        console.log("第一次录音授权失败");
        wx.showModal({
          title: '提示',
          content: '您未授权录音，功能将无法使用',
          showCancel: true,
          confirmText: "授权",
          confirmColor: "#52a2d8",
          success: function(res) {
            if (res.confirm) {
              //确认则打开设置页面（重点）
              wx.openSetting({
                success: (res) => {
                  console.log(res.authSetting);
                  if (!res.authSetting['scope.record']) {
                    //未设置录音授权
                    console.log("未设置录音授权");
                    wx.showModal({
                      title: '提示',
                      content: '您未授权录音，功能将无法使用',
                      showCancel: false,
                      success: function(res) {},
                    })
                  } else {
                    //第二次才成功授权
                    console.log("设置录音授权成功");
                    that.setData({
                      status: 2,
                    })
                    recorderManager.start(options);
                  }
                },
                fail: function() {
                  console.log("授权设置录音失败");
                }
              })
            } else if (res.cancel) {
              console.log("cancel");
            }
          },
          fail: function() {
            console.log("openfail");
          }
        })
      }
    })
  },
  playRecord:function(){
    const recorderManager = wx.getRecorderManager()
    recorderManager.stop();
    const innerAudioContext = wx.createInnerAudioContext();
    innerAudioContext.autoplay = false;
    innerAudioContext.src = this.data.tempFilePath
    innerAudioContext.play()
  },
  record: function() {
    const innerAudioContext = wx.createInnerAudioContext();
    innerAudioContext.stop()
    if (this.data.recordStatus) {
      // this.setData({
      //   recordStatus:false
      // })
      const recorderManager = wx.getRecorderManager()
      recorderManager.stop();
    } else {
      // this.setData({
      //   recordStatus: true
      // })
      this.beginRecord();
    }
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
    this.loadData();
    is_no_more = false;
    is_loading_more = false;
    this.setData({
      page: 1
    });
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  // onReachBottom: function() {
  //   if (is_no_more) {
  //     return;
  //   }
  //   this.loadMore();
  // },
  loadMore: function() {
    var page = this;
    var p = page.data.page;
    if (is_loading_more) {
      return;
    }
    is_loading_more = true;
    wx.showLoading({
      title: '正在加载',
    });
    app.request({
      url: api.default.cat,
      data: {
        page: p + 1
      },
      success: function(res) {
        if (res.data.cat_list.length == 0)
          is_no_more = true;
        if (res.code == 0) {
          var cat_list = page.data.cat_list.concat(res.data.cat_list);
          page.setData({
            cat_list: cat_list,
            page: p + 1,
            page_count: res.data.page_count
          });
        }
      },
      complete: function() {
        is_loading_more = false;
        wx.hideLoading();
      }
    });

  }
})