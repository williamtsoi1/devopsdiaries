<?php 
defined('ABSPATH') or die("Cheating........Uh!!");
/**
 * Widget for Social Login
 */
class TheChampLoginWidget extends WP_Widget { 
	/** constructor */ 
	function TheChampLoginWidget() { 
		parent::WP_Widget( 
			'TheChampLogin', //unique id 
			__('Super Socializer - Login'), //title displayed at admin panel
			array(  
				'description' => __( 'Let your website users login/register using their favorite Social ID Provider, such as Facebook, Twitter, Google+, LinkedIn', 'Super-Socializer' )) 
			); 
	}
	
	/** This is rendered widget content */ 
	function widget( $args, $instance ) {
		// if social login is disabled, return
		if(!the_champ_social_login_enabled()){
			return;
		}
		extract( $args ); 
		if($instance['hide_for_logged_in']==1 && is_user_logged_in()) return;
		echo $before_widget;
		if( !empty( $instance['title'] ) && !is_user_logged_in() ){ 
			$title = apply_filters( 'widget_title', $instance[ 'title' ] ); 
			echo $before_title . $title . $after_title;
		}
		if( !empty( $instance['before_widget_content'] ) ){ 
			echo '<div>' . $instance['before_widget_content'] . '</div>';
		}
		if(!is_user_logged_in()){
			echo the_champ_login_button(true);
		}else{
			global $theChampLoginOptions, $user_ID;
			$userInfo = get_userdata($user_ID);
			echo "<div style='height:80px;width:180px'><div style='width:63px;float:left;'>";
			if(($userAvatar = get_user_meta($user_ID, 'thechamp_avatar', true)) !== false && strlen(trim($userAvatar)) > 0){
				echo '<img alt="Social Avatar" src="'.$userAvatar.'" height = "60" width = "60" title="'.$userInfo->user_login.'" style="border:2px solid #e7e7e7;"/>';
			}else{
				echo @get_avatar($user_ID, 60, $default, $alt);   
			}
			echo "</div><div style='float:left; margin-left:10px'>";
			echo str_replace('-', ' ', $userInfo -> user_login);
			echo '<br/><a href="' . wp_logout_url(home_url()) . '">' .__('Log Out', 'LoginRadius') . '</a></div></div>';
		}
		echo '<div style="clear:both"></div>';
		if( !empty( $instance['after_widget_content'] ) ){ 
			echo '<div>' . $instance['after_widget_content'] . '</div>';
		}
		echo $after_widget; 
	}  

	/** Everything which should happen when user edit widget at admin panel */ 
	function update( $new_instance, $old_instance ) { 
		$instance = $old_instance; 
		$instance['title'] = strip_tags( $new_instance['title'] ); 
		$instance['before_widget_content'] = $new_instance['before_widget_content']; 
		$instance['after_widget_content'] = $new_instance['after_widget_content']; 
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];  

		return $instance; 
	}  

	/** Widget options in admin panel */ 
	function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array( 'title' => 'Login with your Social Account', 'before_widget_content' => '', 'after_widget_content' => '' );  

		foreach( $instance as $key => $value )  
			$instance[ $key ] = esc_attr( $value );  

		$instance = wp_parse_args( (array)$instance, $defaults ); 
		?> 
		<p> 
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Before widget content:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'After widget content:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" /> 
			<br /><br />
			<label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'Super-Socializer' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if(isset($instance['hide_for_logged_in']) && $instance['hide_for_logged_in']==1) echo 'checked="checked"'; ?> /> 
		</p> 
<?php 
  } 
} 
add_action( 'widgets_init', create_function( '', 'return register_widget( "TheChampLoginWidget" );' )); 

/**
 * Widget for Social Sharing (Horizontal widget)
 */
