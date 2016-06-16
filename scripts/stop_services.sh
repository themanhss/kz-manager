#!/bin/bash

#===================================================================================================
# Stops services so system is not accessed by users in partially-deployed state 
#
# @author andy@saltandfuessel.com.au
# @since  2016/03/23
#===================================================================================================

# Stop httpd
/etc/init.d/httpd stop
