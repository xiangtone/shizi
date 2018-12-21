<?php
    use yii\widgets\Breadcrumbs;
?>
<?=Breadcrumbs::widget([
    'homeLink' => ['label' => '个人中心'],
    'links' => [
        ['label' => '修改密码' /*, 'url' => ['index']*/],

    ]
])?>
<?=$this->render('_form' , ['model' => $model])?>