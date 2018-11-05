var api = require('../../api.js');
var app = getApp();
var share = {
  init: function (page) {
    var _this = this;
    _this.page = page;
    _this.page.share = function (e) {
      var index = e.currentTarget.dataset.index;
      var hide = _this.page.data.hide;
      var video_list = _this.page.data.video_list;
      var share = video_list[index];
      var play = wx.createVideoContext("video_" + hide);
      play.seek(0);
      play.pause();
      _this.page.setData({
        share: share,
        share_show: true,
        hide: -1
      });
    }
    _this.page.share_1 = function (e) {
      var video = _this.page.data.video;
      _this.page.setData({
        share: video,
        share_show: true
      });
      var play = wx.createVideoContext("video");
      play.seek(0);
      play.pause();
    }
    _this.page.share_cancel = function (e) {
      _this.page.setData({
        share_show: false
      });
    }
    _this.page.share_b = function (e) {
      wx.showLoading({
        title: '海报生成中',
      });
      app.request({
        url: api.default.share,
        method: 'POST',
        data: {
          video_id: _this.page.data.share.id
        },
        success: function (res) {
          wx.hideLoading();
          if (res.code == 0) {
            wx.previewImage({
              current: res.data.url,
              urls: [res.data.url],
            })
          } else {
            _this.page.setData({
              isshare: true,
              share_text: res.msg
            });
            setTimeout(function () {
              _this.page.setData({
                isshare: false
              })
            }, 1500);
          }
        },
        complete: function () {
        }
      });
    }

  }
}
module.exports = share;