class TheChampSharingWidget extends WP_Widget { 
	/** constructor */ 
	function TheChampSharingWidget() { 
		parent::WP_Widget( 
			'TheChampHorizontalSharing', //unique id 
			'Super Socializer - Sharing (Horizontal Widget)', //title displayed at admin panel 
			//Additional parameters 
			array(
				'description' => __( 'Horizontal sharing widget. Let your website users share content on popular Social networks like Facebook, Twitter, Tumblr, Google+ and many more', 'Super-Socializer' )) 
			); 
	}  

	/** This is rendered widget content */ 
	function widget( $args, $instance ) { 
		// return if sharing is disabled
		if(!the_champ_social_sharing_enabled() || !the_champ_horizontal_sharing_enabled()){
			return;
		}
		extract( $args );
		if($instance['hide_for_logged_in']==1 && is_user_logged_in()) return;
		
		echo "<div class='the_champ_sharing_container the_champ_horizontal_sharing' super-socializer-data-href='".site_url()."'>";
		
		echo $before_widget;
		
		if( !empty( $instance['title'] ) ){ 
			$title = apply_filters( 'widget_title', $instance[ 'title' ] ); 
			echo $before_title . $title . $after_title;
		}

		if( !empty( $instance['before_widget_content'] ) ){ 
			echo '<div>' . $instance['before_widget_content'] . '</div>'; 
		}
		global $theChampSharingOptions;
		$sharingUrl = site_url();
		// if bit.ly integration enabled, generate bit.ly short url
		if(isset($theChampSharingOptions['bitly_enable']) && isset($theChampSharingOptions['bitly_username']) && isset($theChampSharingOptions['bitly_username']) && $theChampSharingOptions['bitly_username'] != '' && isset($theChampSharingOptions['bitly_key']) && $theChampSharingOptions['bitly_key'] != ''){
			$shortUrl = the_champ_generate_sharing_bitly_url(site_url());
			if($shortUrl){
				$sharingUrl = $shortUrl;
			}
		}
		echo the_champ_prepare_sharing_html($sharingUrl);

		if( !empty( $instance['after_widget_content'] ) ){ 
			echo '<div>' . $instance['after_widget_content'] . '</div>'; 
		}
		
		echo "</div>";
		echo $after_widget;
	}  

	/** Everything which should happen when user edit widget at admin panel */ 
	function update( $new_instance, $old_instance ) { 
		$instance = $old_instance; 
		$instance['title'] = strip_tags( $new_instance['title'] ); 
		$instance['before_widget_content'] = $new_instance['before_widget_content']; 
		$instance['after_widget_content'] = $new_instance['after_widget_content']; 
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];  

		return $instance; 
	}  

	/** Widget edit form at admin panel */ 
	function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array( 'title' => 'Share the joy', 'before_widget_content' => '', 'after_widget_content' => '' );

		foreach( $instance as $key => $value ){
			$instance[ $key ] = esc_attr( $value );
		}
		
		$instance = wp_parse_args( (array)$instance, $defaults ); 
		?> 
		<p> 
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Before widget content:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'After widget content:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" /> 
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'Super-Socializer' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if(isset($instance['hide_for_logged_in'])  && $instance['hide_for_logged_in']==1) echo 'checked="checked"'; ?> /> 
		</p> 
	<?php 
    } 
} 
add_action( 'widgets_init', create_function( '', 'return register_widget( "TheChampSharingWidget" );' ));

/**
 * Widget for Social Sharing (Vertical widget)
 */
class TheChampVerticalSharingWidget extends WP_Widget { 
	/** constructor */ 
	function TheChampVerticalSharingWidget() { 
		parent::WP_Widget( 
			'TheChampVerticalSharing', //unique id 
			'Super Socializer - Sharing (Vertical Floating Widget)', //title displayed at admin panel 
			//Additional parameters 
			array(
				'description' => __( 'Vertical floating sharing widget. Let your website users share content on popular Social networks like Facebook, Twitter, Tumblr, Google+ and many more', 'Super-Socializer' )) 
			); 
	}  

