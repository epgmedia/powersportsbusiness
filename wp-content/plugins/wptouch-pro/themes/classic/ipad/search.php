<?php get_header(); ?>	

	<!-- This function figures out what type of archive it is and spits it out as the title in a div class="archive-text" -->
	<?php classic_archive_text(); ?> 
	
	<?php include( 'blog-loop.php' ); ?>

<?php get_footer(); ?>