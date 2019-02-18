<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/14
 * Time: 23:35
 */
namespace app\modules\client\controllers;
use Yii;
use app\models\Cat;
use app\models\Order;
use app\modules\school\models\VideoListForm;
class UserController extends CommonController
{

    public function beforeAction($action)
    {

        if (parent::beforeAction($action)) { //先让CommonController的beforeAction执行
            if(!$this->userInfo){
                return Yii::$app->response->redirect(['client/login/index']);
            }
            return true;
        }
        return false;
    }

    public function actionIndex()
    {
        $catList = Order::find()->alias('o')->where(['o.user_id' => $this->userInfo['id'],
            'o.product_type' => 'cat',
            'o.is_pay' => 1])->leftJoin(Cat::tableName() . ' c', 'c.id=o.product_id')->andWhere(['c.is_delete' => 0, 'c.is_display' => 1])->select([
                'c.*'
            ])->orderBy(['c.sort' => SORT_ASC])->asArray()->all();
        $cat_id = $_GET["cat_id"];
        $arr = ['list'=>[]];
        if ($cat_id){
            $form = new VideoListForm();
            $form->attributes = \Yii::$app->request->get();
            $arr = $form->getList();
        }
        // $catList = Cat::find()->where(['is_delete' => 0, 'is_display' => 1])
        //     ->orderBy(['sort' => SORT_ASC])->asArray()->all();
        // return $this->render('cat', [
        //     'list' => $list
        // ]);
        // var_dump($catList);
        // die();
        return $this->render('index', [
            'userName' => $this->userInfo['nickname'],
            'videoList' => $arr['list'],
        'catList' => $catList
        ]);
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->destroy();
        return Yii::$app->response->redirect(['client/login/index']);
    }
}