	/** This is rendered widget content */ 
	function widget( $args, $instance ) { 
		// return if sharing is disabled
		if(!the_champ_social_sharing_enabled() || !the_champ_vertical_sharing_enabled()){
			return;
		}
		extract( $args );
		if($instance['hide_for_logged_in']==1 && is_user_logged_in()) return;
		
		echo "<div class='the_champ_sharing_container the_champ_vertical_sharing' style='".(isset($instance['alignment']) && $instance['alignment'] != '' && isset($instance[$instance['alignment'].'_offset']) ? $instance['alignment'].': '. ( $instance[$instance['alignment'].'_offset'] == '' ? 0 : $instance[$instance['alignment'].'_offset'] ) .'px;' : '').(isset($instance['top_offset']) ? 'top: '. ( $instance['top_offset'] == '' ? 0 : $instance['top_offset'] ) .'px;' : '') . (isset($instance['vertical_bg']) && $instance['vertical_bg'] != '' ? 'background-color: '.$instance['vertical_bg'] . ';' : '-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;') . "' super-socializer-data-href='".site_url()."'>";
		
		global $theChampSharingOptions;
		$sharingUrl = site_url();
		// if bit.ly integration enabled, generate bit.ly short url
		if(isset($theChampSharingOptions['bitly_enable']) && isset($theChampSharingOptions['bitly_username']) && isset($theChampSharingOptions['bitly_username']) && $theChampSharingOptions['bitly_username'] != '' && isset($theChampSharingOptions['bitly_key']) && $theChampSharingOptions['bitly_key'] != ''){
			$shortUrl = the_champ_generate_sharing_bitly_url(site_url());
			if($shortUrl){
				$sharingUrl = $shortUrl;
			}
		}
		//echo $before_widget;
		echo the_champ_prepare_sharing_html($sharingUrl, 'vertical');
		echo "</div>";
		//echo $after_widget;
	}  

	/** Everything which should happen when user edit widget at admin panel */ 
	function update( $new_instance, $old_instance ) { 
		$instance = $old_instance; 
		$instance['alignment'] = $new_instance['alignment'];
		$instance['left_offset'] = $new_instance['left_offset'];
		$instance['right_offset'] = $new_instance['right_offset'];
		$instance['top_offset'] = $new_instance['top_offset'];
		$instance['vertical_bg'] = $new_instance['vertical_bg'];
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];  

