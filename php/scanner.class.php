<?
  class PortScanner 
  {
  	var $start_ip;    /* start of the ip range to scan */
    var $stop_ip;     /* end of the ip range to scan */
    var $current_ip;  /* current ip address being scanned (this is for future features) */
    var $ports;       /* array of ports to be scanned */
    var $wait;        /* how long to wait for a response from the port */
    var $delay;       /* how long to pause between each port */

    function PortScanner($start, $stop) 
    {
    	$this->start_ip = ip2long($start);  /* store the start ip address as a long number */
    	$this->stop_ip = ip2long($stop);    /* store the end ip address as a long number */
    }

    function set_ports($ports) 
    {
        $ports_array = explode(",",$ports);

        foreach($ports_array as $key=>$val) 
        {      
            if(ereg("([0-9]+)\-([0-9]+)",$val, $buff)) 
            {          
      		    for($ii=$buff[1]; $ii<=$buff[2]; $ii++) 
                {
      			   $this->ports[] = $ii;
                }
            } 
            else 
            {          
                $this->ports[] = $val;
            }
        }
    }

    function set_wait($wait) 
    {
        $this->wait = $wait;
    }
    
    function set_delay($seconds=0, $microseconds=0) 
    {
    	$this->delay = (1000000*$seconds) + $microseconds;
    }

    function do_scan() 
    {
        for($this->current_ip=$this->start_ip; $this->current_ip<=$this->stop_ip; $this->current_ip++) 
        {      	
            $ip = long2ip($this->current_ip);
        
            foreach($this->ports as $key=>$port) 
            {
                if (!getservbyport($port,"tcp")) 
                {
                    $pname = "N/A";
                }
                else 
                {
                    $pname = getservbyport($port,"tcp");
                }
          
                $ptcp = fsockopen($ip, $port, &$errno, &$errstr, $this->wait);
                if($ptcp) 
                {
                    $status=1;
                }
                else 
                {   
                    $status=0;
                }      

                $results["$ip"]["$port"]["pname"] = "$pname";
                $results["$ip"]["$port"]["status"] = "$status";

                $this->do_delay($this->delay);
            }
        }
        
        Return $results;
    }

    function do_delay($delay) 
    {
      $start = gettimeofday();
      do
      {
        $stop = gettimeofday();
        $timePassed = 1000000 * ($stop['sec'] - $start['sec']) + $stop['usec'] - $start['usec'];
      }
      while  ($timePassed < $delay);
    }
  }


  $ip_address = gethostbyname("192.168.11.104");

  $my_scanner = new PortScanner($ip_address,$ip_address);
  $my_scanner->set_ports("15-25,80,110,3306,1337,666");
  $my_scanner->set_delay(1);
  $my_scanner->set_wait(2);

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