var a = getApp();

Page({
    data: {
        setting: [],
        shareinfo: [],
        app_uid: "",
        parentid: "",
        url: "",
        show_app: !1,
        show_btn: !1
    },
    onLoad: function(a) {
        var t = this;
    },
    load_index: function() {
        var t = this;
        a.util.request({
            url: "entry/wxapp/index",
            success: function(a) {
                t.setData({
                    url: a.data.data
                });
            }
        });
    },
  GoIndex:function(){
    wx.switchTab({
      url: '/pages/index/index',
    })
  },
  GoUser: function () {
    wx.switchTab({
      url: '/pages/user/user',
    })
  },
  GoCat: function () {
    wx.switchTab({
      url: '/pages/cat/cat',
    })
  },
  GoLesson: function () {
    wx.redirectTo({
      url: '/pages/video/video?id=5',
    })
  },
    onShareAppMessage: function(a) {
        var t = this;
        return {
            title: t.data.shareinfo.title,
            path: "/fy_lessonv2/pages/index/index?app_uid=" + t.data.app_uid,
            imageUrl: t.data.shareinfo.images,
            success: function(a) {
                wx.showToast({
                    title: "分享成功(" + t.data.app_uid + ")",
                    icon: "success",
                    duration: 2e3
                });
            },
            fail: function(a) {}
        };
    },
    updateUserInfo: function(t) {
        var e = this;
        a.util.getUserInfo(function(a) {
            a.memberInfo && (e.load_shareinfo(), e.save_recommend(a.memberInfo.uid)), e.setData({
                show_app: !0
            });
        }, t.detail);
    }
});