		return $instance; 
	}  

	/** Widget edit form at admin panel */ 
	function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array('alignment' => 'left', 'left_offset' => '40', 'right_offset' => '0', 'top_offset' => '100', 'vertical_bg' => '');

		foreach( $instance as $key => $value ){
			$instance[ $key ] = esc_attr( $value );
		}
		
		$instance = wp_parse_args( (array)$instance, $defaults ); 
		?> 
		<p> 
			<script>
			function theChampToggleSharingOffset(alignment){
				if(alignment == 'left'){
					jQuery('.theChampSharingLeftOffset').css('display', 'block');
					jQuery('.theChampSharingRightOffset').css('display', 'none');
				}else{
					jQuery('.theChampSharingLeftOffset').css('display', 'none');
					jQuery('.theChampSharingRightOffset').css('display', 'block');
				}
			}
			</script>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment', 'Super-Socializer' ); ?></label> 
			<select onchange="theChampToggleSharingOffset(this.value)" style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="left" <?php echo $instance['alignment'] == 'left' ? 'selected' : ''; ?>><?php _e( 'Left', 'Super-Socializer' ) ?></option>
				<option value="right" <?php echo $instance['alignment'] == 'right' ? 'selected' : ''; ?>><?php _e( 'Right', 'Super-Socializer' ) ?></option>
			</select>
			<div class="theChampSharingLeftOffset" <?php echo $instance['alignment'] == 'right' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'left_offset' ); ?>"><?php _e( 'Left Offset', 'Super-Socializer' ); ?></label> 
				<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'left_offset' ); ?>" name="<?php echo $this->get_field_name( 'left_offset' ); ?>" type="text" value="<?php echo $instance['left_offset']; ?>" />px<br/>
			</div>
			<div class="theChampSharingRightOffset" <?php echo $instance['alignment'] == 'left' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'right_offset' ); ?>"><?php _e( 'Right Offset', 'Super-Socializer' ); ?></label> 
				<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'right_offset' ); ?>" name="<?php echo $this->get_field_name( 'right_offset' ); ?>" type="text" value="<?php echo $instance['right_offset']; ?>" />px<br/>
			</div>
			<label for="<?php echo $this->get_field_id( 'top_offset' ); ?>"><?php _e( 'Top Offset', 'Super-Socializer' ); ?></label> 
			<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'top_offset' ); ?>" name="<?php echo $this->get_field_name( 'top_offset' ); ?>" type="text" value="<?php echo $instance['top_offset']; ?>" />px
			
			<label for="<?php echo $this->get_field_id( 'vertical_bg' ); ?>"><?php _e( 'Background Color', 'Super-Socializer' ); ?></label> 
			<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'vertical_bg' ); ?>" name="<?php echo $this->get_field_name( 'vertical_bg' ); ?>" type="text" value="<?php echo $instance['vertical_bg']; ?>" />
			
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'Super-Socializer' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if(isset($instance['hide_for_logged_in'])  && $instance['hide_for_logged_in']==1) echo 'checked="checked"'; ?> /> 
		</p> 
	<?php 
    } 
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "TheChampVerticalSharingWidget" );' ));

/**
 * Widget for Social Counter (Horizontal widget)
 */
class TheChampCounterWidget extends WP_Widget { 
	/** constructor */ 
	function TheChampCounterWidget() { 
		parent::WP_Widget( 
			'TheChampHorizontalCounter', //unique id 
			'Super Socializer - Counter (Horizontal Widget)', //title displayed at admin panel 
			//Additional parameters 
			array(
				'description' => __( 'Horizontal counter widget. Let your website users share/like content on popular Social networks like Facebook, Twitter, Google+ and many more', 'Super-Socializer' )
			)
		); 
	}  

	/** This is rendered widget content */ 
	function widget( $args, $instance ) { 
		// return if sharing is disabled
		if(!the_champ_social_counter_enabled() || !the_champ_horizontal_counter_enabled()){
			return;
		}
		extract( $args );
		if($instance['hide_for_logged_in']==1 && is_user_logged_in()) return;
		
		echo "<div class='the_champ_counter_container the_champ_horizontal_counter'>";
		
		echo $before_widget;
		
		if( !empty( $instance['title'] ) ){ 
			$title = apply_filters( 'widget_title', $instance[ 'title' ] ); 
			echo $before_title . $title . $after_title;
		}

		if( !empty( $instance['before_widget_content'] ) ){ 
			echo '<div>' . $instance['before_widget_content'] . '</div>'; 
		}
		$counterUrl = site_url();
		// if bit.ly integration enabled, generate bit.ly short url
		global $theChampCounterOptions;
		if(isset($theChampCounterOptions['bitly_enable']) && isset($theChampCounterOptions['bitly_username']) && isset($theChampCounterOptions['bitly_username']) && $theChampCounterOptions['bitly_username'] != '' && isset($theChampCounterOptions['bitly_key']) && $theChampCounterOptions['bitly_key'] != ''){
			$shortUrl = the_champ_generate_counter_bitly_url(site_url());
			if($shortUrl){
				$counterUrl = $shortUrl;
			}
		}
		echo the_champ_prepare_counter_html(site_url(), 'horizontal', $counterUrl);

		if( !empty( $instance['after_widget_content'] ) ){ 
			echo '<div>' . $instance['after_widget_content'] . '</div>'; 
		}
		
		echo "</div>";
		echo $after_widget;
	}  

