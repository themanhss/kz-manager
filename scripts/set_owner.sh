#!/bin/bash

#===================================================================================================
# Sets the owner:group of deployed files.
# Provisions the correct environment-specific config files.
# 
# We must perform this logic in a script, because YAML files do not permit logic contructs.
# The same application is deployed to multiple environment-types. It is up to this script to 
# detect the environment, and act accordingly.
#
# @author andy@saltandfuessel.com.au
# @since  2016/03/23
#===================================================================================================

# Declare vars
DEPLOYMENT_GROUP_DEV='iag-dev-grp'
USER_DEV='iag_promo'
GROUP_DEV='iag_promo'
DIR_ROOT_DEV='/var/www/vhosts/iag_promo/iag.saltandfuessel.com.au/httpdocs/'

DEPLOYMENT_GROUP_UAT='uat'
USER_UAT='iag-promo_uat'
GROUP_UAT='iag-promo_uat'
DIR_ROOT_UAT='/var/www/vhosts/iag-promo_uat/iag-promo.uat.com.au/httpdocs/'

DEPLOYMENT_GROUP_PROD='prod'
USER_PROD='iag-promo_prod'
GROUP_PROD='iag-promo_prod'
DIR_ROOT_PROD='/var/www/vhosts/iag-promo_prod/iag-promo.prod.com.au/httpdocs/'

# If deploying to DEV
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_DEV" ]; then
    chown -R $USER_DEV:$GROUP_DEV $DIR_ROOT_DEV
    cd DIR_ROOT_DEV'storage' 
    sudo chmod -R 755 *
fi 

#If deploying to UAT
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_UAT" ]; then
    chown -R $USER_UAT:$GROUP_UAT $DIR_ROOT_UAT
    cd /var/www/vhosts/iag-promo_uat/iag-promo.uat.com.au/httpdocs/
    sudo -u iag-promo_uat composer install
    sudo -u iag-promo_uat composer update
    cd DIR_ROOT_UAT'storage' 
    sudo chmod -R 755 *
fi 

#If deploying to UAT
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_PROD" ]; then
    chown -R $USER_PROD:$GROUP_PROD $DIR_ROOT_PROD
    cd /var/www/vhosts/iag-promo_prod/iag-promo.prod.com.au/httpdocs/
    sudo -u iag-promo_prod composer install
    sudo -u iag-promo_prod composer update
    cd DIR_ROOT_PROD'storage' 
    sudo chmod -R 755 *
fi 

  
