<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/1/19
 * Time: 14:49
 */

namespace app\modules\admin\controllers;
use app\models\Advertisement;
use app\modules\admin\models\AdvertisementForm;

class AdvertisementController extends Controller
{
    public function actionAdvertisement()
    {
    	$advertisement = Advertisement::find()->one();
        return $this->render('advertisement',['advertisement'=>$advertisement]);
    }

    public function actionEdit()
    {
        $advertisement = Advertisement::find()->where(['store_id'=>$this->store->id])->one();
    	if(!$advertisement){
    		$advertisement = new Advertisement();
    	}
        if(\Yii::$app->request->isPost){
            $form = new AdvertisementForm();
            $form->advertisement = $advertisement;
            $form->store_id = $this->store->id;
            $form->attributes = \Yii::$app->request->post('model');
            $this->renderJson($form->save());
        }
    	return $this->render('advertisementEdit',['advertisement'=>$advertisement]);
    }
}