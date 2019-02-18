<?php
use yii\helpers\Url;

$this->registerJsFile('@web/js/index-delete.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/admin.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<title>学懂汉字</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/css/swiper.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.esm.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.2/js/swiper.esm.bundle.js"></script>
	<script type="text/javascript">
        $(function(){
                resizeScreen();
        });

        $(window).resize(function(){
            resizeScreen();
        });

        function resizeScreen(){
            /*
            var contentHeight = $(document).height() - 100;
            var contentWidth = $(document).width() - 250;
            $("#menu").height(contentHeight);


            $("#content-right").css({height : contentHeight , width :contentWidth });
            */
            var top_box_H = $('#header-box').height();
            //var top_bar_H = $('.top_bar').height();
            //var foot_box_H = $('#foot_box').height();
            var DH = $(window).height();
            $("#menu").height(($(document).height()));
            //左右高度
            $('#left_box').css({'height' : DH - top_box_H/*(top_box_H+ top_bar_H + foot_box_H) - 10*/});
            $('#content-right').css({'height' : DH - (top_box_H /*+ top_bar_H+ foot_box_H*/) - 10});
            //右边宽度
            var left_box_W = $('#left_box').width();
            var DW = $(window).width();
            $('#content-right').css({'width' : DW - left_box_W - 40});
        }




    </script>

    <link rel="stylesheet" type="text/css" href="css/iframe.css"/>
    <style>
  
    .swiper-container {
      width: 100%;
      height: 150px;
    }
    .swiper-slide {
      text-align: center;
      font-size: 14px;
      background: #fff;

      /* Center slide text vertically */
      display: -webkit-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      -webkit-justify-content: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      -webkit-align-items: center;
      align-items: center;
    }

    </style>
</head>

<body>

<header id="header">
	<h1 class="header-box">学懂汉字 课程内容</h1>
	<div class="header-right header-box">
        <span>您好，<?php echo $userName ?></span>
        <!-- | <a href="<?=Url::to(['user/edit'])?>" target="main" >修改密码</a> -->
         |  <a href="<?=Url::to(['user/logout'])?>">安全退出</a>
	</div>
</header>

<div id="left_box">
    <div class="menu_box" >
        <ul id="menu">
        <?php foreach ($catList as $index => $value): ?>
        <li >
                <a style="text-align:center;height:auto" href="<?=Url::to(['user/index', 'cat_id' => $value['id']])?>" >
                <div style="width:160px;height:80px;background: url(<?=$value['pic_url']?>) no-repeat;background-size: cover;background-position: center">
                        </div>
                <?=$value['name']?></a>
            </li>
        <?php endforeach;?>
        </ul>
    </div>
</div>
<div id="content-right">
    <?php if ($videoList){?>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($videoList as $index => $value):
    if (!strstr($value['title'], '免费')) {?>
	  <div class="swiper-slide">
	                <a style="text-align:center;height:auto" onclick="javascript:playVideo('<?=$value['video_720'] ? $value['video_720'] : $value['video_url']?>',<?=$value['id']?>)" href="#">
	                <div style="width:120px;height:60px;">
	                <img src='<?=$value['pic_url']?>' style="width:100%;height:100%" >
	                  </div>
	                <div style="width:120px;word-wrap:break-word;"><?=$value['title']?></div></a>
	  </div>
	<?php
    }
endforeach;?>
    </div>
    <!-- 如果需要分页器 -->
    <div class="swiper-pagination"></div>

    <!-- 如果需要导航按钮 -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <!-- 如果需要滚动条 -->
    <!-- <div class="swiper-scrollbar"></div> -->
</div>
<div style="width:100%;display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-orient: horizontal;
    -webkit-flex-direction: row;
    flex-direction: row;">
<video id="videoPlayer" width="1000px"  controls="true" controlslist="nodownload">
  <source src="" type="video/mp4" />
</video>
</div>
<?php
    }?>
</div>
</body>
</html>
<script>
window.onload = function() {
    var mySwiper = new Swiper ('.swiper-container', {

      slidesPerView: 4,
    //   spaceBetween: 30,
      slidesPerGroup: 4,


    loop: false, // 循环模式选项

    // 如果需要分页器
    pagination: {
      el: '.swiper-pagination',
    },

    // 如果需要前进后退按钮
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    // 如果需要滚动条
    // scrollbar: {
    //   el: '.swiper-scrollbar',
    // },
  })
}
function playVideo(video_url,video_id){
//   lesson.currentId = video_id
  var video = document.getElementById("videoPlayer");
  document.getElementById('videoPlayer').setAttribute("src", video_url);
  // video.src = video_url
  video.play();
}
</script>