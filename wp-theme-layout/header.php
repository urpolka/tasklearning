<!DOCTYPE html>
<html>
 <head>
    <meta charset="UTF-8">
    <title>learning</title>
    <?php wp_head(); ?>
    </head>
<body>
    <div class="site-wrapper">
        <header class="site-header">
    <div class="site-header__inner">
        <div class="site-branding">
            <div class="site-title">
                <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
            </div>
            <div class="site-description"><?php bloginfo('description'); ?></div>
        </div>
        <nav class="main-nav">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class'     => 'main-nav__list',
        'container'      => false,
    ) );
    ?>
</nav>
    </div>
</header>