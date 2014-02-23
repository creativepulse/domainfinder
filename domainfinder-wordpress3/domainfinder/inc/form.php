<?php

/**
 * Domain Finder
 *
 * @version 1.2
 * @author Creative Pulse
 * @copyright Creative Pulse 2011-2014
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


$instance = wp_parse_args((array) $instance, array('title' => '', 'tlds' => '', 'layout' => '', 'panel_offset_x' => '', 'panel_offset_y' => ''));

$default_name = '';
$layouts = array();
$path = $dir . '/widget_layouts';
if ($dp = @opendir($path)) {
	while (false !== ($file = readdir($dp))) {
		if (preg_match('/^(.*)\.php$/', $file, $m)) {
			if (strtolower($m[1]) == 'default') {
				$default_name = $m[1];
			}
			else {
				$layouts[] = $m[1];
			}
		}
	}
	closedir($dp);

	sort($layouts);
}
else {
	echo __('Error: Unable to open widget layouts directory');
	return;
}

if ($default_name) {
	array_unshift($layouts, $default_name);
	if (empty($instance['layout'])) {
		$instance['layout'] = $default_name;
	}
}

echo
'<p><label for="' . $this->get_field_id('title') . '">' . __('Title') . ':</label>
	<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . attribute_escape($instance['title']) . '" />
</p>

<p><label for="' . $this->get_field_id('tlds') . '">' . __('TLDs') . ':</label>
	<textarea class="widefat" id="' . $this->get_field_id('tlds') . '" name="' . $this->get_field_name('tlds') . '" rows="5">' . esc_textarea($instance['tlds']) . '</textarea>
</p>

<p><label for="' . $this->get_field_id('layout') . '">' . __('Layout') . ':</label>
	<select class="widefat" id="' . $this->get_field_id('layout') . '" name="' . $this->get_field_name('layout') . '">
';

foreach ($layouts as $layout) {
	echo
'		<option value="' . htmlspecialchars($layout) . '"' . ($instance['layout'] == $layout ? ' selected="selected"' : '') . '>' . htmlspecialchars($layout) . '</option>
';
}

echo
'	</select>
</p>
	
<p>' . __('Panel offset') . ':
	&nbsp; &nbsp;
	<label for="' . $this->get_field_id('panel_offset_x') . '">' . __('X') . '</label>
	<input id="' . $this->get_field_id('panel_offset_x') . '" name="' . $this->get_field_name('panel_offset_x') . '" type="text" value="' . attribute_escape($instance['panel_offset_x']) . '" size="5" />
	&nbsp; &nbsp;
	<label for="' . $this->get_field_id('panel_offset_y') . '">' . __('Y') . '</label>
	<input id="' . $this->get_field_id('panel_offset_y') . '" name="' . $this->get_field_name('panel_offset_y') . '" type="text" value="' . attribute_escape($instance['panel_offset_y']) . '" size="5" />
</p>
';

?>