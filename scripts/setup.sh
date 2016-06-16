#!/bin/bash

#===================================================================================================
# Script to set up IAG Promo EC2s
#
# The authorative copy of this script resides in place on the server at:
#     111.67.20.4/var/www/vhosts/saltandfuessel.com.au/httpdocs/clients/iag/scripts/setup.sh
#
# @usage:  sudo ./ec2_setup.sh
#
# @author: andy@saltandfuessel.com.au
# @since   2016/04/11
#===================================================================================================

# ========= CONFIG /START ============
availability_zone="ap-southeast-2"

# Declare vars
DEPLOYMENT_GROUP_DEV='iag-dev-grp'
USER_DEV='iag_promo'
GROUP_DEV='iag_promo'
DIR_ROOT_DEV='/var/www/vhosts/iag_promo/iag.saltandfuessel.com.au/httpdocs/'

DEPLOYMENT_GROUP_PROD='prod'
USER_PROD='iag-promo_prod'
GROUP_PROD='iag-promo_prod'
DIR_ROOT_PROD='/var/www/vhosts/iag-promo_prod/iag-promo.prod.com.au/httpdocs/'

DEPLOYMENT_GROUP_UAT='uat'
USER_UAT='iag-promo_uat'
GROUP_UAT='iag-promo_uat'
DIR_ROOT_UAT='/var/www/vhosts/iag-promo_uat/iag-promo.uat.com.au/httpdocs/'

# === These will be string-replaced by deployment php script
#user=[user]
#site=[site]

#user='iag-promo_uat'
#site='iag-promo.uat.com.au' 

user='iag-promo_prod'
site='iag-promo.prod.com.au'
    
# If deploying to DEV
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_DEV" ]; then
    exit
fi

# If deploying to UAT
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_PROD" ]; then
    user='iag-promo_prod'
    site='iag-promo.prod.com.au'
fi

# If deploying to UAT
if [ "$DEPLOYMENT_GROUP_NAME" == "$DEPLOYMENT_GROUP_UAT" ]; then
    user='iag-promo_uat'
    site='iag-promo.uat.com.au' 
fi

#===================================================================================================
# CONSTANTS
FALSE=0
TRUE=1

# ========= LOGIC /START ============

# Ensure we are running as root user
current_user=`whoami`
if [ "root" != "$current_user" ]; then
		logger "You must run this script as root (using sudo). Current user: $current_user"
        echo "You must run this script as root (using sudo). Current user: $current_user"
        exit
fi


echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>"
echo "Start Time: "`date`
echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>"

#update default packages
echo "------------------------------------------------------------------------------------------"
echo "Updating default packages..."
yum -y update

# Set system date
echo "------------------------------------------------------------------------------------------"
echo "Setting system timezone to $timezone..."
timezone="Australia/Melbourne"
rm -v -f /etc/localtime
ln -s -v /usr/share/zoneinfo/$timezone /etc/localtime
/etc/init.d/crond restart
date

# Install CodeDeploy
# echo "------------------------------------------------------------------------------------------"
# echo "Installing AWS CodeDeploy..."
# cd /home/ec2-user
# wget https://aws-codedeploy-ap-southeast-2.s3.amazonaws.com/latest/install
# chmod +x ./install
# ./install auto

# Remove http22
echo "------------------------------------------------------------------------------------------"
echo "Removing httpd22..."
yum -y autoremove httpd httpd-devel httpd-tools

# Install httpd24 and HTTPS from Amazon repo
echo "------------------------------------------------------------------------------------------"
echo "Installing http24..."
yum -y install httpd24 mod24_ssl

# Configure httpd24 service start up
echo "Configure httpd24 service to run on startup..."
chkconfig httpd on

# Install scl-utils & dependencies via RPM
echo "------------------------------------------------------------------------------------------"
echo "Installing scl-utils..."
cd /home/ec2-user
rpm -i  ftp://195.220.108.108/linux/remi/fedora/21/remi/x86_64/scl-utils-20140815-4.fc21.x86_64.rpm

# Install PHP-FPM 7.0 from Remi repo (Australian Mirror) and required packages
echo "------------------------------------------------------------------------------------------"
echo "Installing php70-fpm..."
yum -y install http://remi.conetix.com.au/enterprise/remi-release-6.rpm
yum -y install php70 php70-php-mbstring php70-php-mcrypt php70-php-pdo php70-php-fpm php70-php-xml

# Configure php70-php-fpm service start up
echo "Configure php-fpm service to run on startup..."
chkconfig php70-php-fpm on

