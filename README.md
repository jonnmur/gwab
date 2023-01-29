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
