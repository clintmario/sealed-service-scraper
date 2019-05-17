#!/bin/bash

# Note line ending.

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
source "$DIR/../.env"

# Create and enable Virtual Host
if [ ! -f /etc/apache2/sites-available/$CMW_APP_NAME.conf ]; then
    cp $DIR/vhosts/$CMW_APP_NAME.tmpl /etc/apache2/sites-available/$CMW_APP_NAME.conf
    sed -i -e "s/CMW_SERVER_NAME/$CMW_SERVER_NAME/g" /etc/apache2/sites-available/$CMW_APP_NAME.conf
    sed -i -e "s/CMW_APP_NAME/$CMW_APP_NAME/g" /etc/apache2/sites-available/$CMW_APP_NAME.conf
    ln -s /etc/apache2/sites-available/$CMW_APP_NAME.conf /etc/apache2/sites-enabled
fi

# Make MySQL database bind address public
if grep -q "^bind-address\s*=\s*127.0.0.1" /etc/mysql/mariadb.conf.d/50-server.cnf; then
    sed -i -e "s/^bind-address\s*=\s*127.0.0.1/bind-address = 0.0.0.0/" /etc/mysql/mariadb.conf.d/50-server.cnf
fi

# Setup /etc/hosts entry
#if ! grep -q "$CMW_SERVER_NAME" /etc/hosts; then
#    echo -e "127.0.0.1\t$CMW_SERVER_NAME" >> /etc/hosts
#fi

# Setup Classes Masses Web User Account
useradd $CMW_USER_NAME
echo "$CMW_USER_NAME:$CMW_USER_PASSWORD" | chpasswd
mkdir /home/$CMW_USER_NAME
chmod 775 /home/$CMW_USER_NAME
chown -R $CMW_USER_NAME:$CMW_USER_NAME /home/$CMW_USER_NAME
usermod -a -G sudo $CMW_USER_NAME
usermod -a -G www-data $CMW_USER_NAME
chmod -R 775 $CMW_APP_ROOT_DIR
chown -R $CMW_USER_NAME:$CMW_USER_NAME $CMW_APP_ROOT_DIR
ln -s $CMW_APP_ROOT_DIR/.env /home/$CMW_USER_NAME
cp $CMW_APP_ROOT_DIR/$CMW_GITHUB_SSH_KEY_FILE_NAME /home/$CMW_USER_NAME
chown $CMW_USER_NAME:$CMW_USER_NAME /home/$CMW_USER_NAME/$CMW_GITHUB_SSH_KEY_FILE_NAME
chmod 400 /home/$CMW_USER_NAME/$CMW_GITHUB_SSH_KEY_FILE_NAME
