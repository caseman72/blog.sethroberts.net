<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>

		<?php include (TEMPLATEPATH . "/sidebar-left.php"); ?>

	<div id="content" class="narrowcolumn">


<h2>Links:</h2>
<ul>
<?php get_links_list(); ?>
</ul>

</div>	

<?php get_sidebar(); ?>

<?php get_footer(); ?>
