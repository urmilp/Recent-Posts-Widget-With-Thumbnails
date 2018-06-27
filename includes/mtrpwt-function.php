<?php
/**
 * Plugin generic functions file
 *
 * @package Recent Posts Widget With Thumbnails
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Recent_Posts_Widget_With_Thumbnails widget class
 *
 * @since 1.0
 *
* Adds MT Recent Posts Widget With Thumbnails widget
*/
class Mtrecentpostswidgetw_Widget extends WP_Widget {

/** constructor */	
	function __construct() {
    	$widget_ops = array(
			'classname'   => 'recent_posts_widget_with_thubnail', 
			'description' => __('Recent Posts With Thumbnails')
		);
    	parent::__construct('recent-posts-widget-with-thumbnails', __('Recent Posts With Thumbnails'), $widget_ops);
	}


	function widget($args, $instance) {
           

			extract( $args );

		
			$title = apply_filters( 'widget_title', empty($instance['title']) ? 'Recent Posts' : $instance['title'], $instance, $this->id_base);	
			
			if ( ! $number = absint( $instance['number'] ) ) $number = 5;
			
			if ( ! $excerpt_length = absint( $instance['excerpt_length'] ) ) $excerpt_length = 5;
			
			if( ! $cats = $instance["cats"] )  $cats='';
			
			if( ! $show_type = $instance["show_type"] )  $show_type='post';
			
			if( ! $show_page_id = $instance["show_page_id"] )  $show_page_id='';
			
			if( ! $thumb_h =  absint($instance["thumb_h"] ))  $thumb_h=50;
			
			if( ! $thumb_w =  absint($instance["thumb_w"] ))  $thumb_w=50;
			
			if( ! $excerpt_readmore = $instance["excerpt_readmore"] )  $excerpt_readmore='Read more &rarr;';
			
			$default_sort_orders = array('date', 'title', 'comment_count', 'rand');
			
			  if ( in_array($instance['sort_by'], $default_sort_orders) ) {
			
				$sort_by = $instance['sort_by'];
			
				$sort_order = (bool) $instance['asc_sort_order'] ? 'ASC' : 'DESC';
			
			  } else {
			
				// by default, display latest first
			
				$sort_by = 'date';
			
				$sort_order = 'DESC';
			
			  }
			
			
			//Excerpt more filter
            $new_excerpt_more= create_function('$more', 'return " ";');	
			add_filter('excerpt_more', $new_excerpt_more);
			
			
			
			// Excerpt length filter
			$new_excerpt_length = create_function('$length', "return " . $excerpt_length . ";");
			
			if ( $instance["excerpt_length"] > 0 ) add_filter('excerpt_length', $new_excerpt_length);
			
			
			// post info array.
			
			$my_args=array(
						   
				'showposts' => $number,
			
				'category__in'=> $cats,
			
				'orderby' => $sort_by,
			
				'order' => $sort_order,
				
				'post_type' => $show_type,
				
				);
			
			
			
			$mt_recent_posts = null;
			
			$mt_recent_posts = new WP_Query($my_args);
			
			
			
			echo $before_widget;
			
			
			
			// Widget title
			
			echo $before_title;
			
			echo $instance["title"];
			
			echo $after_title;
			
			
			
			// Post list
			echo '<div class="widget widget_recent_entries">';
			echo "<ul>\n";
			
		

		while ( $mt_recent_posts->have_posts() )

		{

			$mt_recent_posts->the_post();

		?>

			<li>

				<div class="post_img">
						<?php if ($instance["thumb"] ) : ?>
				
								<?php

									if (function_exists('the_post_thumbnail') && current_theme_supports("post-thumbnails") && $instance["thumb"] &&has_post_thumbnail()) :
									
									  $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
									  $plugin_dir = 'recent-posts-widget-with-thumbnails-widget';
								?>

								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
									<?php $fet_imag = WPRPWT_URL . '/' . $plugin_dir .'/'.'timthumb/thumb.php?src='. $thumbnail[0] .'&h='.$thumb_h.'&w='.$thumb_w.'&z=0'; ?>
								<?php if($thumbnail) { ?>
									<img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title_attribute(); ?>" width="<?php echo $thumb_w; ?>" height="<?php echo $thumb_h; ?>" />
								<?php }else{ ?>
								
									<img src="<?php echo WPRPWT_DIR; ?>/assets/images/no_thumb.jpg" alt="no image">
								<?php } ?>
								</a>

						<?php endif; ?>
						
						
				</div>
				<div class="post_body">
                        <a href="<?php the_permalink(); ?>" class="rec_post_title" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                        <?php if ( $instance['date'] ) : ?>
							<span><i class="fa fa-calendar"></i> <?php the_time( 'd F Y'); ?></span>
						<?php endif; ?>
						<?php if ( $instance['comment_num'] ) : ?>
							<span class="comment-num">(<?php comments_number(); ?>)</span>
							<?php endif; ?>
						<?php if ( $instance['excerpt'] ) : ?>
						<?php if ( $instance['readmore'] ) : $linkmore = ' <a href="'.get_permalink().'" class="more-link">'.$excerpt_readmore.'</a>'; else: $linkmore =''; endif; ?>
							<p><?php echo get_the_excerpt() . $linkmore; ?> </p>

						<?php endif; ?>
					 
						<?php endif; ?>
					
                </div>	  
			
			</li>

		<?php

		}

		 wp_reset_query();

		echo "</ul>\n";
		echo '</div>';

		

		echo $after_widget;


       
		remove_filter('excerpt_length', $new_excerpt_length);
        remove_filter('excerpt_more', $new_excerpt_more);
			

	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['cats'] = $new_instance['cats'];
		$instance['sort_by'] = esc_attr($new_instance['sort_by']);
		$instance['show_type'] = esc_attr($new_instance['show_type']);
		$instance['asc_sort_order'] = esc_attr($new_instance['asc_sort_order']);
		$instance['number'] = absint($new_instance['number']);
		$instance["thumb"] = esc_attr($new_instance['thumb']);
        $instance['date'] =esc_attr($new_instance['date']);
        $instance['comment_num']=esc_attr($new_instance['comment_num']);
		$instance["excerpt_length"]=absint($new_instance["excerpt_length"]);
		$instance["excerpt_readmore"]=esc_attr($new_instance["excerpt_readmore"]);
		$instance["thumb_w"]=absint($new_instance["thumb_w"]);
		$instance["thumb_h"]=absint($new_instance["thumb_h"]);
		$instance["excerpt"]=esc_attr($new_instance["excerpt"]);
		$instance["readmore"]=esc_attr($new_instance["readmore"]);
		$instance["show_page_id"]=esc_attr($new_instance["show_page_id"]);
		
		
		
		return $instance;
	}
	
	
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$thumb_h = isset($instance['thumb_h']) ? absint($instance['thumb_h']) : 50;
		$thumb_w = isset($instance['thumb_w']) ? absint($instance['thumb_w']) : 50;
		$show_type = isset($instance['show_type']) ? esc_attr($instance['show_type']) : 'post';
		$excerpt_length = isset($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 5;
		$excerpt_readmore = isset($instance['excerpt_readmore']) ? esc_attr($instance['excerpt_readmore']) : 'Read more &rarr;';
		$show_page_id = isset($instance['show_page_id']) ? esc_attr($instance['show_page_id']) : '';
		
		
		

		
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        
        <p>
        
            <label for="<?php echo $this->get_field_id("sort_by"); ?>">
        
        <?php _e('Sort by');	 ?>:
        
        <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by"); ?>">
        
          <option value="date"<?php selected( $instance["sort_by"], "date" ); ?>>Date</option>
        
          <option value="title"<?php selected( $instance["sort_by"], "title" ); ?>>Title</option>
        
          <option value="comment_count"<?php selected( $instance["sort_by"], "comment_count" ); ?>>Number of comments</option>
        
          <option value="rand"<?php selected( $instance["sort_by"], "rand" ); ?>>Random</option>
        
        </select>
        
            </label>
        
        </p>
        
        
        <p>
        
            <label for="<?php echo $this->get_field_id("asc_sort_order"); ?>">
        
        <input type="checkbox" class="checkbox" 
        
          id="<?php echo $this->get_field_id("asc_sort_order"); ?>" 
        
          name="<?php echo $this->get_field_name("asc_sort_order"); ?>"
        
          <?php checked( (bool) $instance["asc_sort_order"], true ); ?> />
        
                <?php _e( 'Reverse sort order (ascending)' ); ?>
        
            </label>
        
        </p>
        
        
        
        
        <p>
        
            <label for="<?php echo $this->get_field_id("excerpt"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("excerpt"); ?>" name="<?php echo $this->get_field_name("excerpt"); ?>"<?php checked( (bool) $instance["excerpt"], true ); ?> />
        
                <?php _e( 'Include post excerpt' ); ?>
        
            </label>
        
        </p>
        
        
        
        <p>
        
            <label for="<?php echo $this->get_field_id("excerpt_length"); ?>">
        
                <?php _e( 'Excerpt length (in words):' ); ?>
        
            </label>
        
            <input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("excerpt_length"); ?>" name="<?php echo $this->get_field_name("excerpt_length"); ?>" value="<?php echo $excerpt_length; ?>" size="3" />
        
        </p>
        
        <p>
        
            <label for="<?php echo $this->get_field_id("readmore"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("readmore"); ?>" name="<?php echo $this->get_field_name("readmore"); ?>"<?php checked( (bool) $instance["readmore"], true ); ?> />
        
                <?php _e( 'Include read more link in excerpt' ); ?>
        
            </label>
        
        </p>
        
        <p>
        
            <label for="<?php echo $this->get_field_id("excerpt_readmore"); ?>">
        
                <?php _e( 'Excerpt read more text:' ); ?>
        
            </label>
        
            <input style="text-align: center;" type="text" id="<?php echo $this->get_field_id("excerpt_readmore"); ?>" name="<?php echo $this->get_field_name("excerpt_readmore"); ?>" value="<?php echo $excerpt_readmore; ?>" size="10" />
        
        </p>
        <p>
        
            <label for="<?php echo $this->get_field_id("date"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("date"); ?>" name="<?php echo $this->get_field_name("date"); ?>"<?php checked( (bool) $instance["date"], true ); ?> />
        
                <?php _e( 'Include post date' ); ?>
        
            </label>
        
        </p>
        
        
        <p>
        
            <label for="<?php echo $this->get_field_id("comment_num"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("comment_num"); ?>" name="<?php echo $this->get_field_name("comment_num"); ?>"<?php checked( (bool) $instance["comment_num"], true ); ?> />
        
                <?php _e( 'Show number of comments' ); ?>
        
            </label>
        
        </p>
        
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
        <?php if ( function_exists('the_post_thumbnail') && current_theme_supports("post-thumbnails") ) : ?>
        
        <p>
        
            <label for="<?php echo $this->get_field_id("thumb"); ?>">
        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("thumb"); ?>" name="<?php echo $this->get_field_name("thumb"); ?>"<?php checked( (bool) $instance["thumb"], true ); ?> />
        
                <?php _e( 'Show post thumbnail' ); ?>
        
            </label>
        
        </p>
        
        <p>
        
            <label>
        
                <?php _e('Thumbnail dimensions'); ?>:<br />
        
                <label for="<?php echo $this->get_field_id("thumb_w"); ?>">
        
                    W: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_w"); ?>" name="<?php echo $this->get_field_name("thumb_w"); ?>" value="<?php echo $thumb_w; ?>" />
        
                </label>
        
                
        
                <label for="<?php echo $this->get_field_id("thumb_h"); ?>">
        
                    H: <input class="widefat" style="width:40%;" type="text" id="<?php echo $this->get_field_id("thumb_h"); ?>" name="<?php echo $this->get_field_name("thumb_h"); ?>" value="<?php echo $thumb_h; ?>" />
        
                </label>
        
            </label>
        
        </p>
        
        <?php endif; ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('show_type'); ?>"><?php _e('Show Post Type:');?> 
                <select class="widefat" id="<?php echo $this->get_field_id('show_type'); ?>" name="<?php echo $this->get_field_name('show_type'); ?>">
                <?php
                    global $wp_post_types;
                    foreach($wp_post_types as $k=>$sa) {
                        if($sa->exclude_from_search) continue;
                        echo '<option value="' . $k . '"' . selected($k,$show_type,true) . '>' . $sa->labels->name . '</option>';
                    }
                ?>
                </select>
            </label>
        </p>
		
		<p>
            <label for="<?php echo $this->get_field_id('show_page_id'); ?>"><?php _e('Show Page Type:');?> 
                <select class="widefat" id="<?php echo $this->get_field_id('show_page_id'); ?>" name="<?php echo $this->get_field_name('show_page_id'); ?>[]" style="height:auto;max-height:6em" multiple="multiple" size="4">
                <?php
                   
					if( $pages = get_pages() ){
						foreach($pages as $key => $option) {
							
							//$selected = in_array( $key, $show_page_id ) ? ' selected="selected" ' : '';
						
							echo '<option value="' . $key . '"' . selected($key,$show_page_id,true) . '>' . $option->post_title . '</option>';
						}
					}
					
                ?>
				
				
                </select>
            </label>
        </p>
		
<?php
	}
	
	
}




// register MT Recent Posts Widget With Thumbnails widget
function register_mtrecentpostswidgetw_widget() {
	register_widget( 'Mtrecentpostswidgetw_Widget' );
}
add_action( 'widgets_init', 'register_mtrecentpostswidgetw_widget' );
?>