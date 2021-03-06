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


class Mod_DomainFinder_DataMgr {
    
    function init($cleanup) {
        $timeout = 900; // 15 min x 60 sec
        $this->filename = dirname(__FILE__) . '/data.php';
        $this->opening =
'<' . "?php

// no direct access
die('Restricted access');

?" . '>
';
        $this->modified = false;

        $content = file_get_contents($this->filename);
        if (empty($content)) {
            // data not set, set it up
            $this->data = array();
            $this->modified = true;
        }
        else {
            // data is set
            $this->data = unserialize(substr($content, strlen($this->opening)));
            
            if ($cleanup) {
                $limit = time() - $timeout;
                foreach ($this->data as $k => $v) {
                    if ($v['mtime'] < $limit) {
                        unset($this->data[$k]);
                        $this->modified = true;
                    }
                }
            }
        }
    }
    
    function save() {
        if ($this->modified) {
            file_put_contents($this->filename, $this->opening . serialize($this->data));
        }
    }
    
    function touch() {
        $this->data[$_SERVER['REMOTE_ADDR']]['mtime'] = time();
        $this->modified = true;
    }
    
    function start_session($create = true) {
        $this->init(true);
        
        if (!isset($this->data[$_SERVER['REMOTE_ADDR']])) {
            if ($create) {
                $mhs = md5(microtime());
                $this->data[$_SERVER['REMOTE_ADDR']] = array(
                    'mhs' => $mhs,
                    'nhc' => md5(mt_rand(0, 2147483647))
                );
                $this->modified = true;
            }
            else {
                return false;
            }
        }
        else {
            $mhs = $this->data[$_SERVER['REMOTE_ADDR']]['mhs'];
        }

        $this->touch();
        $this->save();
        return substr($mhs, mt_rand(0, 22), 10);
    }
    
    function get_info() {
        $this->init(true);

        $result = false;
        if (isset($this->data[$_SERVER['REMOTE_ADDR']])) {
            $result = $this->data[$_SERVER['REMOTE_ADDR']];
            $this->touch();
        }
        
        $this->save();
        
        return $result;
    }
    
    function get_nhc() {
        $this->init(false);

        $result = @$this->data[$_SERVER['REMOTE_ADDR']]['nhc'];
        if (empty($result)) {
            $result = md5(microtime());
        }
        
        return substr($result, mt_rand(0, 22), 10);
    }

    function validate($mhs, $nhc) {
        if (empty($mhs) || empty($nhc)) {
            return false;
        }
        
        $info = $this->get_info();
        return strpos($info['mhs'], $mhs) !== false && strpos($info['nhc'], $nhc) !== false;
    }
    
}

$data_mgr = new Mod_DomainFinder_DataMgr();

?>