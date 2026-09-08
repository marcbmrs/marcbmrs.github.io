<?php get_header(); ?>
<article class="dmb-single"><div class="dmb-container"><?php while ( have_posts() ) : the_post(); ?><header class="dmb-single__header"><h1><?php the_title(); ?></h1></header><div class="dmb-entry"><?php the_content(); ?></div><?php endwhile; ?></div></article>
<?php get_footer(); ?>
