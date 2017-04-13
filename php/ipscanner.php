		
<?php
    error_reporting(0);
    $from = $_POST['ip1'];
    $to = $_POST['ip2'];
    $port1=$_POST['port1'];
    $port2=$_POST['port2'];
//    $from = "192.168.11.1";
 //   $to = "192.168.11.255";
//    $port1="1";
//    $port2="90";
    $myFile = "ip_up.txt";
    $fh = fopen($myFile, 'w');
    $arry1 = explode(".",$from);
    $arry2 = explode(".",$to);
    $a1 = $arry1[0]; $b1 = $arry1[1]; $c1 = $arry1[2]; $d1 = $arry1[3];
    $a2 = $arry2[0]; $b2 = $arry2[1]; $c2 = $arry2[2]; $d2 = $arry2[3];
    while( $d2 >= $d1 || $c2 > $c1 || $b2 > $b1 || $a2 > $a1)
    {
        if($d1 > 255)
        {
            $d1 = 1;
            $c1 ++;
        }
        if($c1 > 255)
        {
            $c1 = 1;
            $b1 ++;
        }
        if($b1 > 255)
        {
            $b1 = 1;
            $a1 ++;
        }
        $ip = $a1 . "." . $b1 . "." . $c1 . "." .$d1;
        for($i=$port1;$i<(int)$port2+1;$i++) 
        {
            $tB = microtime(true);
            $fP = fSockOpen($ip, $i, $errno, $errstr, 1);
            $tA = microtime(true);
            if (!$fP) 
            {
                echo $ip.":".$i." – down";
            }
            else 
            {
                echo ($ip . ":" . $i);
                // . " – " . round((($tA – $tB) * 1000), 0) . " ms");
                fwrite($fh,$ip."\r\n");
            }
            echo "<br>";
            flush();
        }
        $d1 ++;
    }
    echo '<a href="ip_up.txt">Download</a>';
?>