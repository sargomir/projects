<?php
namespace app\modules\projects\components;

use yii\base\Widget;
use yii\helpers\Html;

class DropCheckWidget extends Widget{
	public $label;
	public $model;
	public $value;
	public $items;
	
	public function init(){
		parent::init();
		if($this->items===null){
			$this->items = [];
		}
	}
	
	public function run(){
		return $this->render('dropcheck', [
			'id' 	=> $this->id,
			'label' => $this->label,
			'value' => $this->value, 
			'items' => $this->items
		]);
	}
}
?>