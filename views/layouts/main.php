<?php
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */
if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {
    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }
    app\assets\AdminLteAsset::register($this);
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/bower/admin-lte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini <?php if (Yii::$app->session['sidebarcollapse']) echo "sidebar-collapse" ?>">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    
    
    <!--This script will send sidebar-toggle state to server to store it in session-->
    <script type="text/javascript">
        jQuery(document).load(
            $(".sidebar-toggle").on( "click", function() {
                if ($("body").hasClass("sidebar-collapse"))
                    b_collapse = 0;
                else
                    b_collapse = 1;
                $.post('<?= \yii\helpers\Url::toRoute('module/sidebarcollapse') ?>', {sidebarcollapse: b_collapse});
            })
        );
    </script>    
    
    
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>