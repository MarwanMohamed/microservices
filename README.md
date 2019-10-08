# microservices

#open books in 5051
php -S localhost:5051 -t public


#open authers in 5050
php -S localhost:5050 -t public


#login with token on gateway
php -S localhost:8000 -t public

* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
