<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/3/20
 * Time: 17:32
 */

namespace app\modules\admin\models;

use app\models\Classes;
use app\models\ClassUser;
use app\models\User;
use yii\data\Pagination;

class ClassesForm extends \app\models\Model
{
    public $classes;
    public $class_name;
    public $type;
    public $create_user_id;
    public $create_time;

    public $sort;
    public $class_id;
    public $keyword;
    public $limit;
    public $page;
    public $store;

    public function rules()
    {
        return [
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1],
            [['class_name'], 'trim'],
            //[['type', 'create_user_id'], 'required'],
            [['sort'], 'integer', 'min' => 0],
            [['class_id'], 'integer'],
            [['type'], 'integer', 'min' => 0], //  在这里配置数据库字段,save的时候才会写入
            [['sort'], 'default', 'value' => 100],
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'class_name' => '班级名称',
            'type' => '班级类型',
            'create_user_id' => '创建班级的用户Id',
            'create_time' => '创建班级时间',
            'sort' => '排序',
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $query = ClassUser::find()->alias('cu')->where(['cu.class_id' => $this->class_id,
            'cu.is_delete' => 0,
        ])->leftJoin(User::tableName() . ' u', 'u.id=cu.user_id');

        $query->andWhere(['like', 'u.nickname', $this->keyword]);

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->select([
            'cu.*', 'u.*','cu.id as id'
        ])->orderBy(['cu.id' => SORT_DESC])->limit($p->limit)->offset($p->offset)->asArray()->all();

        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count,
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getModelError();
        }

        $this->classes->create_time = time();
        $this->classes->class_name = $this->class_name;
        $this->classes->type = $this->type;
        $this->classes->create_user_id = \Yii::$app->user->identity->id;

        if ($this->classes->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            return $this->getModelError($this->classes);
        }
    }
    public function del($id)
    {
        $item = Classes::findOne($id);
        // $ret = $item->;
        if ($item){
            $item->is_delete=1;
            if ($item->save()){
                return true;
            }
        }
        //var_dump($ret);die();
        return false;
        //$count = Classes()::model()->deleteByPk($id);
        // //$count = Classes()::model()->deleteByPk($id);
        // $classes = new Classes();
        // $ret = $classes->findOne($id)->delete();
        // //var_dump($ret);die();
        // return ret;
    }
    public function delUser($id)
    {
        //$count = Classes()::model()->deleteByPk($id);
        $item = ClassUser::findOne($id);
        // $ret = $item->;
        if ($item){
            $item->is_delete=1;
            if ($item->save()){
                return true;
            }
        }
        //var_dump($ret);die();
        return false;
    }
    public function getList()
    {
        //var_dump($this->keyword);
        if ($this->keyword) {
            $sql_count = "SELECT   COUNT(*)   FROM   zjhj_video_classes INNER JOIN zjhj_video_user ON zjhj_video_user.id=zjhj_video_classes.create_user_id WHERE zjhj_video_classes.is_delete=0 and  zjhj_video_classes.class_name='$this->keyword'";
            $sql_pagination = "SELECT   zjhj_video_classes.*,zjhj_video_user.*,zjhj_video_classes.id as class_id FROM zjhj_video_classes INNER JOIN zjhj_video_user ON zjhj_video_user.id=zjhj_video_classes.create_user_id WHERE  zjhj_video_classes.is_delete=0 and zjhj_video_classes.class_name='$this->keyword'";
        } else {
            $sql_count = "SELECT   COUNT(*)   FROM   zjhj_video_classes INNER JOIN zjhj_video_user ON zjhj_video_user.id=zjhj_video_classes.create_user_id where zjhj_video_classes.is_delete=0";
            $sql_pagination = "SELECT zjhj_video_classes.*,zjhj_video_user.*,zjhj_video_classes.id as class_id
FROM zjhj_video_classes INNER JOIN zjhj_video_user ON zjhj_video_user.id=zjhj_video_classes.create_user_id where zjhj_video_classes.is_delete=0";
        }
        //var_dump($sql_count);die();
        $modle_count = \Yii::$app->db->createCommand($sql_count);

        $count = $modle_count->queryScalar();

        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $model_pagination = \Yii::$app->db->createCommand($sql_pagination . " LIMIT :offset,:limit");
        $model_pagination->bindValue(':offset', $p->offset);
        $model_pagination->bindValue(':limit', $p->limit);

        $list = $model_pagination->queryAll();

        //var_dump($list);

        return [
            'list' => $list,
            'pagination' => $p,
            'row_count' => $count,
        ];

        /*
    $query = Coupon::find()->where(['store_id' => $this->store_id,'is_delete'=>0]);

    if ($this->keyword){
    $query->andWhere(['like', 'name', $this->keyword]);
    }
    $count = $query->count();
    $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
    $list = $query->orderBy(['sort' => SORT_ASC, 'addtime' => SORT_DESC])->offset($p->offset)->limit($p->limit)->asArray()->all();
    return [
    'list' => $list,
    'pagination' => $p,
    'row_count' => $count
    ];
     */
    }

}
