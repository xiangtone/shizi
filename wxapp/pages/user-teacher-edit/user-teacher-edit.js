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
  onLoad: function (options) {
    app.pageOnLoad(this);
    var page = this;
    app.request({
      url: api.user.teacher,
      method: 'get',
      data: {
        page: 1,
      },
      success: function (res) {
        console.log(res)
        if (res.code == -3) {
          wx.showModal({
            title: '提示',
            content: res.msg,
            confirmText: '前往',
            showCancel: false,
            success: function (res) {
              if (res.confirm) {
                wx.redirectTo({
                  url: '/pages/user-binding/user-binding',
                })
              }
            }
          })
        } else if (res.code == 0) {
          page.setData({
            status: true,
          });
        } else {
          page.setData({
            status: false,
          });
        }
      },
    });
  },
  onSubmit: function () {
    console.log('try submit')
    app.request({
      url: api.user.teacher_edit,
      method: 'POST',
      data: {
        teacherName: this.data.teacherName,
        cooName: this.data.cooName,
        bank: this.data.bank,
        bankAccount: this.data.bankAccount,
      },
      success: function (res) {
        if (res.code == 0) {
          page.setData({
            binding: true,
            binding_num: phone
          })
        } else {
          wx.showToast({
            title: '失败'+res.msg,
            image: "/images/icon-warning.png",
          });
        }
      },
    })
  },
  TeacherName: function (e) {
    this.setData({
      teacherName: e.detail.value
    })
  },
  CooName: function (e) {
    this.setData({
      cooName: e.detail.value
    })
  },
  Bank: function (e) {
    this.setData({
      bank: e.detail.value
    })
  },
  BankAccount: function (e) {
    this.setData({
      bankAccount: e.detail.value
    })
  },
  getPhoneNumber: function (e) {
    var page = this;
    if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {
      wx.showModal({
        title: '提示',
        showCancel: false,
        content: '未授权',
        success: function (res) { }
      })
    } else {
      wx.login({
        success: function (res) {
          if (res.code) {
            var code = res.code;
            app.request({
              url: api.user.user_binding,
              method: 'POST',
              data: {
                iv: e.detail.iv,
                encryptedData: e.detail.encryptedData,
                code: code,
              },
              success: function (res) {
                if (res.code == 0) {
                  page.setData({
                    PhoneNumber: res.data.dataObj,
                  });
                } else {
                  wx.showToast({
                    title: '授权失败'
                  });
                }
              },
            });

          } else {
            wx.showToast({
              title: '授权失败',
              image: "/images/icon-warning.png",
            });
          }
        },
      });
    }
  },
  gainPhone: function () {
    this.setData({
      gainPhone: true,
      handPhone: false,
    });
  },
  handPhone: function () {
    this.setData({
      gainPhone: false,
      handPhone: true,
    });
  },

  nextStep: function () {
    var page = this;
    var phone = this.data.handphone;
    if (!phone || phone.length != 11) {
      wx.showToast({
        title: '手机号码错误',
        image: "/images/icon-warning.png",
      });
      return
    }

    app.request({
      url: api.user.user_hand_binding,
      method: 'POST',
      data: {
        content: phone,
      },
      success: function (res) {
        if (res.code == 0) {
          page.timer()
          page.setData({
            content: res.msg,
            timer: true,
          })
        } else if (res.code == 2) {
          wx.showModal({
            title: '提示',
            content: res.msg,
            showCancel: false
          });
        } else {
          wx.showModal({
            title: '提示',
            content: res.msg,
            showCancel: false
          });
        }
      },
    });
  },
  timer: function () {
    let promise = new Promise((resolve, reject) => {
      let setTimer = setInterval(
        () => {
          this.setData({
            second: this.data.second - 1
          })
          if (this.data.second <= 0) {
            this.setData({
              timer: false,
            })
            resolve(setTimer)
          }
        }, 1000)
    })
    promise.then((setTimer) => {
      clearInterval(setTimer)
    })
  },

  HandPhoneInput: function (e) {
    this.setData({
      handphone: e.detail.value
    })
  },
  CodeInput: function (e) {
    this.setData({
      code: e.detail.value
    })
  },
  PhoneInput: function (e) {
    this.setData({
      phoneNum: e.detail.value
    })
  },
  renewal: function () {
    var page = this;
    page.setData({
      binding: false,
      gainPhone: true,
      handPhone: false,
    });

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
    app.request({
      url: api.user.index,
      method: 'GET',
      success: function (res) {
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