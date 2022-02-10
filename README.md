1. be sure to configure products-api.local pointing to your localhost
2. there is .env.default, that could be used
3. run migrations and seeders
4. pick one email from users table, password test123 is default for all users
5. use that email and password to perform POST request to /api/login in order to obtain access token 
   curl -X POST -F 'email=labadie.abraham@example.net' -F 'password=test123' http://products-api.local/api/login
6. for the last task please relaunch last migration (2022_02_10_093731_create_ratios_table.php)
php artisan migrate:rollback --step=1
php artisan migrate
7. have fun
