# Astra Tech Task

This is an API online store with CRUD functionality for products and categories as well as ordering functionality

## How to run the project

Clone the repo, then install the dependencies using `composer install`, make sure you have PHP >= 8.2 installed. Then, execute `composer run setup`. This is will setup the .env file, do the migration and seed the database with some dummy data for testing purposes. This also creates an admin user with email `example@example.com` and password `12345678`.

After that you can serve the application usign `php artisan serve`.

## API documentation

The API documentation is built using Swagger UI, to see the documentaion and to test the API you visit `http://localhost:8000/api/documentation` after serving the project.
