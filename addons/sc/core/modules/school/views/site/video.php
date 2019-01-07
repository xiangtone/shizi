<script src="https://cdn.bootcss.com/vue/2.4.4/vue.min.js"></script>
<div style="width:100%">
<?php foreach ($list as $index => $value): ?>
        <div style="float:left;padding:10px;" >
                <a style="text-align:center;height:auto" onclick="javascript:playVideo('<?=$value['video_1080'] ? $value['video_1080'] : $value['video_url']?>',<?=$value['id']?>)" href="#">
                <div style="width:120px;height:60px;">
                <img src='<?=$value['pic_url']?>' style="width:100%;height:100%" >
                  </div>
                <div style="width:120px;word-wrap:break-word;"><?=$value['title']?></div></a>
</div>
<?php endforeach;?>
</div>
<div id='lesson' v-if='currentId' style="width:100%;display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-orient: horizontal;
    -webkit-flex-direction: row;
    flex-direction: row;">
<a href='#'  v-on:click='openChar'>
  <div style="width:150px;height:75px;">
<img src='http://qiniu.xuedonghanzi.com/uploads/image/7a/7aa7351ae77b9360468676f55ef93727.png' style="width:100%;height:100%" >
</div>
</a>
<a href='#'  v-on:click='openWord'>
<div style="width:150px;height:75px;">
<img src='http://qiniu.xuedonghanzi.com/uploads/image/42/421e52444be14af1191a1137227c8477.png' style="width:100%;height:100%" >
</div>
</a>
<a href='#'  v-on:click='openSentence'>
<div style="width:150px;height:75px;">
<img src='http://qiniu.xuedonghanzi.com/uploads/image/4b/4bd089000652fcc0e153dce1b5b664e8.png' style="width:100%;height:100%" >
</div>
</a>
</div>
<div style="width:100%">
<video id="videoPlayer" width="100%"  controls="true" controlslist="nodownload">
  <source src="" type="video/mp4" />
</video>
</div>
<script>
var lesson;
window.onload = function(){
  $('#videoPlayer').bind('contextmenu',function() { return false; });
  lesson = new Vue({
        el: '#lesson',
        data: {
          currentId: 0,
        },
        methods: {
          openChar: function () {
            var iWidth=750; //弹出窗口的宽度;
        var iHeight=800; //弹出窗口的高度;
        var iTop = (window.screen.availHeight-30-iHeight)/2; //获得窗口的垂直位置;
        var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
        window.open("../../../../char.htm?video_id="+this.currentId,"_blank","height="+iHeight+",width="+iWidth+", top="+iTop+",left="+iLeft+",menubar=no,toolbar=no,status=no,location=no");
          },
          openWord: function(){
            var iWidth=500; //弹出窗口的宽度;
        var iHeight=750; //弹出窗口的高度;
        var iTop = (window.screen.availHeight-30-iHeight)/2; //获得窗口的垂直位置;
        var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
        window.open("../../../../game/zuci_ext/build/web-mobile/?video_id="+this.currentId,"_blank","height="+iHeight+",width="+iWidth+", top="+iTop+",left="+iLeft+",menubar=no,toolbar=no,status=no,location=no");
          },
          openSentence: function(){
            var iWidth=960; //弹出窗口的宽度;
        var iHeight=640; //弹出窗口的高度;
        var iTop = (window.screen.availHeight-30-iHeight)/2; //获得窗口的垂直位置;
        var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
        window.open("../../../../game/zuju/build/web-mobile/?video_id="+this.currentId,"_blank","height="+iHeight+",width="+iWidth+", top="+iTop+",left="+iLeft+",menubar=no,toolbar=no,status=no,location=no");
          },
        }
      })
};
function playVideo(video_url,video_id){
  lesson.currentId = video_id
  var video = document.getElementById("videoPlayer");
  document.getElementById('videoPlayer').setAttribute("src", video_url);
  // video.src = video_url
  video.play();
}
</script>