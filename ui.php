<?php
/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

add_action('widgets_init', 'register_rsvpmakerbyJSON');

function register_rsvpmakerbyJSON () {
//register only if RSVPMaker is not active on this site
    register_widget("RSVPMakerByJSON_Standalone");
}

function enq_rsvpjson () {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( !is_plugin_active( 'rsvpmaker/rsvpmaker.php' ) )
        wp_enqueue_script('rsvpmaker_jsonjs',plugins_url('rsvpmaker-by-json-widget/rsvp-json.js'), array(), '1.0');
}
add_action('wp_enqueue_scripts','enq_rsvpjson');

class RSVPMakerByJSON_Standalone extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct('rsvpmaker_by_json', $name = 'RSVPMaker Events (API)');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		if(empty($title))
            $title = __('Events','rsvpmaker');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/','',$title));
		$url = ($instance["url"]) ? $instance["url"] : site_url('/wp-json/rsvpmaker/v1/future');
		$limit = ($instance["limit"]) ? $instance["limit"] : 0;
		$morelink = ($instance["morelink"]) ? $instance["morelink"] : '';
        global $rsvp_options;
		;?>
              <?php echo $before_widget;?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title;?>
<div id="rsvpjsonwidget-<?php echo $slug; ?>"><?php _e('Loading','rsvpmaker'); ?> ...</div>
<script>
var jsonwidget<?php echo $slug; ?> = new RSVPJsonWidget('rsvpjsonwidget-<?php echo $slug; ?>','<?php echo $url; ?>',<?php echo $limit; ?>,'<?php echo $morelink; ?>');
</script>
<?php		
  echo $after_widget;?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['url'] = trim($new_instance['url']);
	$instance['limit'] = $new_instance['limit'];
	$instance['morelink'] = trim($new_instance['morelink']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = (isset($instance['title'])) ? esc_attr($instance['title']) : '';
        if(function_exists('rsvpmaker_upcoming'))
    		$url = (isset($instance["url"])) ? $instance["url"] : site_url('/wp-json/rsvpmaker/v1/future');
        else
    		$url = (isset($instance["url"])) ? $instance["url"] : 'https://rsvpmaker.com/wp-json/rsvpmaker/v1/future';
		$limit = (isset($instance["limit"])) ? $instance["limit"] : 10;
		$morelink = (isset($instance["morelink"])) ? $instance["morelink"] : '';
        ?>
            <p><label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:','rsvpmaker');?> <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title;?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('url');?>"><?php _e('JSON URL:','rsvpmaker');?> <input class="widefat" id="<?php echo $this->get_field_id('url');?>" name="<?php echo $this->get_field_name('url');?>" type="text" value="<?php echo $url;?>" /></label>
            <br />Examples from rsvpmaker.com demo:
            <br /><a target="_blank" href="https://rsvpmaker.com/wp-json/rsvpmaker/v1/future">all future events</a>
            <br /><a target="_blank" href="https://rsvpmaker.com/wp-json/rsvpmaker/v1/type/featured">events tagged type/featured</a></p>
          <p><label for="<?php echo $this->get_field_id('limit');?>"><?php _e('Maximum # Displayed:','rsvpmaker');?> <input class="widefat" id="<?php echo $this->get_field_id('limit');?>" name="<?php echo $this->get_field_name('limit');?>" type="text" value="<?php echo $limit;?>" /></label><br /><em>Use 0 for no limit</em></p>
            <p><label for="<?php echo $this->get_field_id('morelink');?>"><?php _e('URL for more events:','rsvpmaker');?> <input class="widefat" id="<?php echo $this->get_field_id('morelink');?>" name="<?php echo $this->get_field_name('morelink');?>" type="text" value="<?php echo $morelink;?>" /></label></p>

        <?php 
    }
}