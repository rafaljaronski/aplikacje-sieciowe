<?php get_header(); ?>

<main class="site-main">

    <?php if (have_posts()) : ?>

        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>

                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>

                <div class="entry-meta">
                    <span><?php the_author(); ?></span>
                    <span><?php the_date(); ?></span>
                    <?php if (has_category()) : ?>
                        <span><?php the_category(', '); ?></span>
                    <?php endif; ?>
                </div>

                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>

            </article>
        <?php endwhile; ?>

        <div class="pagination">
            <?php the_posts_pagination(); ?>
        </div>

    <?php else : ?>
        <p class="no-content">Brak wpisów do wyświetlenia.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
