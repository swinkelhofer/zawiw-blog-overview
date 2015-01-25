<?php
add_shortcode('zawiw_blog_overview', 'zawiw_blog_overview_shortcode');
add_action( 'wp_enqueue_scripts', 'zawiw_blog_overview_queue_stylesheet' );

function zawiw_blog_overview_shortcode($filter)
{
	global $wpdb;
	if(isset($filter) && isset($filter["filter_out"]))
		$preg = '/' . strtolower($filter["filter_out"]) . '/';
		
	$start_blog = $wpdb->blogid;
	$blog_list = $wpdb->get_col('SELECT blog_id FROM ' . $wpdb->blogs);
	echo "<div id='zawiw_blog_overview'>";
	foreach ($blog_list as $blogid)
	{
		switch_to_blog($blogid);
		$name = get_bloginfo('name');
		$url = get_bloginfo('url');
		$url = str_replace("https", "http", $url);
		if(isset($preg) && preg_match($preg, strtolower($name)) === 1)
			continue;
		else
			echo "<div class='zawiw_blog_overview_item'><a href='" . $url . "'>" . $name . '</a></div>';
	}
	echo "</div>";
	switch_to_blog($start_blog);
}

function zawiw_blog_overview_queue_stylesheet()
{
	global $post;	//Contains the whole site content
	if(!has_shortcode($post->post_content, 'zawiw_blog_overview'))	//Loads stylesheets only if shortcode exists
		return;
    	wp_enqueue_style( 'zawiw_blog_overview_style', plugins_url( 'style.css', __FILE__ ) );
}

?>
