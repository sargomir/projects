<?php
use yii\helpers\Html;
  use yii\helpers\ArrayHelper;

use yii\bootstrap\ActiveForm;

  use kartik\widgets\Select2;
  
  use app\modules\projects\Projects as Module;
  use app\modules\projects\models\AuthUser;
  
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Module::t('app', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="module-login" style="
    width:400px; margin:7% auto;
    background: transparent linear-gradient(to bottom, #F5F5F5 0%, #FFF 19%, #FFF 77%, #F5F5F5 100%);
    box-shadow: 0px 0px 0px 5px rgba(0, 0, 0, 0.15);
    padding: 15px;
    ">
    <h1><?= Html::encode($this->title) ?></h1>

    <!--<p>Please fill out the following fields to login:</p>-->

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        //'options' => ['class' => 'form-horizontal'],
        //'fieldConfig' => [
        //    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        //    'labelOptions' => ['class' => 'col-lg-1 control-label'],
        //],
    ]); ?>

    <?php
		/**
		 * Будем показывать выпадающий список пользователей только для локальных подсете
		 */
		$ip = Yii::$app->request->UserIP;
		$validator = new yii\validators\IpValidator();
		$validator->setRanges([
			'192.168.0.1/24', // Основная
			'192.168.100.1/24' // VPN
		]);
		if ($validator->validate($ip, $error)) {
			echo $form->field($model, 'username')
				->widget(Select2::classname(), [
					'data' => ArrayHelper::map(AuthUser::find()->where('IFNULL(disabled, 0) = 0')->orderBy('username')->all(), 'id', 'username'),
					'options' => ['placeholder' => Module::t('app', 'Select ...')],
					'pluginOptions' => [
						'allowClear' => true,
					],
				])
				->label(Module::t('app', 'User'));
		}
		else echo $form->field($model, 'username');
	?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 <!--col-lg-11-->">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        <?= Module::t('app', 'Forgot Password?<br> Call {number} to get a new password.', ['number'=>'<code><div class="fa fa-phone">110</div></code>']) ?>
    </div>
</div>
