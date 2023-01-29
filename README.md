## Setup

1. Clone repository.
2. Run `composer install`.
3. Modify .env with correct database parameters.
4. Run `php artisan migrate`.

## Usage

1. Run `php artisan serve` to start the PHP development server.
2. Read API documentation: `YOURDEPLOYMENTSERVERURL:PORT/docs`.
3. Make requests to provided endpoints

## Created files for this assignment

+ /app/Http/Controllers/ProductController.php
+ /app/Http/Controllers/AttributeController.php
+ /app/Http/Resources/ProductResource.php
+ /app/Http/Resources/AttributeResource.php
+ /app/Models/Product.php
+ /app/Models/Attribute.php
+ /app/Models/ProductAttribute.php
+ /tests/Feature/ProductTest.php
+ /tests/Unit/AttributeTest.php
+ /database/migrations/2023_01_26_200501_create_products_table.php
+ /database/migrations/2023_01_26_200510_create_attributes_table.php
+ /database/migrations/2023_01_27_203325_create_productattribute_table.php

## Modified files for this assignment

+ /routes/api.php
+ /config/database.php
+ /phpunit.xml

## Testing

1. Run `php artisan test` or `./vendor/bin/phpunit --testdox`.

## Assignment information

Write a small API that uses a database backend.
The main data entities in your API are product and attribute. Product can have multiple attributes.

A product must have the following properties:
* id
* name
* price

An attribute has properties:
* id
* name

1. Ensure you have all data needed for given task. If in doubt, ask.
2. Implement API calls for
    1. creating, updating and deleting these records.
    2. retrieving
        1. a list of products
        2. searching a product by name
        3. searching products by attribute
3. Ensure API endpoints have proper error handling, responding with meaningful error messages. Responding with appropriate response code and type is a plus.
4. Document the API. Explain the proper way to use it. Assume that the reader is not familiar with the API, and will be using it as a service without having access to the source code.

Write the application either using PHP or Laravel. Use a MySQL database and git for version control.
Please submit:
* API source code (including a README file with deployment instructions);
* SQL schema description;
* Sufficient documentation as part of readme file.