# Move php-fpm template config to prevent unnessary processes
echo "Moving php-fpm template to prevent unnessary processes spin up..."
mv -v /etc/opt/remi/php70/php-fpm.d/www.conf /etc/opt/remi/php70/php-fpm.d/www.conf.template

# Configure PHP
sed -i -e "s/^;*date.timezone =.*/date.timezone = \"Australia\/Melbourne\"/g" /etc/opt/remi/php70/php.ini

# Create a hard link from 'php' to 'php70' so we can use standard php CLI syntax
echo "Creating hard link from 'php' to 'php70'..."
ln -v /usr/bin/php70 /usr/bin/php


# Create virtual host root
mkdir -p -v /var/www/vhosts


# Create php-fpm user
echo "------------------------------------------------------------------------------------------"
echo "Creating php-fpm user..."
useradd --home /var/www/vhosts/$user $user
chmod 755 -c -v /var/www/vhosts/$user


# Create vhost structure
echo "------------------------------------------------------------------------------------------"
echo "Creating virtual host directories..."
mkdir -v /var/www/vhosts/$user/$site
mkdir -v /var/www/vhosts/$user/$site/httpdocs
mkdir -v /var/www/vhosts/$user/$site/httpdocs/public
mkdir -v /var/www/vhosts/$user/$site/log
mkdir -v /var/www/vhosts/$user/$site/session
mkdir -v /var/www/vhosts/$user/$site/wsdlcache

# Change folder permissions to world readable/executable
find /var/www/vhosts -type d -exec chmod -c 755 {} + 

# Create the log files so we can read them (otherwise would be 640)
touch /var/www/vhosts/$user/$site/log/access_log
touch /var/www/vhosts/$user/$site/log/error_log
touch /var/www/vhosts/$user/$site/log/php-fpm-error.log

chmod 644 -c -v /var/www/vhosts/$user/$site/log/*


# Set ownership and permissions
chown -R -c $user:$user /var/www/vhosts/$user/
chown -R -c root:root /var/www/vhosts/$user/$site/log/

# Get free port for php-fpm process to listen on
echo "------------------------------------------------------------------------------------------"
echo "Getting free port for php-fpm..."
available_port_found=$FALSE
php_fpm_port=9010
while [ $FALSE == $available_port_found ]; do

        echo "Checking port $php_fpm_port"
        output=`grep -r fcgi://127.0.0.1:$php_fpm_port /etc/httpd/conf.d`

        # if matching line found
        if [ ${#output} -gt 0 ]; then
                echo "port in use: $php_fpm_port"
                php_fpm_port=$(($php_fpm_port+10))
        else
                echo "free port found: $php_fpm_port"
                available_port_found=$TRUE
        fi
done


# Configure Apache
echo "------------------------------------------------------------------------------------------"
echo "Configuring Apache vhost..."
vhost_content="
<IfVersion < 2.4>
    NameVirtualHost *:80
</IfVersion>
<VirtualHost *:80>
    ServerName   $user
    ServerAdmin  alerts@saltandfuessel.com.au
    DocumentRoot \"/var/www/vhosts/$user/$site/httpdocs/public\"
    ErrorLog     \"/var/www/vhosts/$user/$site/log/error_log\"
    CustomLog    \"/var/www/vhosts/$user/$site/log/access_log\" common

        <IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f
                RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-d
                RewriteRule . /index.php [PT]
        </IfModule>


    <Directory \"/var/www/vhosts/$user/$site/httpdocs\">
                Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All

        #fix so we can run both apache 2.2 and 2.4
        <IfVersion < 2.4>
            Allow from all
        </IfVersion>
        <IfVersion >= 2.4>
            Require all granted
                </IfVersion>
        </Directory>

        ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://127.0.0.1:$php_fpm_port/var/www/vhosts/$user/$site/httpdocs/public/\$1
</VirtualHost>
"
echo "$vhost_content"
echo "$vhost_content" > /etc/httpd/conf.d/$site.conf


# Create PHP-FPM config file
echo "------------------------------------------------------------------------------------------"
echo "Configuring php-fpm..."
cp -v /etc/opt/remi/php70/php-fpm.d/www.conf.template  /etc/opt/remi/php70/php-fpm.d/$site.conf


# Configure PHP-FPM
sed -i -e "s/^\[www\]/[$site]/" /etc/opt/remi/php70/php-fpm.d/$site.conf
sed -i -e "s/^user = apache/user = $user/" /etc/opt/remi/php70/php-fpm.d/$site.conf
sed -i -e "s/^group = apache/group = $user/" /etc/opt/remi/php70/php-fpm.d/$site.conf
sed -i -e "s/^listen = 127.0.0.1:9000/listen = 127.0.0.1:$php_fpm_port/" /etc/opt/remi/php70/php-fpm.d/$site.conf
sed -i -e "s/^php_admin_value\[error_log\].*/php_admin_value[error_log] = \/var\/www\/vhosts\/$user\/$site\/log\/php-fpm-error.log/" /etc/opt/remi/php70/php-fpm.d/$site.conf
sed -i -e "s/^php_value\[session.save_path\].*/php_value[session.save_path] = \/var\/www\/vhosts\/$user\/$site\/session/" /etc/opt/remi/php70/php-fpm.d/$site.conf
sed -i -e "s/^php_value\[soap.wsdl_cache_dir\].*/php_value[soap.wsdl_cache_dir] = \/var\/www\/vhosts\/$user\/$site\/wsdlcache/" /etc/opt/remi/php70/php-fpm.d/$site.conf


