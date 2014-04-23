<?php

/*
Plugin Name: Domain Finder
Plugin URI: http://www.creativepulse.gr/en/appstore/domainfinder
Version: 1.3
Description: Domain Finder searches for domain availability
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Author: Creative Pulse
Author URI: http://www.creativepulse.gr
*/


class CpExt_DomainFinder extends WP_Widget {

	function __construct() {
		$options = array('classname' => 'CpExt_DomainFinder', 'description' => 'Domain Finder searches for domain availability');
		$this->WP_Widget('CpExt_DomainFinder', 'DomainFinder', $options);
	}

	function register_widget() {
		register_widget(get_class($this));
	}

	function widget($args, $instance) {
		$dir = dirname(__FILE__);
		require($dir . '/inc/widget.php');
	}

	function update($new_instance, $old_instance) {
		$fields = array('title', 'tlds', 'layout', 'panel_offset_x', 'panel_offset_y', 'z_index');
		foreach ($fields as $field) {
			$old_instance[$field] = $new_instance[$field];
		}
		return $old_instance;
	}

	function form($instance) {
		$dir = dirname(__FILE__);
		require($dir . '/inc/form.php');
	}

}

add_action('widgets_init', array('CpExt_DomainFinder', 'register_widget'));

?>