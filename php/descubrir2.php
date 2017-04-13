<?php
include("scanner.class.php");

$ip_address1 = "192.168.11.99";
$ip_address2 = "192.168.11.115";

$my_scanner = new PortScanner($ip_address1, $ip_address2);
$my_scanner->set_ports("1-100");
$my_scanner->set_wait(2);
$my_scanner->set_delay(0, 5000);

$results = $my_scanner->do_scan();

foreach($results as $ip=>$ip_results) 
{
    echo gethostbyaddr($ip)."\n<blockquote>\n";

    foreach($ip_results as $port=>$port_results) 
    {
        echo "\t".$port." : ".$port_results['pname']." : ";
        if ($port_results['status']==1)
        {
            echo "open";
        }
        else 
        {
            echo "closed";
        }
        echo "<br>\n";
    }
    echo "</blockquote>\n\n";
}


?>