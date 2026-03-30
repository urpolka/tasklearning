<?php
/*
Template Name: Про мене
*/
?>
<?php get_header(); ?>
<main>
<section class="about">
<?php while (have_posts()) : the_post(); ?>
<h1><?php the_title(); ?></h1>
<div><?php the_content(); ?></div>
<?php endwhile; ?>
</section>
</main>
<?php get_footer(); ?>