# apiseplac

composer install 
php artisan migrate
php artisan db:seed --class=DependenciasTableSeeder
php artisan db:seed --class=RolesTableSeeder
php artisan db:seed --class=UsersTableSeeder
php artisan serve
