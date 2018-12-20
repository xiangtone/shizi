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
</head>

<body>

<header id="header">
	<h1 class="header-box">学懂汉字 课程内容</h1>
	<div class="header-right header-box">
        <span>您好，<?php echo $userName ?></span>
        <!-- <a href="<?=Url::to(['user/edit'])?>" target="main"" class="pos">修改密码</a> -->
         |  <a href="<?=Url::to(['site/logout'])?>">安全退出</a>
	</div>
</header>

<div id="left_box">
    <div class="menu_box" >
        <ul id="menu">
        <?php foreach ($list as $index => $value): ?>
        <li >
                <a style="text-align:center;height:auto" href="<?=Url::to(['site/video', 'cat_id' => $value['id']])?>" target="main">
                <div style="width:160px;height:80px;background: url(<?=$value['pic_url']?>) no-repeat;background-size: cover;background-position: center">
                        </div>
                <?=$value['name']?></a>
            </li>
        <?php endforeach;?>
        </ul>
    </div>
</div>
<div id="content-right">
    <iframe name="main" style="width:100%;height:100%;" frameborder="0" src="<?=Url::to(['site/main'])?>"></iframe>
</div>
</body>
</html>