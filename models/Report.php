<?php

namespace app\modules\projects\models;

use Yii;
use \yii\db\Expression;

/**
 * This is the model class for table "{{%report_workers}}".
 *
 * @property string $worker_id
 * @property string $project
 * @property string $part
 * @property string $tasks_total
 * @property string $tasks_active
 * @property string $tasks_completed
 * @property string $tasks_estimated
 * @property string $tasks_elapsed
 */
class Report extends Task
{
    public $tasks_issued;
    public $tasks_estimated;
    public $tasks_elapsed;
    public $tasks_failed;
    
    public $_project;
    public $_parent_part;
    public $_project_part;
    public $_budget;
    
    //public function getBudget()
    //{
    //    $budget = Budget::findOne(['project_id'=>$this->project_id, 'project_part_id'=>$this->parent_part->part_id]);
    //    $this->link('budget', $budget);
    //    return $budget;
    //    //
    //    //$query = $class::find();
    //    //$query->primaryModel = $this;
    //    //$query->link = $link;
    //    //$query->multiple = false;
    //    //return $query;
    //    //
    //    //return $budget;
    //    //return $this->parent_part->budget;
    //    return $this
    //        ->hasOne(Budget::className(), [
    //            'project_id' => 'task.project_id',
    //            'project_part_id' => 'parent_part_id'
    //        ])
    //        ->via('parent_part');
    //        //->andOnCondition(['budgets.project_id' => new Expression('tasks.project_id')]);
    //        //->andOnCondition(['budgets.project_part_id' => new Expression('budget_parts.parent_part_id')])
    //        //->where(['budgets.project_id'=>$this->project_id])
    //        //->via('project_part');
    //        //->viaTable(ProjectPart::tableName() . ' budget_parts', ['part_id' => 'project_part_id']);
    //        //->viaTable(ProjectPart::tableName() . ' budget_parts', ['part_id' => 'project_part_id']);
    //}
    
    public function getParent_part()
    {
        return $this
            ->hasOne(ProjectPart::className(), ['part_id'=>'parent_part_id'])
            ->from(ProjectPart::tableName() . ' parent_parts')
            ->via('project_part');
    }

}