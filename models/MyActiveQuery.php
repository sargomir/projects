<?php

namespace app\modules\projects\models;

/**
* This is the ActiveQuery class for [[Availability]].
*
* @see Availability
*/
class MyActiveQuery extends \yii\db\ActiveQuery
{
    //public function init()
    //{
    //    // We filter selected GridView items            
    //    $controller = \Yii::$app->controller->id;
    //    $action = \Yii::$app->controller->action->id;
    //    $model = $this->modelClass;
    //    $tablename = $model::tableName();
    //    $primarykey = $model::primaryKey()[0];
    //    if (isset (\Yii::$app->session["$controller$action"])
    //        // We want to apply select filter only to GridView query, not to subqueries
    //        && strtolower(\app\modules\warehouse\controllers\MyController::ActionTable($controller, $action)) == strtolower($tablename))
    //        foreach (\Yii::$app->session["$controller$action"] as $key)
    //        {
    //            if (is_array($key))
    //                $this->orFilterWhere($key);
    //            else
    //                $this->orWhere(["{$tablename}.{$primarykey}" => $key]);
    //        }
    //}
 
    public function all($db = null)
    {
        return parent::all($db);
    }
 
    public function one($db = null)
    {
        return parent::one($db);
    }
  
} 
