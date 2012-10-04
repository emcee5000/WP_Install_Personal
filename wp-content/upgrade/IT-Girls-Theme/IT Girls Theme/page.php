<?php get_header(); ?>
<article id="content">
<?php the_post(); ?>
<div id="post">
<h1><?php the_title(); ?></h1>
<p><?php the_content(); ?></p>
</div><!--/post-->
<?php get_footer(); ?>