<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;

/**
 * This is the model class for table "{{%auth_assignment}}".
 *
 * @property string $name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 */
class AuthItem extends AuthActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'description'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['description', 'data'], 'string', 'max' => 215],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('app', 'Item Name'),
            'user_id' => Module::t('app', 'User ID'),
            'created_at' => Module::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'name']);
    }
}