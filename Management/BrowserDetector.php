<?php

class BrowserDetector{
    static public function Detects(){
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = 'Unknown';
        $version= "";

        // IE 11
        if ( preg_match( '/Trident\/7.0; rv:11.0/',$u_agent ) ){
            return array(
                'name'      => "MSIE",
                'version'   => 11,
            );
        }
        else if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
            $ub = "MSIE";
        }
        else if (preg_match('/Firefox/i',$u_agent)){
            $ub = "Firefox";
        }
        else if (preg_match('/Chrome/i',$u_agent)){
            $ub = "Chrome";
        }
        else if (preg_match('/Safari/i',$u_agent)){
            $ub = "Safari";
        }
        else if (preg_match('/Opera/i',$u_agent)){
            $ub = "Opera";
        }
        else if (preg_match('/Netscape/i',$u_agent)){
            $ub = "Netscape";
        }

        $known = array('Version', $ub, 'other');
        $pattern = '#(?P<browser>' . join('|', $known) .
            ')[/ ]+(?P<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version = $matches['version'][0];
            }
            else {
                $version = $matches['version'][1];
            }
        }
        else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'name'      => $ub,
            'version'   => $version,
        );
    }
}