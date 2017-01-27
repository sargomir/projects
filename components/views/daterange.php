<?php
/**
 * Date Range Widget view
 * User: gorev
 * Date: 16.03.2016
 * Time: 9:31
 */

use app\modules\projects\Projects as Module;
use yii\helpers\Html;
use kartik\date\DatePicker;


/**
 * Render daterange filter button
 */
$this->beginBlock('daterange-filter-button');
	echo "<button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">
	<span class=\"glyphicon glyphicon-calendar\"></span>&nbsp{$label}&nbsp<span class=\"caret\"></span></button>";
$this->endBlock();


/**
 * Render daterange dropdown form
 */
$this->beginBlock('daterange-filter-form');
echo "<div class='panel-heading'>{$title}</div>";
echo "<div class='panel-body'>";
$form = yii\widgets\ActiveForm::begin([
    'method' => 'get',
    //'action' => ['task/index'],
//    'options' => ['data-pjax' => true]
]);

// Datepickers block
echo $period_start = $form->field($model, $attribute1)->widget(DatePicker::classname(), [
    'options' => ['placeholder' => Module::t('app', 'Select...')],
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose'=>true,
        'todayHighlight' => true,
        'todayBtn' => "linked",
    ]
]); 
echo $period_end = $form->field($model, $attribute2)->widget(DatePicker::classname(), [
    'options' => [
        'placeholder' => Module::t('app', 'Select...'),
        //'value' => date("d.m.Y"), // default value
//        'value' => $model->period_end,
    ],
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose'=>true,
        'todayHighlight' => true,
        'todayBtn' => "linked",
    ]
]);


// Presets block
echo '<div class="btn-toolbar" role="toolbar">';
$counter = 0;
foreach ($presets as $preset) {
    $counter++;
    $checked = ($model[$attribute1] == $preset['value']['attribute1']
        && $model[$attribute2] == $preset['value']['attribute2']) ? "active" : "";
    echo '<div class="btn-group">';
    echo Html::button($counter, [
        'id' => $id . '-btn-preset' . $counter,
        'class' => "btn btn-xs btn-default btn-group {$checked}",
        'title' => $preset['label'],
        'style' => 'top: 0px; margin: 10px 5px 0px 5px', //glyphicon has 1 px
        'checked' => $checked,
        'onclick' => "document.getElementById('" . Html::getInputId($model, $attribute1) . "').value = \"{$preset['value']['attribute1']}\"; "
            . "document.getElementById('" . Html::getInputId($model, $attribute2) . "').value = \"{$preset['value']['attribute2']}\";"
    ]);
    echo "</div>";
}
echo "</div></div>";


// Apply button
echo '<div class="panel-footer" style="text-align: center; width: 100%">' .
    Html::Button(
        '<div class="glyphicon glyphicon-ok" />&nbsp' . Module::t('app', 'Apply'),
        [ 'id' => $id . '-btn-apply', 'class' => 'btn btn-xs btn-success', 'style' => 'float: none;',
            'onclick' => "jQuery(\"#{$form->id}\").submit();" ]
    )
    . '</div>';
$form::end();
$this->endBlock();

///**
// * Bootstrap closes dropdown-menu when clicked outside its element
// * We do not want that in form so we should disable that event handler
// */
//$this->registerJS("$('.dropdown-menu').find('form').click(function (e) { e.stopPropagation(); });");

?>


<!--Html layout-->

<div class="daterange">
    <div class="row">
        <div class="col-lg-12">
            <div class="button-group">
                <?= $this->blocks['daterange-filter-button'] ?>
                <ul class="dropdown-menu" style="width:220px; padding: 0px;">
                    <div class="panel panel-primary" style="margin-bottom: 0px;">
                        <?= $this->blocks['daterange-filter-form'] ?>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
