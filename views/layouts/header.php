<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\projects\Projects as Module;

$user = Yii::$app->user;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">EAS</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Url::base() ?>/img/nopic.png" class="user-image" alt="User Image" style="background-color:white;" />
                        <span class="hidden-xs"><?php if (isset ($user->identity->displayname)) echo $user->identity->displayname ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
<!--                        <li class="user-header">
                            <img src="<?php //= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?php // if(isset ($user->identity->username)) echo $user->identity->username ?>
                                <small><?php //if (isset ($user->identity->roles)) echo $user->identity->roles?></small>
                            </p>
                        </li>
-->                        <!-- Menu Body -->
                        <!--<li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= Url::toRoute(['user/view', 'id'=>$user->id]) ?>" class="btn btn-default btn-flat"><?= Module::t('app', 'Profile') ?></a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Module::t('app', 'Sign out'),
                                    ['/projects/module/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
<!--                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>-->
            </ul>
        </div>
    </nav>
</header>