<<?php get_header(); ?>
<div class="content-area">
    <main>

<?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php the_post_thumbnail('large'); ?>

            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

            <div class="post-meta">
                <?php echo get_the_date(); ?>
                <?php the_author_posts_link(); ?>
                <?php the_category(', '); ?>
            </div>

            <div class="entry-content">
                <?php the_content(); ?>
                <?php // або для анонсу: the_excerpt(); ?>
            </div>
        </article>

    <?php endwhile; ?>

    <?php the_posts_pagination(); ?>

<?php else : ?>
    <p>Постів не знайдено.</p>
<?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>