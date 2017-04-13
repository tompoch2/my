<?php
    error_reporting(0);
    set_time_limit(0);
    
//    $host = $_POST["host"];
//    $sport = $_POST["sport"];
//    $eport = $_POST["eport"];
//    $delay = 1;
    
//    $host = "google.com";
//    $sport = "70";
//    $eport = "100";
//    $delay = 1;
    
    $host = $_SERVER['argv'][1];
    $sport = $_SERVER['argv'][2];
    $eport = $_SERVER['argv'][3];
    $delay = 1;
    
    function usage()
    {
        print "ReneXXX Port Scanner\n";
        print "Uso: php {$_SERVER['argv'][0]} {hostname} {start} {end}\n";
        print "\n" . "Example : php {$_SERVER['argv'][0]}  google.com 80 443\n";
        die();
    }
    
    if ($_SERVER['argc'] != 4) usage();    
    for($i = $sport; $i <= $eport; $i++)
    {
        $fp = fsockopen($host, $i, $errno, $errstr, $delay);
        if(getservbyport($i, 'tcp') == '') $protocol = 'Unknown'; else $protocol = getservbyport($i, 'tcp');
        
        if ($fp){
            print "port $i [$protocol] on $host is Active\n";
            fclose($fp);
        } else {
            print "port $i [protocol] on $host is Inactive\n";
        }
        flush();
    }
    
?>