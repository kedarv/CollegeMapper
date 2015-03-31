# CollegeMapper
Welcome to 2014Maps. This application was built to visualize where senior year students in high school will attend college. This application is the more mature and abstracted version of 2014Maps. The main advantage of CollegeMapper over 2014Maps is the use of a modern MVC framework (Laravel 4.2). This allows for the use of abstracted database queries, efficient view rendering, and simple routing.

# Installation
### Download
You may either download the .zip file or clone the repository into a folder.

### Composer
Laravel utilizes Composer to manage its dependencies. You can read about installing [composer here.](https://getcomposer.org/) Once you have installed composer, run 'composer install'

### Database
Eloquent allows for the use of MySQL, SQLite, pgSQL, and SQLSRV. Open app/config/database.php and fill in the appropriate details. Then, open a terminal and navigate to the root folder of CollegeMapper. Run 'php artisan migrate'