	/** Everything which should happen when user edit widget at admin panel */ 
	function update( $new_instance, $old_instance ) { 
		$instance = $old_instance; 
		$instance['title'] = strip_tags( $new_instance['title'] ); 
		$instance['before_widget_content'] = $new_instance['before_widget_content']; 
		$instance['after_widget_content'] = $new_instance['after_widget_content']; 
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];  

		return $instance; 
	}  

	/** Widget edit form at admin panel */ 
	function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array( 'title' => 'Share the joy', 'before_widget_content' => '', 'after_widget_content' => '' );

		foreach( $instance as $key => $value ){
			$instance[ $key ] = esc_attr( $value );
		}
		
		$instance = wp_parse_args( (array)$instance, $defaults ); 
		?> 
		<p> 
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Before widget content:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" /> 
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'After widget content:', 'Super-Socializer' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" /> 
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'Super-Socializer' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if(isset($instance['hide_for_logged_in'])  && $instance['hide_for_logged_in']==1) echo 'checked="checked"'; ?> /> 
		</p> 
	<?php 
    } 
} 
add_action( 'widgets_init', create_function( '', 'return register_widget( "TheChampCounterWidget" );' ));

/**
 * Widget for Social Counter (Vertical widget)
 */
class TheChampVerticalCounterWidget extends WP_Widget { 
	/** constructor */ 
	function TheChampVerticalCounterWidget() { 
		parent::WP_Widget( 
			'TheChampVerticalCounter', //unique id 
			'Super Socializer - Counter (Vertical Floating Widget)', //title displayed at admin panel 
			//Additional parameters 
			array(
				'description' => __( 'Vertical floating counter widget. Let your website users share/like content on popular Social networks like Facebook, Twitter, Google+ and many more', 'Super-Socializer' )) 
			); 
	}  

	/** This is rendered widget content */ 
	function widget( $args, $instance ) { 
		// return if counter is disabled
		if(!the_champ_social_counter_enabled() || !the_champ_vertical_counter_enabled()){
			return;
		}
		extract( $args );
		if($instance['hide_for_logged_in']==1 && is_user_logged_in()) return;
		
		echo "<div class='the_champ_counter_container the_champ_vertical_counter' style='".(isset($instance['alignment']) && $instance['alignment'] != '' && isset($instance[$instance['alignment'].'_offset']) ? $instance['alignment'].': '. ( $instance[$instance['alignment'].'_offset'] == '' ? 0 : $instance[$instance['alignment'].'_offset'] ) .'px;' : '').(isset($instance['top_offset']) ? 'top: '. ( $instance['top_offset'] == '' ? 0 : $instance['top_offset'] ) .'px;' : '') . (isset($instance['vertical_bg']) && $instance['vertical_bg'] != '' ? 'background-color: '.$instance['vertical_bg'] . ';' : '-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;') . "' super-socializer-data-href='".site_url()."'>";
		$counterUrl = site_url();
		// if bit.ly integration enabled, generate bit.ly short url
		global $theChampCounterOptions;
		if(isset($theChampCounterOptions['bitly_enable']) && isset($theChampCounterOptions['bitly_username']) && isset($theChampCounterOptions['bitly_username']) && $theChampCounterOptions['bitly_username'] != '' && isset($theChampCounterOptions['bitly_key']) && $theChampCounterOptions['bitly_key'] != ''){
			$shortUrl = the_champ_generate_counter_bitly_url(site_url());
			if($shortUrl){
				$counterUrl = $shortUrl;
			}
		}
		//echo $before_widget;
		echo the_champ_prepare_counter_html(site_url(), 'vertical', $counterUrl);
		echo "</div>";
		//echo $after_widget;
	}  

