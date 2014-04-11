<?php 
/*
Template Name: FOX Targetted
*/
get_header(); ?>

<div id="content">
<!-- PSB_Fox_Targeted_728 -->
<div style="margin:10px auto;width:728px;height:90background-color:#000000;">
<div id='div-gpt-ad-1391092863898-0' style='width:728px; height:90px;'>
<script type='text/javascript'>
googletag.cmd.push(function() { googletag.display('div-gpt-ad-1391092863898-0'); });
</script>
</div>
</div>

	<div id="contentleft">
	
		<div class="postarea">
	
		<?php include(TEMPLATEPATH."/breadcrumb.php");?>
			
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php $title=get_post_meta($post->ID, title, true); if (!$title) { ?><h1><?php the_title(); ?></h1><?php } ?>
				<?php edit_post_link('(Edit This Page)', '<p>', '</p>'); ?>
				<div style="float:right;">
                    <!-- PSB_Fox_Targeted_300 -->
                    <div id='div-gpt-ad-1391029424174-0' style='width:300px; height:250px;'>
                        <script type='text/javascript'>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1391029424174-0'); });
                        </script>
                    </div>
                </div>
			<?php the_content(__('[Read more]'));?>
		 			
			<?php endwhile; else: ?>
			
			<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php endif; ?>
						
		</div>
		
	</div>
	
<?php include(TEMPLATEPATH."/sidebar.php");?>

</div>

<!-- The main column ends  -->

<?php get_footer(); ?>