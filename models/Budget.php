<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;

/**
 * This is the model class for table "{{%budgets}}".
 *
 * @property string $budget_id
 * @property string $project_id
 * @property string $project_part_id
 * @property string $budget
 * @property string $created_at
 *
 * @property Projects $project
 * @property ProjectParts $projectPart
 *
 * @property boolean @Exceeded 
 */
class Budget extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%budgets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'budget'], 'required'],
            [['project_id', 'project_part_id', 'budget'], 'integer'],
            // Composite key project_id+project_part_id must be unique
            [['project_part_id'], 'unique', 'targetAttribute' => ['project_id', 'project_part_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'budget_id' => Module::t('app', 'Ид. бюджета'),
            'project_id' => Module::t('app', 'Ид. проекта'),
            'project_part_id' => Module::t('app', 'Ид. элемента проекта'),
            'budget' => Module::t('app', 'Бюджет'),
            'created_at' => Module::t('app', 'Создано'),
            'used' => Module::t('app', 'Выдано'),
            'ratio' => Module::t('app', 'Коэфф.'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['project_id' => 'project_id']);
        //if ($project = Project::findOne(['project_id'=>$this->project_id])) return $project;
        //return new Project();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject_part()
    {
        return $this->hasOne(ProjectPart::className(), ['part_id' => 'project_part_id']);
        //if ($project = ProjectPart::findOne(['part_id'=>$this->project_part_id])) return $project;
        //return new ProjectPart();
    }
    
    /**
     * Budget usage
     */
    public function getUsed()
    {
        $sum_estimated = Task::find()
            ->select(['project_id'=>'tasks.project_id', 'project_part_id'=>'project_parts.parent_part_id', 'estimated'=>'sum(tasks.estimated)'])
            ->leftJoin('project_parts', 'tasks.project_part_id = project_parts.part_id')
            ->leftJoin('budgets', 'project_parts.parent_part_id = budgets.project_part_id and tasks.project_id = budgets.project_id')
            ->where(['tasks.project_id'=>$this->project_id, 'project_parts.parent_part_id'=>$this->project_part_id])
            ->andWhere('task_status(task_id) <> -2')
            ->groupBy(['tasks.project_id', 'project_parts.parent_part_id'])->asArray()->one()['estimated'];
        return $sum_estimated;
    }
    
    /**
     * Budget usage in percents
     */
    public function getRatio()
    {
        if ($this->budget > 0)
            return number_format (100 * $this->used / $this->budget, 2);
        return number_format (0, 2);        
    }

    /**
     * Here we will set flags for email notification
     * flag1 must be set true if budget exceeded 75% (on task save)
     * flag2 must be set true if budget exceeded 100% (on task save)
     * flags must be reset when budget has changed
     */
    public function beforeSave ($insert)
    {
        parent::beforeSave($insert);
        if (isset ($this->DirtyAttributes['budget']))
            $this->flag1 = $this->flag2 = false;
        return true;
    }    
}