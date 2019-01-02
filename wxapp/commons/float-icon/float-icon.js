var api = require('../../api.js');
var app = getApp();
var floatIcon = {
  init: function (page) {
    var _this = this;
    _this.page = page;
    
    _this.page.goCover = function (e) {
      console.log('goCover')
      wx.reLaunch({
        url: '/pages/cover/cover',
      })
    }
  }
}
module.exports = floatIcon;