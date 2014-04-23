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


require_once(dirname(__FILE__) . '/../inc/data_manager.php');

echo 'document.cpwdg_domainfinder_nhc = "' . $data_manager->get_nhc() . '";';

?>