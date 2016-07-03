<?php
/*
the code in this file was provided by: Nsp Code  http://www.nsp-code.com
and should not be used without his permissions
*/


if (!class_exists('wpdb2')) 
    {
 
        Class wpdb2 Extends wpdb 
            {

	            function _ping() 
                    {

		                $retry = 3;
		                $failed = 1;
                        
		                $ping = mysql_ping( $this->dbh ) ;
		                while( !$ping && $failed < $retry) 
                            {

			                    $this->dbh = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, 1);
			                    $this->select(DB_NAME);

			                    if ( !DB_CHARSET && version_compare(mysql_get_server_info($this->dbh), '4.1.0', '>=')) 
                                    {
 				                        $this->query("SET NAMES '" . DB_CHARSET . "'");
 				                    }
                                    
			                    $ping = mysql_ping( $this->dbh ) ;
			                    if(!$ping ) 
                                    {
				                        sleep(2);
				                        $failed+=1;
				                    }
			                    }

		                if(!$ping ) 
                            {
			                    $this->print_error('Attempted to connect for ' . $retry	. ' but failed...');
			                }
		                }

	            function query($query) 
                    {
                        $this->_ping();
		                return parent::query($query);
		            }
	        }

	    $wpdb2 = new wpdb2(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
	    foreach(get_object_vars($wpdb) as $k=>$v) 
            {
		        if (is_scalar($v)) 
                    {
			            $wpdb2->$k = $v;
			        }
		    }
	    
        $wpdb =& $wpdb2;
        
 
                                                                                                  
    }

?>
