#!/bin/bash

#===================================================================================================
# Removes deployment scripts
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

# Remove YAML file. Really we should structure revision as:
# /appspec.yml
# /httpdocs
# So that we can just deploy /httpdocs/ folder and not deploy the appspec.yml file at all

# If deploying to DEV
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_DEV" ]; then
    rm -f $DIR_ROOT_DEV'appspec.yml'
    rm -f $DIR_ROOT_DEV'.gitignore'
    rm -rf $DIR_ROOT_DEV'scripts'    
    mv $DIR_ROOT_DEV'.env-dev' $DIR_ROOT_DEV'.env' 
fi

# If deploying to UAT
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_UAT" ]; then
    rm -f $DIR_ROOT_UAT'appspec.yml'
    rm -f $DIR_ROOT_UAT'.gitignore'
    rm -rf $DIR_ROOT_UAT'scripts'
    mv $DIR_ROOT_UAT'.env-uat' $DIR_ROOT_UAT'.env'
fi

# If deploying to PROD
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_PROD" ]; then
    rm -f $DIR_ROOT_PROD'appspec.yml'
    rm -f $DIR_ROOT_PROD'.gitignore'
    rm -rf $DIR_ROOT_PROD'scripts'    
    mv $DIR_ROOT_PROD'.env-prod' $DIR_ROOT_PROD'.env'
fi

