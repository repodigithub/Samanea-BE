## Samanea BE - Lumen 9.x REST API

Documentation is still on progress. For now, you can fork this postman collection\
[![Run in Postman](https://run.pstmn.io/button.svg)](https://documenter.getpostman.com/view/21110464/UzQvtk8m)

### Installation

 1. Clone this project\
 `git clone https://git@github.com:repodigithub/Samanea-BE.git`
 2. Cd into your project folder\
 `cd Samanea-BE`
 3. Install dependencies\
 `composer install --no-dev`\
 Or if you want continue developing this project\
 `composer install`
 5. Copy env file\
 `cp .env.example .env`
 4. Setup your database via .env file
 5. Make app key\
`php artisan key:generate`
 6. Migrate database\
 `php artisan migrate`
 7. (Optional) Seed the database\
 `php artisan db:seed`
 8. Create jwt key\
 `php artisan jwt:secret`
 ## Serve
 `php -S localhost:8000 -t public`
 