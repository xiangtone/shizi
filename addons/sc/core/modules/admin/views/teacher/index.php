<?php

defined('YII_RUN') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '老师列表';
$this->params['active_nav_group'] = 9;
?>
<div class="main-nav" flex="cross:center dir:left box:first">
    <div>
        <nav class="breadcrumb rounded-0 mb-0" flex="cross:center">
            <a class="breadcrumb-item" href="<?=$urlManager->createUrl(['admin/system/index'])?>">系统</a>
            <span class="breadcrumb-item active"><?=$this->title?></span>
        </nav>
    </div>
    <div>
        <?=$this->render('/layouts/nav-right')?>
    </div>
</div>
<div class="main-body p-3" id="app">
    <div class="float-right mb-4">
        <form method="get">

            <?php $_s = ['keyword']?>
            <?php foreach ($_GET as $_gi => $_gv): if (in_array($_gi, $_s)) {
        continue;
    }
    ?>
	                <input type="hidden" name="<?=$_gi?>" value="<?=$_gv?>">
	            <?php endforeach;?>

            <div class="input-group">
                <input class="form-control" placeholder="微信昵称" name="keyword" autocomplete="off"
                       value="<?=isset($_GET['keyword']) ? trim($_GET['keyword']) : null?>">
                <span class="input-group-btn"><button class="btn btn-primary">搜索</button></span>
            </div>
        </form>
    </div>

    <table class="table table-bordered bg-white">
        <thead>
        <tr>
            <td><input type="checkbox" class="check-all">ID</td>
            <td>头像</td>
            <td>昵称</td>
            <td>加入时间</td>
            <td>名字</td>
            <td>单位</td>
            <td>银行</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $index => $value): ?>
            <tr>

                <td><input type="checkbox" class="check" value="<?=$value['id']?>"><?=$value['id']?></td>
                <td><img src="<?=$value['avatar_url']?>" style="width: 30px;height: 30px;"></td>
                <td><?=$value['nickname']?><br><?=$value['wechat_open_id']?></td>
                <td><?=date('Y-m-d H:i:s', $value['addtime'])?></td>
                <td><?=$value['teacher_name']?></td>
                <td><?=$value['school_name']?></td>
                <td><?=$value['bank_name']?><br><?=$value['bank_account']?></td>

                <td>
                    <?php if ($value['status'] == 0): ?>
                        <a class="del" href="javascript:" data-content="是否通过审核？"
                           data-url="<?=$urlManager->createUrl(['admin/teacher/status', 'id' => $value['id'], 'status' => 1])?>">审核</a>
                    <?php else: ?>
                        <span class="badge badge-success">已审核</span>
                        |
                        <a class="del" href="javascript:" data-content="是否关闭审核？"
                           data-url="<?=$urlManager->createUrl(['admin/teacher/status', 'id' => $value['id'], 'status' => 0])?>">取消</a>
                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

    <div class="modal fade" aria-labelledby="myModalLabel" aria-hidden="true" id="myModal" style="margin-top:200px;display: ;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="height:40px;">
                    <h5 class="modal-title" id="myModalLabel">
                        会员到期时间
                    </h5>
                </div>
                <div class="modal-body">
                    会员到期时间：<input type="date" name="end_time" id="end_time"/>
                    <input type="hidden" value="" name="user_id" id="user_id">
                </div>
                <div class="modal-footer" style="height:40px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="close">关闭</button>
                    <button type="button" class="btn btn-primary" id="member" onclick="member()">添加</button>
                </div>
            </div>
        </div>
    </div>


    <div class="text-center">
        <?=\yii\widgets\LinkPager::widget(['pagination' => $pagination])?>
        <div class="text-muted"><?=$row_count?>条数据</div>
    </div>
</div>
<script>

    $(document).on('click', '.check-all', function () {
        var checked = $(this).prop('checked');
        $('.check').prop('checked', checked);
        if (checked) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.check', function () {
        var checked = $(this).prop('checked');
        var all = $('.check');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        var is_use = false;//只要有一个选中，批量按妞就可以使用
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_use = true;
            } else {
                is_all = false;
            }
        });
        if (is_all) {
            $('.check-all').prop('checked', true);
        } else {
            $('.check-all').prop('checked', false);
        }
        if (is_use) {
            $('.batch').addClass('is_use');
        } else {
            $('.batch').removeClass('is_use');
        }
    });
    $(document).on('click', '.batch', function () {
        var all = $('.check');
        var is_all = true;//只要有一个没选中，全选按钮就不选中
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                is_all = false;
            }
        });
        if (is_all) {
            $.myAlert({
                content: "请先勾选需要删除项"
            });
        }
    });
    $(document).on('click', '.is_use', function () {
        var a = $(this);
        var check = [];
        var all = $('.check');
        all.each(function (i) {
            if ($(all[i]).prop('checked')) {
                check.push($(all[i]).val());
            }
        });
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        check: check,
                        type: a.data('type')
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                        }
                    },
                    complete: function () {
                        $.myLoadingHide();
                    }
                });
            }
        })
    });
    function add_member($id){
        $("#user_id").val($id);
    }
    var AddMemberUrl = "<?=$urlManager->createUrl(['admin/user/add-member'])?>";
    function member(){
        var user_id = $("#user_id").val();
        var due_time = $("#end_time").val();
        if(!end_time){
            $.myAlert({
                content: "请输入时间"
            });
            return
        }
        $.ajax({
            url: AddMemberUrl,
            type: 'get',
            dataType: 'json',
            data: {
                user_id:user_id,
                due_time:due_time
            },
            success: function (res) {
                if (res.code == 0) {
                    $('#myModal').css('display','none');
                    $.myAlert({
                        content: "添加成功",confirm:function(e){
                            window.location.reload();
                        }
                    });
                }else{
                    $.myAlert({
                        content: "添加失败"
                    });
                }
            }
        });
    }
</script>