# laradock directory
`cd ./laradoc`

# run all
`docker-compose up --detach nginx mariadb`

# create tables
`docker-compose exec workspace php artisan migrate`

# seed initial users
`docker-compose exec workspace php artisan db:seed --class=UsersTableSeeder`

# confirm users
`docker-compose exec mariadb mysql -uuser -puser -e'select * from crm.users;'`

# Put a symlink from /public/storage to /storage/app/public folder
Store client avatars in storage/app/public folder and make them accessible from public
`docker-compose exec workspace php artisan storage:link`

# Add some test clients/transactions using faker
`docker-compose exec workspace php artisan db:seed --class=TestDataSeeder`


# Install front-end dependencies
`docker-compose exec workspace npm install`


# laravel env
DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=crm
DB_USERNAME=root
DB_PASSWORD=user

# TODO:
copy all to transactions
create postman for transactions
enhance existing example with new views
depending on time login/logout
depending on time tests + faker
full test to clone & run