<?php
use app\modules\projects\Projects as Module;
use yii\helpers\Html;

/**
 * Render Filter Button 
 */
$this->beginBlock('filter-button');
	$checked_count = 0; $result = "";
	foreach ($items as $i=>$item)
		if ($item['checked']) {
			$checked_count++;
			$result .= "<div class=\"{$item['class']}\">&nbsp</div>";
		}
	if ($checked_count < 1) $result .= $label;
	echo "<button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">
	<span class=\"glyphicon glyphicon-filter\"></span>&nbsp{$result}&nbsp<span class=\"caret\"></span></button>";
$this->endBlock();

/**
 * 
 * Render Filter List Items
 */
$this->beginBlock('filter-items');
	echo '<div class="panel panel-primary" style="margin-bottom: 0px;">';
	echo "<div class='panel-heading'>Фильтр по статусу</div>";

	$form = yii\widgets\ActiveForm::begin([
		'method' => 'get',
		//'action' => ['task/index'],
	]);

	$result = "";
	foreach ($items as $i=>$item) {
		$checked = "";
		if ($item['checked']) {
			// boolean -> html attribute
			$checked = "checked";
			/**
			 * html posts only true values for checkboxes
			 * so we initialize request with hidden inputs to send ""
			 * and overwrite these values later in normal inputs
			 */
			$result .= "<input type=\"hidden\" id=\"Tests\" name=\"{$value}[{$i}]\" value=\"\" />";
		}
		$result .= "
		<li><a class=\"small\" data-value=\"1\" tabIndex=\"-1\">
		<input type=\"checkbox\" name=\"{$value}[{$i}]\" value=\"{$item['value']}\" {$checked} />
		<div class=\"{$item['class']}\">{$item['label']}</div>
		</a></li>
		";
	}
	echo "<div class='panel-body'>" . $result . "</div>";

	// Apply button
	echo '<div class="panel-footer" style="text-align: center; width: 100%">' .
		Html::Button(
			'<div class="glyphicon glyphicon-ok" />&nbsp' . Module::t('app', 'Apply'),
			[ 'id' => $id . '-btn-apply', 'class' => 'btn btn-xs btn-success', 'style' => 'float: none;',
				'onclick' => "jQuery(\"#{$form->id}\").submit();" ]
		)
		. '</div>';
	echo "</div>";

	$form::end();
$this->endBlock();

?>

<div class="dropcheck">
 <div class="row">
  <div class="col-lg-12">
   <div class="button-group">
    <?= $this->blocks['filter-button'] ?>
    <ul class="dropdown-menu">
	 <?= $this->blocks['filter-items'] ?>
	</ul>
   </div>
  </div>
 </div>
</div>
