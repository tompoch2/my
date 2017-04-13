// host = '172.16.153.122';	// hostname or IP address
host = '192.168.11.100';	// hostname or IP address
// host = '172.16.153.110';	// hostname or IP address
port = 1883;
topic = '#';		// topic to subscribe to
useTLS = false;
//username = null;
//password = null;
username = "pi";
password = "raspberry";

// path as in "scheme:[//[user:password@]host[:port]][/]path[?query][#fragment]"
//    defaults to "/mqtt"
//    may include query and fragment
//
// path = "/mqtt";
// path = "/data/cloud?device=12345";

cleansession = true;
