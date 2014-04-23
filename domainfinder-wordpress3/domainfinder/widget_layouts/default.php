<?php

/**
 * Domain Finder
 *
 * @version 1.3
 * @author Creative Pulse
 * @copyright Creative Pulse 2011-2014
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


$iname = 'cpwdg_domainfinder_' . $widget->view_id(true);

$tlds = array();
foreach ($widget->tlds as $tld) {
	$tlds[] = '"' . $widget->json_esc($tld['name']) . '"';
}

if ($widget->load_libraries(basename(__FILE__))) {
	echo
'<link rel="stylesheet" href="' . plugins_url('/widget_layouts/default.css', dirname(__FILE__)) . '">
';	
}

echo
'<script type="text/javascript">
document.cpwdg_domainfinder_conf["' . $iname . '"] = {
	lang_str: {
		domain_searching: "' . __('Searching ...') . '",
		domain_available: "' . __('Available') . '",
		domain_unavailable: "' . __('Not available') . '",
		domain_error: "' . __('Error') . '",
		more_info: "' . __('More info') . '",
		no_tlds: "' . __('Please select one ore more domain extensions and retry') . '",
		invalid_domain: "' . __('The domain name is not valid. Enter letters, numbers and dashes only.') . '",
		domain_too_short: "' . __('The domain name must be 3 characters or longer') . '",
		domain_too_long: "' . __('The domain name cannot be more than 64 characters') . '",
		invalid_tld: "' . __('The domain extension is not valid') . '",
		information_na: "' . __('Information not available. Please wait until the search status is complete and retry.') . '"
	},
	tlds: [' . implode(', ', $tlds) . '],
	panel_offset_x: ' . $widget->panel_offset_x . ',
	panel_offset_y: ' . $widget->panel_offset_y . ',
	call_url: "' . $widget->json_esc(plugins_url('', dirname(__FILE__))) . '",
	cb_open: "",
	cb_create_listing: ""
};
</script>
';

echo '
<div id="' . $iname . '" class="widget cpwdg_domainfinder">
	<div id="' . $iname . '_input_panel" class="cpwdg_domainfinder_input_panel">
		<div class="cpwdg_domainfinder_text_prefix">www.</div>
		<input type="text" id="' . $iname . '_text" name="' . $iname . '_text" placeholder="' . __('example.com') . '" />
		<input type="button" class="button" id="' . $iname . '_button" value="' . __('Search domain') . '" />
		<div class="cpwdg_domainfinder_tlds">
';

foreach ($widget->tlds as $tld) {
	$tld_id = $iname . '_tld_' . $tld['name'];
	echo
'			<input type="checkbox" name="' . $tld_id . '" id="' . $tld_id . '"' . ($tld['enabled'] ? ' checked="checked"' : '') . '> <label for="' . $tld_id . '">' . $tld['name'] . '</label>
';
}

echo
'
		</div>
	</div>
	
	<div id="' . $iname . '_result_panel" class="cpwdg_domainfinder_result_panel" style="display:none;' . ($widget->z_index == 0 ? '' : 'z-index:' . $widget->z_index . ';') . '">
		<button id="' . $iname . '_close_button" class="cpwdg_domainfinder_results_close_button">' . __('close') . '</button>
		<div class="cpwdg_domainfinder_results_title">' . __('Search results') . '</div>

		<div class="cpwdg_domainfinder_results_container">
			<table>
				<tbody id="' . $iname . '_result_items">
					<tr>
						<td><div class="cpwdg_domainfinder_icon_searching"></div></td>
						<td><div class="cpwdg_domainfinder_status_searching">' . __('Searching ...') . '</div></td>
						<td><div class="cpwdg_domainfinder_domain">example.com</div></td>
						<td><div class="cpwdg_domainfinder_icon_remove">x</div></td>
						<td><div class="cpwdg_domainfinder_icon_moreinfo">' . __('More info') . '</div></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
';

?>