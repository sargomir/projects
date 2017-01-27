<?php
/**
 * Date Range Widget
 * User: gorev
 * Date: 16.03.2016
 * Time: 9:26
 */

namespace app\modules\projects\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class DateRangeWidget extends Widget{
    public $label;
    public $title;
    public $model;
    public $attribute1;
    public $attribute2;
    public $from;
    public $to;
    public $presets;

    public function init(){
        parent::init();
        if($this->presets===null){
            $this->presets = [];
        }

        /**
         * Bootstrap closes dropdown-menu when clicked outside its element
         * We do not want that in form so we should disable that event handler
         */
        $this->view->registerJS("$('.dropdown-menu').find('form').click(function (e) { e.stopPropagation(); });");
    }

    public function run(){
        $this->view->registerJS("
                $(document).on('pjax:end', function (t) {
                    $('.dropdown-menu').find('form').click(function (e) { e.stopPropagation(); });
                    jQuery('#tasksearch-period_start-kvdate').kvDatepicker({\"format\":\"dd.mm.yyyy\",\"autoclose\":true,\"todayHighlight\":true,\"todayBtn\":\"linked\",\"language\":\"ru\"});
                    jQuery('#tasksearch-period_end-kvdate').kvDatepicker({\"format\":\"dd.mm.yyyy\",\"autoclose\":true,\"todayHighlight\":true,\"todayBtn\":\"linked\",\"language\":\"ru\"});
                });
        ");

        return $this->render('daterange', [
            'id'            => $this->id,
            'label'         => $this->label,
            'title'         => $this->title,
            'model'         => $this->model,
            'attribute1'    => $this->attribute1,
            'attribute2'    => $this->attribute2,
            'presets'       => $this->presets
        ]);
    }
}
?>