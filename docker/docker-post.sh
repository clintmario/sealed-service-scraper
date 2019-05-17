#!/bin/bash

# Note line ending.

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
echo $DIR > /tmp/docker-post.log
chmod 777 /tmp/docker-post.log
source "$DIR/../.env"

# Sleep for 2s
/bin/sleep 2

# Create MySQL database and user
MYSQL=`which mysql`
Q1="CREATE DATABASE IF NOT EXISTS $DB_DATABASE;"
Q2="GRANT USAGE ON *.* TO '$DB_USERNAME'@'%' IDENTIFIED BY '$DB_PASSWORD';"
Q3="GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'%';"
Q4="FLUSH PRIVILEGES;"
SQL="${Q1}${Q2}${Q3}${Q4}"
    
if [ $APP_ENV != "production" ]; then
$MYSQL -uroot -e "$SQL"
echo "SQL executed." >> /tmp/docker-post.log
fi

su $CMW_USER_NAME <<'EOF'
/bin/bash
source $HOME/.env
cd $CMW_APP_ROOT_DIR
echo $CMW_APP_ROOT_DIR >> /tmp/docker-post.log
eval `ssh-agent -s`
ssh-add $HOME/$CMW_GITHUB_SSH_KEY_FILE_NAME
ssh-keyscan github.com >> ~/.github_key
ssh-keygen -lf ~/.github_key
cat ~/.github_key >> ~/.ssh/known_hosts
if [ $APP_ENV == "production" ]; then
    git checkout master
fi
git pull
composer install
chown -R www-data:www-data storage
php artisan migrate --path=app/Modules/Home/Migrations
php artisan migrate --path=app/Modules/User/Migrations
php artisan migrate --path=app/Modules/Group/Migrations
php artisan migrate --path=app/Modules/CMS/Migrations
php artisan migrate --path=app/Modules/Application/Migrations
php artisan db:seed --class="App\Modules\User\Seeders\UsersSeeder"
php artisan db:seed --class="App\Modules\CMS\Seeders\CMSSeeder"
php artisan db:seed --class="App\Modules\MITOCW\Seeders\POCSeeder"
EOF
