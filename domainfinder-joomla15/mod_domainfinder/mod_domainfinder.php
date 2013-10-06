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

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__) . '/helper.php');

$mod = new Mod_DomainFinder();
$mod->prepare($params);

$path = JModuleHelper::getLayoutPath('mod_domainfinder', $params->get('layout', 'default'));
if (file_exists($path)) {
    require($path);
}

?>