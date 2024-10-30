<?php
/*
Plugin Name: CatholicJukebox Radio plugin
Plugin URI: http://wordpress.org/extend/plugins
Description: Show the Recently Played or Top 5 Requested list from CatholicJukebox.com Radio
Author: George Leite
Version: 1.0
Author URI: http://www.catholicjukebox.com
License: GPL2

    Copyright 2012  George Leite  (email : leitefrog@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation. 
    
    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    The license for this software can likely be found here: 
    http://www.gnu.org/licenses/gpl-2.0.html
    
*/

class CatholicJukebox_Widget extends WP_Widget {

	function CatholicJukebox_Widget() {
		$widget_ops = array('classname' => 'widget_cjbw', 'description' => __('CatholicJukebox.com Radio lists'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('cjbw', __('CatholicJukebox Radio'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );

		echo $before_widget;
		if ( !empty( $title ) ) 
		{
			 echo $before_title . $title . $after_title;
		}
		if ($instance['recentlyPlayed'])
		{
		 
			$file = fopen ( "http://www.catholicjukebox.com/widgetdata.php", "r");
			if (!$file) 
			{
				echo "<p>Unable to open remote file.\n";
				exit;
			}

			?>			
				<div class="cjbwwidget">
			
				<?php  fpassthru($file); ?>
			
				</div>
			<?php
			fclose($file);
		}

		if (($instance['recentRequests']) && ($instance['recentRequests']))
		{
			echo "<br/>";
		}

		if ($instance['recentRequests'])
		{
		 
			$file = fopen ( "http://www.catholicjukebox.com/top5requests.html", "r");
			if (!$file) 
			{
				echo "<p>Unable to open remote file.\n";
				exit;
			}

			?>			
				<div class="cjbwwidget">
			
				<?php  fpassthru($file); ?>
			
				</div>
			<?php
			fclose($file);
		}
		echo $after_widget;
		
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['recentlyPlayed'] = isset($new_instance['recentlyPlayed']);
		$instance['recentRequests'] = isset($new_instance['recentRequests']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = strip_tags($instance['title']);

?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><input id="<?php echo $this->get_field_id('recentlyPlayed'); ?>" name="<?php echo $this->get_field_name('recentlyPlayed'); ?>" type="checkbox" <?php checked(isset($instance['recentlyPlayed']) ? $instance['recentlyPlayed'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('recentlyPlayed'); ?>"><?php _e('Show Recently Played'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('recentRequests'); ?>" name="<?php echo $this->get_field_name('recentRequests'); ?>" type="checkbox" <?php checked(isset($instance['recentRequests']) ? $instance['recentRequests'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('recentRequests'); ?>"><?php _e('Show Top 5 Reqests of the Week'); ?></label></p>
<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("CatholicJukebox_Widget");'));
