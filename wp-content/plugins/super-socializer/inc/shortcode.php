<?php
defined('ABSPATH') or die("Cheating........Uh!!");
/** 
 * Shortcode for Social Sharing.
 */ 
function the_champ_sharing_shortcode($params){
	// notify if sharing is disabled
	if(the_champ_social_sharing_enabled()){
		extract(shortcode_atts(array(
			'style' => '',
			'type' => 'horizontal',
			'left' => '0',
			'top' => '100',
		), $params));
		if(($type == 'horizontal' && !the_champ_horizontal_sharing_enabled()) || ($type == 'vertical' && !the_champ_vertical_sharing_enabled())){
			return;
		}
		global $post;
		$targetUrl = get_permalink($post -> ID);
		$html = '<div class="the_champ_sharing_container the_champ_'.$type.'_sharing" super-socializer-data-href="'.$targetUrl.'" ';
		$verticalOffsets = '';
		if($type == 'vertical'){
			$verticalOffsets = 'left: '.$left.'px; top: '.$top.'px;';
		}
		// style 
		if($style != "" || $verticalOffsets != ''){
			$html .= 'style="';
			$html .= $verticalOffsets;
			$html .= $style;
			$html .= '"';
		}
		$html .= '>';
		$html .= the_champ_prepare_sharing_html($targetUrl, $type);
		$html .= '</div>';
		return $html;
	}
}
add_shortcode('TheChamp-Sharing', 'the_champ_sharing_shortcode');

/** 
 * Shortcode for Social Counter.
 */ 
function the_champ_counter_shortcode($params){
	// notify if counter is disabled
	if(the_champ_social_counter_enabled()){
		extract(shortcode_atts(array(
			'style' => '',
			'type' => 'horizontal',
			'left' => '0',
			'top' => '100',
		), $params));
		if(($type == 'horizontal' && !the_champ_horizontal_counter_enabled()) || ($type == 'vertical' && !the_champ_vertical_counter_enabled())){
			return;
		}
		global $post;
		$targetUrl = get_permalink($post -> ID);
		$html = '<div class="the_champ_counter_container the_champ_'.$type.'_counter" ';
		$verticalOffsets = '';
		if($type == 'vertical'){
			$verticalOffsets = 'left: '.$left.'px; top: '.$top.'px;';
		}
		// style 
		if($style != "" || $verticalOffsets != ''){
			$html .= 'style="';
			$html .= $verticalOffsets;
			$html .= $style;
			$html .= '"';
		}
		$html .= '>';
		global $theChampCounterOptions;
		$counterUrl = $targetUrl;
		// if bit.ly integration enabled, generate bit.ly short url
		if(isset($theChampCounterOptions['bitly_enable']) && isset($theChampCounterOptions['bitly_username']) && isset($theChampCounterOptions['bitly_username']) && $theChampCounterOptions['bitly_username'] != '' && isset($theChampCounterOptions['bitly_key']) && $theChampCounterOptions['bitly_key'] != ''){
			$shortUrl = the_champ_generate_counter_bitly_url($targetUrl);
			if($shortUrl){
				$counterUrl = $shortUrl;
			}
		}
		$html .= the_champ_prepare_counter_html($targetUrl, $type, $counterUrl);
		$html .= '</div>';
		return $html;
	}
}
add_shortcode('TheChamp-Counter', 'the_champ_counter_shortcode');

/** 
 * Shortcode for Social Login.
 */ 
function the_champ_login_shortcode($params){
	if(the_champ_social_login_enabled()){
		extract(shortcode_atts(array(
			'style' => ''
		), $params));
		$html = '<div ';
		// style 
		if($style != ""){
			if(strpos($style, 'float') === false){
				$style = 'float: left;' . $style;
			}
			$html .= 'style="'.$style.'"';
		}
		$html .= '>';
		$html .= the_champ_login_button(true);
		$html .= '</div><div style="clear:both"></div>';
		return $html;
	}
}
add_shortcode('TheChamp-Login', 'the_champ_login_shortcode');