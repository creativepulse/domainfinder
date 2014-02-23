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


if (!class_exists('CpWidget_DomainFinder')) {

	class CpWidget_DomainFinder {

		public static function get_model() {
			static $instance = null;
			if ($instance === null) {
				$instance = new CpWidget_DomainFinder();
			}
			return $instance;
		}

		public function view_id($new = false) {
			static $num = 0;

			if ($new) {
				$num++;
			}

			return $num;
		}

		public function load_libraries($name) {
			static $libraries = array();
			$result = !isset($libraries[$name]);
			$libraries[$name] = true;
			return $result;
		}

		public function json_esc($input, $esc_html = true) {
			$result = '';
			if (!is_string($input)) {
				$input = (string) $input;
			}

			$conv = array("\x08" => '\\b', "\t" => '\\t', "\n" => '\\n', "\f" => '\\f', "\r" => '\\r', '"' => '\\"', "'" => "\\'", '\\' => '\\\\');
			if ($esc_html) {
				$conv['<'] = '\\u003C';
				$conv['>'] = '\\u003E';
			}

			for ($i = 0, $len = strlen($input); $i < $len; $i++) {
				if (isset($conv[$input[$i]])) {
					$result .= $conv[$input[$i]];
				}
				else if ($input[$i] < ' ') {
					$result .= sprintf('\\u%04x', ord($input[$i]));
				}
				else {
					$result .= $input[$i];
				}
			}

			return $result;
		}

		public function prepare($params) {
			$this->tlds = array();
			
			foreach (explode("\n", $params['tlds']) as $tld) {
				$tld = trim($tld);
				$enabled = true;
				if ($tld != '' && $tld[0] == '-') {
					$enabled = false;
					$tld = ltrim(substr($tld, 1));
				}

				$tld = preg_replace('/^([ .]*)|([ .]*)$/', '', $tld);
				if (preg_match('/^[a-z.]+$/i', $tld)) {
					$this->tlds[] = array('name' => $tld, 'enabled' => $enabled);
				}
			}

			$this->panel_offset_x = intval(trim($params['panel_offset_x']));
			$this->panel_offset_y = intval(trim($params['panel_offset_y']));

			if ($this->load_libraries('*')) {
				$dir = dirname(__FILE__);
				require_once($dir . '/data_manager.php');

				echo
'<script type="text/javascript" src="' . plugins_url('/js/domainfinder.js', $dir) . '"></script>
<script type="text/javascript" src="' . plugins_url('/js/domainfinder_loader.php', $dir) . '"></script>
<script type="text/javascript">
document.cpwdg_domainfinder_conf = {};
document.cpwdg_domainfinder_mhs = "' . $data_manager->start_session() . '";
</script>
';
			}
		}
	}

}


// process instance

$layout = (string) @$instance['layout'];
if ($layout == '') {
	$layout = 'default';
}

$widget = CpWidget_DomainFinder::get_model();
$widget->prepare($instance);

echo $args['before_widget'];
echo empty($instance['title']) ? '' : $args['before_title'] . $instance['title'] . $args['after_title'] . "\n";

$layout_filename = $dir . '/widget_layouts/' . $layout . '.php';
if (strpos($layout, "\0") !== false || strpos($layout, '..') !== false || strpos($layout, ':') !== false || strpos($layout, '/') !== false || strpos($layout, '\\') !== false) {
	echo __('Invalid layout value') . "\n";
}
else if (!file_exists($layout_filename)) {
	echo __('Layout script not found') . "\n";
}
else {
	require($layout_filename);
}

echo $args['after_widget'];

?>