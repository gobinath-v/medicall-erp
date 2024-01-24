### Medicall ERP

Guide for install and run the project

- [x] Clone the project in your local machine
  - If you've using laragon, clone the project in the `www` folder.
- [x] Install the composer and NPM dependencies
  - Run the `composer install` and `npm install` command.
- [x] Create an empty database for our application
- [x] Copy the .evn.example file to .env and update the database configuration
- [x] Generate an app encryption key
  - Run the `php artisan key:generate` command.
- [x] Migrate the database
  - Run the following command for migrate the database `php artisan migrate`.
- [x] Seed the database
  - Run the following command for seed the database `php artisan db:seed`.
- [x] Run the project by using vite
  - Copy current app domain (<http://127.0.0.1:8000/login/>) and paste it in the `APP_URL` variable in the `.env` file.
  - Run the `npm run dev` command.

### Login with the following credentials

- Username: `admin@gmail.com`
- Password: `password`
