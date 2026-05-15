<?php get_header(); ?>

<main class="site-main">

    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>

            <h1 class="entry-title"><?php the_title(); ?></h1>

            <div class="entry-meta">
                <span><?php the_author(); ?></span>
                <span><?php the_date(); ?></span>
            </div>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <div class="article-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn">Wróć</a>
            </div>

        </article>
    <?php endwhile; ?>

</main>

<?php get_footer(); ?>
