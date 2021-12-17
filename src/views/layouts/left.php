<?php

use yii\bootstrap\Nav;
?>
    <aside class="main-sidebar">

        <section class="sidebar">

            <section class="sidebar">

                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
                    </div>
                    <div class="pull-left info">
                        <p><?=Yii::$app->user->identity->phone?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
                    </div>
                </div>
                <?php
                $items=[
                    ['label' => '常用功能', 'header' => true],
                    [
                        'label' => '权限管理',
                        'badge' => '<span class="right badge badge-info">2</span>',
                        'items' => [
                            ['label' => '用户','url' => ['user/index']],
                            ['label' => '菜单','url' => ['menu/index']],
                            ['label' => '角色','url' => ['role/index']],
                            ['label' => '权限','url' => ['permission/index']],
                            ['label' => '规则','url' => ['script/index']],
                        ]
                    ],

                ];

                echo dmstr\widgets\Menu::widget(
                    [
                        'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                        'items'=> $items
                    ]
                )
                ?>


            </section>

    </aside>


<?php \common\widgets\JsBlock::begin(); ?>
    <script>
    $(function(){
        $('.sidebar-menu li:not(.treeview) > a').on('click', function(){
            var $parent = $(this).parent().addClass('active');
            $parent.siblings('.treeview.active').find('> a').trigger('click');
            $parent.siblings().removeClass('active').find('li').removeClass('active');
        });

        $(window).on('load', function(){
            $('.sidebar-menu a').each(function(){
                if(this.href === window.location.href){
                    $(this).parent().addClass('active')
                        .closest('.treeview-menu').addClass('.menu-open')
                        .closest('.treeview').addClass('active');
                }
            });
        });
    });

    </script>
<?php \common\widgets\JsBlock::end(); ?>