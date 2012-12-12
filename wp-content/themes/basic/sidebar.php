<div id="sidebar">

<div id="googleads">
<script type="text/javascript"><!--
google_ad_client = "pub-7181320835497271";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_ad_type = "text";
google_ad_channel = "";
google_color_border = "999999";
google_color_bg = "eeeeee";
google_color_link = "990000";
google_color_text = "000000";
google_color_url = "000000";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>

  <ul>

   <?php /* If this is a 404 page */ if (is_404()) { ?>

   <?php /* If this is a category archive */ } elseif (is_category()) { ?>

   <li><p>You are currently browsing the archives for the <?php single_cat_title(''); ?> category.</p></li>

   <?php /* If this is a yearly archive */ } elseif (is_day()) { ?>

   <li><p>You are currently browsing the <a href="<?php bloginfo('home'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives for the day <?php the_time('l, F jS, Y'); ?>.</p></li>

   <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
   <li><p>You are currently browsing the <a href="<?php bloginfo('home'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives for <?php the_time('F, Y'); ?>.</p></li>

   <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
   <li><p>You are currently browsing the <a href="<?php bloginfo('home'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives for the year <?php the_time('Y'); ?>.</p></li>

   <?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
   <li><p>You have searched the <a href="<?php echo bloginfo('home'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives for <strong>'<?php echo wp_specialchars($s); ?>'</strong>.
   If you are unable to find anything in these search results, you can try one of these links.</p></li>

   <?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
   <li><p>You are currently browsing the <a href="<?php echo bloginfo('home'); ?>/"><?php echo bloginfo('name'); ?></a> weblog archives.</p></li>

   <?php } ?>

   <?php if( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ): ?>
   <?php endif; ?>

  </ul>

<a href="http://sethroberts.net/" style="display:block;height: 460px; width: 177px;">
<img src="http://media.sethroberts.net/ad/shangriladiet_ad.gif" alt="The Shangri-La Diet Ad" width="177" height="460" border="0" /></a>

<div style="margin: 8px 0 0 8px;">
	<script type="text/javascript" src="http://s50.sitemeter.com/js/counter.asp?site=s50sethroberts"></script>
	<noscript><a href="http://s50.sitemeter.com/stats.asp?site=s50sethroberts" target="_top"><img src="http://s50.sitemeter.com/meter.asp?site=s50sethroberts" alt="Site Meter" border="0"/></a></noscript>
</div>
</div>
