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


$result = array(
    'version' => 'DomainFinder 1.0',
    'domain' => trim(@$_GET['domain']),
    'key' => trim(@$_GET['key']),
    'task' => trim(@$_GET['task']),
    'host' => trim(@$_GET['host']),
    'availability' => '',
    'error' => '',
);

function output($raw_info = '') {
    if ($GLOBALS['result']['task'] == 'info') {
        if ($GLOBALS['result']['error'] == '') {
            echo $raw_info;
        }
        else {
            echo 'Error: ' . $GLOBALS['result']['error'];
        }
    }
    else {
        foreach ($GLOBALS['result'] as $k => $v) {
            echo "$k: $v\n";
        }

        echo "\n";
        echo $raw_info;
    }   
    
    exit();
}

if ($result['domain'] == '') {
    $result['error'] = 'Domain is not set.';
    output();
}

if ($result['task'] == '') {
    $result['error'] = 'Task is not set.';
    output();
}
else if (!in_array($result['task'], array('search', 'info'))) {
    $result['error'] = 'Requested task is not recognizable.';
    output();
}


require_once(dirname(__FILE__) . '/data.inc.php');
if (!$data_mgr->validate(@$_GET['mhs'], @$_GET['nhc'])) {
    $result['error'] = 'Not authorized or page time-out. Please refresh the page and retry.';
    output();
}


class Mod_DomainFinder_Search {

    var $exception;
    var $error_no;
    var $error_msg;
    
    var $info_text;
    var $info_whois_server;
    var $info_creation_time;
    var $info_update_time;
    var $info_expiration_time;

    function reset() {
        $this->exception = '';
        $this->error_no = 0;
        $this->error_msg = '';

        $this->info_text = '';
        $this->info_whois_server = '';
        $this->info_creation_time = 0;
        $this->info_update_time = 0;
        $this->info_expiration_time = 0;
    }

    function search_host($domain, $host) {
        $this->reset();     
        @set_time_limit(60);
        $conn = @fsockopen($host, 43, $this->error_no, $this->error_msg, 30);
        if (empty($conn)) {
            $this->exception = 'CONNECTION_FAILURE';
        }

        if ($this->exception == '') {       
            fputs($conn, $domain . "\r\n");
            
            $lines = 0;
            while (!feof($conn)) {
                $line = fgets($conn, 1024);
                $this->info_text .= $line;
                $lines++;
                
                $line = trim($line);
                
                if (preg_match('/^Whois Server\s*:?/i', $line, $m)) {
                    $this->info_whois_server = trim(substr($line, strlen($m[0])));
                }
                
                if (preg_match('/^Creation Date\s*:?/i', $line, $m)) {
                    $this->info_creation_time = strtotime(trim(substr($line, strlen($m[0]))));
                }
                
                if (preg_match('/^Updated Date\s*:?/i', $line, $m)) {
                    $this->info_update_time = strtotime(trim(substr($line, strlen($m[0]))));
                }
                
                if (preg_match('/^Expiration Date\s*:?/i', $line, $m)) {
                    $this->info_expiration_time = strtotime(trim(substr($line, strlen($m[0]))));
                }
            }
            
            fclose($conn);
            
            $this->info_text = trim($this->info_text);
    
            if ($this->info_text == '') {
                $this->exception = 'EMPTY_RESPONSE';
            }
        }

        if ($this->exception == '' && preg_match('/no match for/i', $this->info_text)) {
            $this->exception = 'AVAILABLE';
        }

        if ($this->exception == '' && preg_match('/no data found/i', $this->info_text)) {
            $this->exception = 'AVAILABLE';
        }

        if ($this->exception == '' && preg_match('/not found/i', $this->info_text)) {
            $this->exception = 'AVAILABLE';
        }
        
        if ($this->exception == '' && $lines <= 2) {
            $this->exception = 'UNEXPECTED_RESPONSE';
        }
        
        if ($this->exception == '' && preg_match('/error/i', $this->info_text)) {
            $this->exception = 'UNEXPECTED_RESPONSE';
        }

        return $this->exception == '';
    }
    
    function search($domain, $custom_host = '') {
        $this->reset();
        $base_host = 'whois-servers.net';
        $domain = trim($domain);
        
        if ($domain == '') {
            $this->exception = 'DOMAIN_NOT_SET';
        }

        // in case domain is given in IP format, try to get the host
        if ($this->exception == '' && preg_match('/^\d+(\.\d+){3}$/', $domain)) {
            $d = gethostbyaddr($domain);
            if ($d == $domain) {
                $this->exception = 'INVALID_DOMAIN';
            }
            else {
                $domain = $d;
            }
        }
        
        if ($this->exception == '' && !preg_match('/^[a-z0-9-]+(\.[a-z0-9-]+)+$/i', $domain)) {
            $this->exception = 'INVALID_DOMAIN';
        }

        if ($this->exception == '') {
            // analyze parts of the domain for further examination
            $parts = explode('.', $domain);
            $part_count = count($parts);
            
            if ($part_count < 2) {
                $this->exception = 'INVALID_DOMAIN';
            }
        }
        
        if ($this->exception == '') {
            // if domain starts with "www.", strip it
            if ($part_count > 2 && strtolower($parts[0]) == 'www') {
                $domain = substr($domain, 4);
            }

            if ($custom_host == '') {
                // search TLD.whois-servers.net
                $host = strtolower($parts[$part_count - 1]) . '.' . $base_host;
                $this->search_host($domain, $host);
            
                // in case TLD.whois-servers.net does not work, search without the TLD
                if ($this->exception == 'UNEXPECTED_RESPONSE') {
                    $this->search_host($domain, $base_host);
                }
            }
            else {
                $this->search_host($domain, $custom_host);
            }
        }

        return $this->exception == '';
    }
}

$search = new Mod_DomainFinder_Search();
if ($search->search($result['domain'], $result['host'])) {
    $result['availability'] = 'unavailable';
}
else {
    switch ($search->exception) {
        case 'AVAILABLE':
            $result['availability'] = 'available';
            break;
            
        case 'CONNECTION_FAILURE':
            $result['error'] = 'Connection failure to WHOIS server';
            break;
            
        case 'DOMAIN_NOT_SET':
            $result['error'] = 'Domain is not set';
            break;
            
        case 'INVALID_DOMAIN':
            $result['error'] = 'Invalid domain';
            break;
            
        case 'UNEXPECTED_RESPONSE':
        case 'EMPTY_RESPONSE':
        default:
            $result['error'] = 'Empty or unexpected response from WHOIS server';
    }
}

$result['host'] = $search->info_whois_server;

output($search->info_text);

?>