<?php if (function_exists('ob_start'))ob_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="stylesheet" type="text/css" href="http://cache.blogads.com/319864931/feed.css" />

<style type="text/css" media="screen">
/*	To accomodate differing install paths of WordPress, images are referred only here,
	and not in the wp-layout.css file. If you prefer to use only CSS for colors and what
	not, then go right ahead and delete the following lines, and the image files. */
		
/*	body { background: #9c9 url("http://media.sethroberts.net/blog/images/background.gif")repeat-x left top; }	*/
<?php /* Checks to see whether it needs a sidebar or not */ if ((! $withcomments) && (! is_single())) { ?>
/*	#page { background: url("http://media.sethroberts.net/blog/images/kubrickbg.jpg") repeat-y top; border: none; }
		#page { background: url("http://media.sethroberts.net/blog/images/sethrobertsbg_950w.jpg") repeat-y top; border: none; }*/
		
		
<?php } else { // No sidebar ?>
	#page { background: url("http://media.sethroberts.net/blog/images/sethrobertsbg_950w.jpg") repeat-y top; border: none; } 
<?php } ?>


/*	#header { background: url("http://media.sethroberts.net/blog/images/sethheader.jpg") no-repeat bottom center; }
		#header { background: url("http://media.sethroberts.net/blog/images/sethheader_950w.jpg") no-repeat bottom center; }
	#footer { background: url("http://media.sethroberts.net/blog/images/sethrobertsfooter_950w.jpg") no-repeat bottom; border: none;}*/

/*	Because the template is slightly different, size-wise, with images, this needs to be set here
	If you don't want to use the template's images, you can also delete the following two lines. */
		
/*	#header 	{ margin: 0 !important; margin: 0 0 0 1px; padding: 1px; height: 198px; width: 758px; }
	#headerimg 	{ margin: 7px 9px 0; height: 192px; width: 740px; } */

/* 	To ease the insertion of a personal header image, I have done it in such a way,
	that you simply drop in an image called 'personalheader.jpg' into your /images/
	directory. Dimensions should be at least 760px x 200px. Anything above that will
	get cropped off of the image. */
	/*
	#headerimg { background: url('http://media.sethroberts.net/blog/images/personalheader.jpg') no-repeat top;}
	*/
</style>

<?php wp_head(); ?>
</head>
<body>
<div id="page">


<div id="header">
	<div id="headerimg">
		<h1><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	</div>
</div>
<hr />