	/** Everything which should happen when user edit widget at admin panel */ 
	function update( $new_instance, $old_instance ) { 
		$instance = $old_instance; 
		$instance['alignment'] = $new_instance['alignment'];
		$instance['left_offset'] = $new_instance['left_offset'];
		$instance['right_offset'] = $new_instance['right_offset'];
		$instance['top_offset'] = $new_instance['top_offset'];
		$instance['vertical_bg'] = $new_instance['vertical_bg'];
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];

		return $instance; 
	}  

	/** Widget edit form at admin panel */ 
	function form( $instance ) { 
		/* Set up default widget settings. */ 
		$defaults = array('alignment' => 'left', 'left_offset' => '40', 'right_offset' => '0', 'top_offset' => '100', 'vertical_bg' => '');

		foreach( $instance as $key => $value ){
			$instance[ $key ] = esc_attr( $value );
		}
		
		$instance = wp_parse_args( (array)$instance, $defaults ); 
		?> 
		<p> 
			<script>
			function theChampToggleCounterOffset(alignment){
				if(alignment == 'left'){
					jQuery('.theChampCounterLeftOffset').css('display', 'block');
					jQuery('.theChampCounterRightOffset').css('display', 'none');
				}else{
					jQuery('.theChampCounterLeftOffset').css('display', 'none');
					jQuery('.theChampCounterRightOffset').css('display', 'block');
				}
			}
			</script>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment', 'Super-Socializer' ); ?></label> 
			<select onchange="theChampToggleCounterOffset(this.value)" style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
				<option value="left" <?php echo $instance['alignment'] == 'left' ? 'selected' : ''; ?>><?php _e( 'Left', 'Super-Socializer' ) ?></option>
				<option value="right" <?php echo $instance['alignment'] == 'right' ? 'selected' : ''; ?>><?php _e( 'Right', 'Super-Socializer' ) ?></option>
			</select>
			<div class="theChampCounterLeftOffset" <?php echo $instance['alignment'] == 'right' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'left_offset' ); ?>"><?php _e( 'Left Offset', 'Super-Socializer' ); ?></label> 
				<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'left_offset' ); ?>" name="<?php echo $this->get_field_name( 'left_offset' ); ?>" type="text" value="<?php echo $instance['left_offset']; ?>" />px<br/>
			</div>
			<div class="theChampCounterRightOffset" <?php echo $instance['alignment'] == 'left' ? 'style="display: none"' : ''; ?>>
				<label for="<?php echo $this->get_field_id( 'right_offset' ); ?>"><?php _e( 'Right Offset', 'Super-Socializer' ); ?></label> 
				<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'right_offset' ); ?>" name="<?php echo $this->get_field_name( 'right_offset' ); ?>" type="text" value="<?php echo $instance['right_offset']; ?>" />px<br/>
			</div>
			<label for="<?php echo $this->get_field_id( 'top_offset' ); ?>"><?php _e( 'Top Offset', 'Super-Socializer' ); ?></label> 
			<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'top_offset' ); ?>" name="<?php echo $this->get_field_name( 'top_offset' ); ?>" type="text" value="<?php echo $instance['top_offset']; ?>" />px
			
			<label for="<?php echo $this->get_field_id( 'vertical_bg' ); ?>"><?php _e( 'Background Color', 'Super-Socializer' ); ?></label> 
			<input style="width: 200px" class="widefat" id="<?php echo $this->get_field_id( 'vertical_bg' ); ?>" name="<?php echo $this->get_field_name( 'vertical_bg' ); ?>" type="text" value="<?php echo $instance['vertical_bg']; ?>" />
			
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'Super-Socializer' ); ?></label> 
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value="1" <?php if(isset($instance['hide_for_logged_in'])  && $instance['hide_for_logged_in']==1) echo 'checked="checked"'; ?> />
		</p> 
	<?php 
    } 
} 
add_action( 'widgets_init', create_function( '', 'return register_widget( "TheChampVerticalCounterWidget" );' ));