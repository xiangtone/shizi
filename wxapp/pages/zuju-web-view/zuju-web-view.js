var siteinfo= require('../../siteinfo.js')

Page({
 
  data: {
    url: siteinfo.game_zuju_url,
    video_id:'',
  },
  onLoad: function (options) {
    //目前固定写死
    var url_ = this.data.url+"?video_id="+options.video_id/*9*/+"&user_id="+options.user_id
    //var url_ = "http://qiniu.agsew.com/uploads/video/20181213141801/1544681881f67356259ffa3871.mp3"
    console.log("游戏url_="+url_);
    this.setData({ url: url_ });
  }
});


