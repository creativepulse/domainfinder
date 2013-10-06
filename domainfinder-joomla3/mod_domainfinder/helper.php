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

// no direct access
defined('_JEXEC') or die('Restricted access');


if (!class_exists('Mod_DomainFinder')) {
    
    class Mod_DomainFinder {

        function instance_id($new = false) {
            static $num = 0;

            if ($new) {
                $num++;
            }

            return $num;
        }

        function json_esc($input, $esc_lt_gt = true) {
            $result = '';
            $input = (string) $input;
            $conv = array( "'" => "'", '"' => '"', '&' => '&', '\\' => '\\', "\n" => 'n', "\r" => 'r', "\t" => 't', "\b" => 'b', "\f" => 'f' );

            if ($esc_lt_gt) {
                $conv = array_merge($conv, array( '<' => 'u003C', '>' => 'u003E' ));
            }

            for ($i = 0, $len = strlen($input); $i < $len; $i++) {
                $result .= isset($conv[$input[$i]]) ? '\\' . $conv[$input[$i]] : $input[$i];
            }

            return $result;
        }

        function prepare(&$params) {
            $this->instance_id(true);

            $this->tlds = array();
            
            foreach (explode("\n", $params->get('tlds')) as $tld) {
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


            // prepare general javascript parameters
            $document = JFactory::getDocument();
            if ($this->instance_id(false) == 1) {
                require_once(dirname(__FILE__) . '/data.inc.php');
                $mhs = $data_mgr->start_session();

                $document->addScript('modules/mod_domainfinder/js/domainfinder.js');
                $document->addScript('modules/mod_domainfinder/js/domainfinder_loader.php');
                $document->addScriptDeclaration('document.mod_domainfinder_conf = {};');
                $document->addScriptDeclaration('document.mod_domainfinder_mhs = "' . $mhs . '";');
            }

        }
        
    }
    
}

?>