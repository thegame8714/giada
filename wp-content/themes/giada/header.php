<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html class="no-js ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="no-js ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9]><html class="no-js ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 9]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php if (is_search()) { ?>
                <meta name="robots" content="noindex, nofollow" />
            <?php } ?>
        <title><?php wp_title(''); ?></title>
        <?php wp_head(); ?>
        <!-- IE Fix for HTML5 Tags -->
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script src="<?php bloginfo('template_directory'); ?>/assets/js/respond.js"></script>
        <![endif]-->
    </head>
<body <?php body_class(); ?>>
<header>

        <div class="logo">
                <a href="<?php echo bloginfo('url'); ?>">
<!--                        <img src="--><?php //echo get_bloginfo('template_directory'); ?><!--/assets/img/logo.png" class="img img-responsive">-->
                    <span>Giada</span><span>Zanotti</span>
                </a>
            </div>
         <div class="menu-burger">
                <a href="" class="burger">
                        <i class="fa fa-bars"></i>
                        </a>
                <?php
                $top_nav_args = array(
                    'container' => 'nav',
                    'container_id' => 'primary-nav',
                    'items_wrap' => '<ul class="list-unstyled">%3$s</ul>',
                    'theme_location' => 'primary'
                );

                wp_nav_menu($top_nav_args);
                ?>
            </div>

   </header>