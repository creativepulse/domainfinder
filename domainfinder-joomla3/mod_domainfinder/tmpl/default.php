<?php

/**
 * domain finder
 *
 * @version 1.1
 * @author Creative Pulse
 * @copyright Creative Pulse 2011-2013
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


global $tpl;
$iname = 'mod_domainfinder_' . $mod->instance_id();

$tlds = array();
foreach ($mod->tlds as $tld) {
    $tlds[] = '"' . $mod->json_esc($tld['name']) . '"';
}

$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_domainfinder/css/default.css');
$document->addScriptDeclaration(
'document.mod_domainfinder_conf["' . $iname . '"] = {
    lang_str: {
        domain_searching: "' . JText::_('DOMAINFINDER_SEARCHING') . '",
        domain_available: "' . JText::_('DOMAINFINDER_AVAILABLE') . '",
        domain_unavailable: "' . JText::_('DOMAINFINDER_UNAVAILABLE') . '",
        domain_error: "' . JText::_('DOMAINFINDER_ERROR') . '",
        more_info: "' . JText::_('DOMAINFINDER_MORE_INFO') . '",
        no_tlds: "' . JText::_('DOMAINFINDER_NO_TLDS') . '",
        invalid_domain: "' . JText::_('DOMAINFINDER_INVALID_DOMAIN') . '",
        domain_too_short: "' . JText::_('DOMAINFINDER_DOMAIN_TOO_SHORT') . '",
        domain_too_long: "' . JText::_('DOMAINFINDER_DOMAIN_TOO_LONG') . '",
        invalid_tld: "' . JText::_('DOMAINFINDER_INVALID_TLD') . '",
        information_na: "' . JText::_('DOMAINFINDER_INFORMATION_NA') . '"
    },
    tlds: [' . implode(', ', $tlds) . '],
    call_url: "' . $mod->json_esc(JUri::root()) . '",
    cb_open: "BtDomainFinder_h_open",
    cb_create_listing: "BtDomainFinder_h_create_listing"
};
');

echo '
<div id="' . $iname . '" class="mod_domainfinder">
    <div id="' . $iname . '_input_panel" class="domainfinder_input_panel">
        <div class="domainfinder_text_prefix">www.</div>
        <input type="text" id="' . $iname . '_text" name="' . $iname . '_text" placeholder="' . JText::_('example.com') . '" />
        <input type="button" class="button" id="' . $iname . '_button" value="' . JText::_('DOMAINFINDER_SEARCH_DOMAIN') . '" />
        <div class="domainfinder_tlds">
';

foreach ($mod->tlds as $tld) {
    $tld_id = $iname . '_tld_' . $tld['name'];
    echo
'            <input type="checkbox" name="' . $tld_id . '" id="' . $tld_id . '"' . ($tld['enabled'] ? ' checked="checked"' : '') . '> <label for="' . $tld_id . '">' . $tld['name'] . '</label>
';
}

echo
'
        </div>
    </div>
    
    <div id="' . $iname . '_result_panel" class="domainfinder_result_panel" style="display:none">
        <button id="' . $iname . '_close_button" class="domainfinder_results_close_button">' . JText::_('Close') . '</button>
        <div class="domainfinder_results_title">' . JText::_('Search results') . '</div>

        <div class="domainfinder_results_container">
            <table>
                <tbody id="' . $iname . '_result_items">
                    <tr><td>&nbsp;</td></tr>



                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>
                    <tr>
                        <td><div class="domainfinder_icon_searching"></div></td>
                        <td><div class="domainfinder_status_searching">Searching ...</div></td>
                        <td><div class="domainfinder_domain">abc.com</div></td>
                        <td><div class="domainfinder_icon_remove">x</div></td>
                        <td><div class="domainfinder_icon_moreinfo">More info</div></td>
                    </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>
';

?>