<?php
use app\modules\projects\Projects as Module;
$user = Yii::$app->user;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= \yii\helpers\Url::base() ?>/img/nopic.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php if (isset ($user->id)) echo $user->id ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
<!--        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->

        <?= app\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'СУЗП', 'options' => ['class' => 'header']],
                    
                    [
                        'label' => Module::t('app', 'Projects'),
                        'url' => ['/projects/project/index'],
                        'icon' => 'fa fa-briefcase',
                        'visible' => Yii::$app->user->can('worker')                     
                    ],
                    [
                        'label' => Module::t('app', 'Project Parts'),
                        'url' => ['/projects/projectpart/index'],
                        'icon' => 'fa fa-cubes',
                        'visible' => Yii::$app->user->can('admin')                     
                    ],                          
                    [
                        'label' => Module::t('app', 'Budgets'),
                        'url' => ['/projects/budget/index'],
                        'icon' => 'fa fa-calculator',
                        'visible' => Yii::$app->user->can('worker')                     
                    ],
                    [
                        'label' => Module::t('app', 'Tasks'),
                        'url' => ['/projects/task/index'],
                        'icon' => 'fa fa-tasks',
                        'visible' => Yii::$app->user->can('worker')                     
                    ],
                    [
                        'label' => Module::t('app', 'Отчеты'),
                        'url' => '#',
                        'icon' => 'fa fa-line-chart',
                        'items' => [
                            [
                                'label' => 'По проектам',
                                'url' => ['/projects/report/project']
                            ],
                            [
                                'label' => 'По работникам',
                                'url' => ['/projects/report/worker']
                            ],                            
                            [
                                'label' => 'По задачам проекта',
                                'url' => ['/projects/report/task']
                            ],
                            [
                                'label' => 'Отчёт за истекший месяц',
                                'url' => ['/projects/report/accounting'],
                            ],
                            [
                                'label' => 'Экспорт в BDDS',
                                'url' => ['/projects/report/export'],
                                'visible' => Yii::$app->user->can('admin'),
                            ],                           
                        ],
                    ],                    
                    [
                        'label' => Module::t('app', 'Users'),
                        'url' => ['/projects/user/index'],
                        'icon' => 'fa fa-users',                        
                        'visible' => Yii::$app->user->can('user_manager')
                    ],
                    [
                        'label' => Module::t('app', 'Help'),
                        'url' => ['/projects/module/index'],
                        'icon' => 'fa fa-info',
                    ],

                    ['label' => 'Login', 'url' => ['module/login'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>