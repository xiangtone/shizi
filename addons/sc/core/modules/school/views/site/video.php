<div style="width:100%">
<?php foreach ($list as $index => $value): ?>
        <div style="float:left;padding:10px;" >
                <a style="text-align:center;height:auto" onclick="javascript:playVideo('<?=$value['video_url']?>')" href="#">
                <div style="width:120px;height:60px;background: url(<?=$value['pic_url']?>) no-repeat;background-size: cover;background-position: center">
                        </div>         
                <div><?=$value['title']?></div></a>
</div>
<?php endforeach;?>
</div>
<div style="width:100%">
<video id="videoPlayer" width="100%"  controls="controls">
  <source src="" type="video/mp4" />
</video></div>
<script>
function playVideo(video_url){
  var video = document.getElementById("videoPlayer");
  document.getElementById('videoPlayer').setAttribute("src", video_url);
  // video.src = video_url
  video.play();
}
</script>