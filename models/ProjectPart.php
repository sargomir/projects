<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;

/**
 * This is the model class for table "{{%ProjectPart}}".
 *
 * @property string $PartID
 * @property string $ParentID
 * @property string $Code
 * @property string $Part
 *
 * @property ProjectPart $parent
 * @property ProjectPart[] $projectParts
 * @property Tasks[] $tasks
 */
class ProjectPart extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_parts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_part_id'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['part'], 'string', 'max' => 150],
            [['bdds_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'part_id' => Module::t('app', 'Part ID'),
            'parent_part_id' => Module::t('app', 'Parent ID'),
            'code' => Module::t('app', 'Code'),
            'part' => Module::t('app', 'Part'),
            'bdds_id' => Module::t('app', 'BDDS ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ProjectPart::className(), ['part_id' => 'parent_part_id'])->from(ProjectPart::tableName() . ' PP');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectParts()
    {
        return $this->hasMany(ProjectPart::className(), ['parent_part_id' => 'part_id'])->from(ProjectPart::tableName() . ' SP');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['project_part_id' => 'part_id']);
    }
}