# Install Composer
echo "------------------------------------------------------------------------------------------"
echo "Exporting Composer-installation-required environment variables..."
export -p COMPOSER_HOME=/var/www/vhosts/$user/$site/httpdocs
echo "Installing Composer..."
cd /var/www/vhosts/$user/$site/httpdocs
php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
#php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/bin/composer
chmod 755 /usr/bin/composer



# Configure AWS CodeDeploy
# Note that at this point, CodeDeploy is not yet installed.
echo "------------------------------------------------------------------------------------------"
echo "Configuring AWS CodeDeploy..."
mkdir -v /root/.aws
echo "[default]
region = $availability_zone
" > ~/.aws/config


# Create symbolic link to deploy code to.
# Link target is envionment specific.
# This enables us to define a single destination in YAML, but support multiple environment folder hierachies.
# IMPORTANT: You must deploy to a subfolder of the link, eg /etc/codedeploy-agent/links/iag_promo/httpdocs
# IT WILL FAIL SILENTLY IF YOU TRY TO DEPLOY TO /etc/codedeploy-agent/links/
echo "------------------------------------------------------------------------------------------"
echo "Creating CodeDeploy Target Symbolic Link..."
mkdir -p -v /etc/codedeploy-agent/links/
ln --symbolic /var/www/vhosts/$user/$site /etc/codedeploy-agent/links/iag_promo


# Install AWS 'CloudWatch Agent'
# echo "------------------------------------------------------------------------------------------"
# echo "Installing CloudWatch Agent..."
# yum install -y awslogs

# Start awslogs on reboot
# sudo chkconfig awslogs on


# Configure CloudWatch to stream selected logs ( /etc/awslogs/awscli.conf )
echo "------------------------------------------------------------------------------------------"
echo "Configuring CloudWatch logs..."
echo " >>>>>>>>>>>>>>>>>> TODO - edit /etc/awslogs/awscli.conf <<<<<<<<<<<<<<<<<<<"





# Restart PHP-FPM
echo "------------------------------------------------------------------------------------------"
echo "Restarting php-fpm..."
/etc/init.d/php70-php-fpm restart

# Restart Apache
echo "------------------------------------------------------------------------------------------"
echo "Restarting httpd..."
/etc/init.d/httpd restart

# Restart CodeDeploy Agent using KILL 
# A restart or SIGTERM causes ~1min delay, presumably until next poll. 
# Since this script runs before any deployments, we can safely SIGKILL 
echo "------------------------------------------------------------------------------------------"
echo "Restarting codedeploy-agent..."
# Option #1 - Kill and start
# codedeploy_pid=`/etc/init.d/codedeploy-agent status | grep -o "[0-9]*"`
# kill -SIGKILL $codedeploy_pid
# /etc/init.d/codedeploy-agent start

# Option #2 - Terminate and start
#echo "Restarting services... (note that codedeploy-agent takes a while to restart ~1min)"
#/etc/init.d/codedeploy-agent restart

# Restart AWS Logs
echo "------------------------------------------------------------------------------------------"
echo "Restarting awslogs..."
/etc/init.d/awslogs restart


# Get Private IP address & Report it back to SF Admins so they can connect from Bastion
# TODO - this is just bloody awkward. Need permission to query from AWS CLI
private_ip=`ifconfig | grep -o "inet addr:[0-9.]*  Bcast" | grep -o [0-9.]*`
curl -k 'http://saltandfuessel.com.au/clients/iag/scripts/report_private_ip.php?password=Uns6rW2nJs&private_ip='$private_ip


echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<"
echo "End Time: "`date`
echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<"


 











