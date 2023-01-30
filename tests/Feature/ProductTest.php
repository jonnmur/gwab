<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testApiGetReturnsProductList()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Testing Attribute 1';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Testing Attribute 2';
        $attribute2->save();

        // Create products
        $product1 = new Product();
        $product1->name = 'Testing Product 1';
        $product1->price = 11.99;
        $product1->save();

        $product1->attributes()->attach([$attribute1->id]);

        $product2 = new Product();
        $product2->name = 'Testing Product 2';
        $product2->price = 12.99;
        $product2->save();

        $product2->attributes()->attach([$attribute1->id, $attribute2->id]);

        $response = $this->call('GET', '/api/products');

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                [
                    'id' => $product1->id,
                    'name' => $product1->name,
                    'price' => $product1->price,
                    'attributes' => [
                        [
                            'id' => $attribute1->id,
                            'name' => $attribute1->name,
                        ],
                    ],
                ],
                [
                    'id' => $product2->id,
                    'name' => $product2->name,
                    'price' => $product2->price,
                    'attributes' => [
                        [
                            'id' => $attribute1->id,
                            'name' => $attribute1->name,
                        ],
                        [
                            'id' => $attribute2->id,
                            'name' => $attribute2->name,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testApiGetFiltersProductsByProductName()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Food and Drinks';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Tech and Machines';
        $attribute2->save();

        // Create products
        $product1 = new Product();
        $product1->name = '1L Milk';
        $product1->price = 1.19;
        $product1->save();

        $product1->attributes()->attach([$attribute1->id]);

        $product2 = new Product();
        $product2->name = 'Very Nice Microwave';
        $product2->price = 299.99;
        $product2->save();

        $product2->attributes()->attach([$attribute1->id, $attribute2->id]);

        $response = $this->call('GET', '/api/products', [
            'search' => 'milk'
        ]);

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                [
                    'id' => $product1->id,
                    'name' => $product1->name,
                    'price' => $product1->price,
                    'attributes' => [
                        [
                            'id' => $attribute1->id,
                            'name' => $attribute1->name,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testApiGetFiltersProductsByAttributeName()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Food and Drinks';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Tech and Machines';
        $attribute2->save();

        // Create products
        $product1 = new Product();
        $product1->name = '1L Milk';
        $product1->price = 1.19;
        $product1->save();

        $product1->attributes()->attach([$attribute1->id]);

        $product2 = new Product();
        $product2->name = 'Very Nice Microwave';
        $product2->price = 299.99;
        $product2->save();

        $product2->attributes()->attach([$attribute1->id, $attribute2->id]);

        $product3 = new Product();
        $product3->name = 'Phone';
        $product3->price = 959.99;
        $product3->save();

        $product3->attributes()->attach([$attribute2->id]);

        $response = $this->call('GET', '/api/products', [
            'search' => 'food'
        ]);

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                [
                    'id' => $product1->id,
                    'name' => $product1->name,
                    'price' => $product1->price,
                    'attributes' => [
                        [
                            'id' => $attribute1->id,
                            'name' => $attribute1->name,
                        ],
                    ],
                ],
                [
                    'id' => $product2->id,
                    'name' => $product2->name,
                    'price' => $product2->price,
                    'attributes' => [
                        [
                            'id' => $attribute1->id,
                            'name' => $attribute1->name,
                        ],
                        [
                            'id' => $attribute2->id,
                            'name' => $attribute2->name,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testApiGetReturnsSingleProductById()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Testing Attribute 1';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Testing Attribute 2';
        $attribute2->save();

        // Create products
        $product1 = new Product();
        $product1->name = 'Testing Product 1';
        $product1->price = 11.99;
        $product1->save();

        $product1->attributes()->attach([$attribute1->id]);

        $product2 = new Product();
        $product2->name = 'Testing Product 2';
        $product2->price = 12.99;
        $product2->save();

        $product2->attributes()->attach([$attribute1->id, $attribute2->id]);

        $response = $this->call('GET', '/api/products/' . $product1->id);

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                'id' => $product1->id,
                'name' => $product1->name,
                'price' => $product1->price,
                'attributes' => [
                    [
                        'id' => $attribute1->id,
                        'name' => $attribute1->name,
                    ],
                ],
            ],
        ]);
    }

    public function testApiGetReturns404IfProductDoesNotExist()
    {
        $response = $this->call('GET', '/api/products/9999');

        $response->assertStatus(404);
    }

    public function testApiPostCreatesProductWithAttributesIfTheyExist()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Testing Attribute 1';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Testing Attribute 2';
        $attribute2->save();

        $response = $this->call('POST', '/api/products/', [
            'name' => 'Testing Product',
            'price' => 13.99,
            'attributes' => [$attribute1->id, $attribute2->id, 9999],
        ]);

        $response->assertStatus(201);

        $this->assertEquals('Product Created', $response->json()['message']);
        $this->assertEquals('Testing Product', $response->json()['data']['name']);
        $this->assertEquals(13.99, $response->json()['data']['price']);
        $this->assertCount(2, $response->json()['data']['attributes']);
        $this->assertEquals('Testing Attribute 1', $response->json()['data']['attributes'][0]['name']);
        $this->assertEquals('Testing Attribute 2', $response->json()['data']['attributes'][1]['name']);
    }

    public function testApiPostCreatesProductWithoutAttributesThatDoNotExist()
    {
        $response = $this->call('POST', '/api/products/', [
            'name' => 'Testing Product',
            'price' => 13.99,
            'attributes' => [9999],
        ]);

        $response->assertStatus(201);

        $this->assertEquals('Product Created', $response->json()['message']);
        $this->assertEquals('Testing Product', $response->json()['data']['name']);
        $this->assertEquals([], $response->json()['data']['attributes']);
    }

    public function testApiPostReturns422IfNameRequiredValidationFails()
    {
        $response = $this->call('POST', '/api/products/', [
            'name' => '',
            'price' => 10.99,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name field is required.', $response->json()['errors']['name'][0]);
    }

    public function testApiPostReturns422IfNameLengthValidationFails()
    {
        $response = $this->call('POST', '/api/products/', [
            'name' => 'upF1V5uO3O3wHzZvTzWfFfwZBR6owc3OlG3E0Xxn7SSZxyOb0mRUL3HiAbQrFnbbN8HSmKz79oICn0VhlFjUqOjunqtD7ZRp0J7RCBEcwhyo840ojONap6zDjMOobwBzpxITvgyuFgt5ylC7KZy0mTUquAYeWlmIoeX3F5WIptTSDCknDAX9dCcboFwz9X5bkJsXCOiOFXamars1pwZIHCxk0yElnV4PvxK9GcOWiRJD882RfoNhYDrVdTI70mlk',
            'price' => 10.99,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name must not be greater than 255 characters.', $response->json()['errors']['name'][0]);
    }

    public function testApiPostReturns422IfNameUniqueValidationFails()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();
        
        $response = $this->call('POST', '/api/products/', [
            'name' => 'Testing Product',
            'price' => 10.99,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name has already been taken.', $response->json()['errors']['name'][0]);
    }

    public function testApiPostReturns422IfPriceNumericValidationFails()
    {
        $response = $this->call('POST', '/api/products/', [
            'name' => 'Testing Product',
            'price' => 'string',
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The price must be a number.', $response->json()['errors']['price'][0]);
    }

    public function testApiPostReturns422IfPriceMinValidationFails()
    {
        $response = $this->call('POST', '/api/products/', [
            'name' => 'Testing Product',
            'price' => -100,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The price must be between 0 and 999999.99.', $response->json()['errors']['price'][0]);
    }

    public function testApiPostReturns422IfPriceMaxValidationFails()
    {
        $response = $this->call('POST', '/api/products/', [
            'name' => 'Testing Product',
            'price' => 10000000,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The price must be between 0 and 999999.99.', $response->json()['errors']['price'][0]);
    }

    public function testApiPutUpdatesProduct()
    {
        // Create attribute
        $attribute1 = new Attribute();
        $attribute1->name = 'Testing Attribute 1';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Testing Attribute 2';
        $attribute2->save();

        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        $product->attributes()->attach([$attribute1->id]);

        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => 'Testing Product Modified',
            'price' => 12.99,
            'attributes' => [$attribute2->id, 9999],
        ]);

        $response->assertStatus(200);

        $this->assertEquals('Product Updated', $response->json()['message']);
        $this->assertEquals('Testing Product Modified', $response->json()['data']['name']);
        $this->assertCount(1, $response->json()['data']['attributes']);
        $this->assertEquals('Testing Attribute 2', $response->json()['data']['attributes'][0]['name']);
    }

    public function testApiPutUpdatesProductWithoutAttributeThatDoesNotExist()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        // Create attribute
        $attribute = new Attribute();
        $attribute->name = 'Testing Attribute 1';
        $attribute->save();

        $product->attributes()->attach([$attribute->id]);

        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => 'Testing Product Modified',
            'price' => 12.99,
            'attributes' => [9999],
        ]);

        $response->assertStatus(200);

        $this->assertEquals('Product Updated', $response->json()['message']);
        $this->assertEquals('Testing Product Modified', $response->json()['data']['name']);
        $this->assertEquals([], $response->json()['data']['attributes']);

        // Check relation
        $this->assertFalse($product->attributes()->exists($attribute->id));
    }

    public function testApiPutReturns404IfProductDoesNotExist()
    {
        $response = $this->call('PUT', '/api/products/99999');

        $response->assertStatus(404);
    }

    /*
    public function testApiPutReturns422IfValidationFails()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => '',
            'price' => '',
        ]);

        $response->assertStatus(422);
    }
    */

    public function testApiPutReturns422IfNameRequiredValidationFails()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => '',
            'price' => 10.99,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name field is required.', $response->json()['errors']['name'][0]);
    }

    public function testApiPutReturns422IfNameLengthValidationFails()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => 'upF1V5uO3O3wHzZvTzWfFfwZBR6owc3OlG3E0Xxn7SSZxyOb0mRUL3HiAbQrFnbbN8HSmKz79oICn0VhlFjUqOjunqtD7ZRp0J7RCBEcwhyo840ojONap6zDjMOobwBzpxITvgyuFgt5ylC7KZy0mTUquAYeWlmIoeX3F5WIptTSDCknDAX9dCcboFwz9X5bkJsXCOiOFXamars1pwZIHCxk0yElnV4PvxK9GcOWiRJD882RfoNhYDrVdTI70mlk',
            'price' => 10.99,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name must not be greater than 255 characters.', $response->json()['errors']['name'][0]);
    }

    public function testApiPutReturns422IfNameUniqueValidationFails()
    {
        // Create product
        $product1 = new Product();
        $product1->name = 'Testing Product 1';
        $product1->price = 11.99;
        $product1->save();

        // Create product
        $product2 = new Product();
        $product2->name = 'Testing Product 2';
        $product2->price = 13.99;
        $product2->save();
        
        $response = $this->call('PUT', '/api/products/' . $product1->id, [
            'name' => 'Testing Product 2',
            'price' => 10.99,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name has already been taken.', $response->json()['errors']['name'][0]);
    }

    public function testApiPutReturns422IfPriceNumericValidationFails()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => 'Testing Product',
            'price' => 'string',
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The price must be a number.', $response->json()['errors']['price'][0]);
    }

    public function testApiPutReturns422IfPriceMinValidationFails()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => 'Testing Product',
            'price' => -100,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The price must be between 0 and 999999.99.', $response->json()['errors']['price'][0]);
    }

    public function testApiPutReturns422IfPriceMaxValidationFails()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();
        
        $response = $this->call('PUT', '/api/products/' . $product->id, [
            'name' => 'Testing Product',
            'price' => 10000000,
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The price must be between 0 and 999999.99.', $response->json()['errors']['price'][0]);
    }

    public function testApiDeleteDeletesProduct()
    {
        // Create product
        $product = new Product();
        $product->name = 'Testing Product';
        $product->price = 11.99;
        $product->save();

        // Create attribute
        $attribute = new Attribute();
        $attribute->name = 'Testing Attribute';
        $attribute->save();

        $product->attributes()->attach([$attribute->id]);

        $response = $this->call('DELETE', '/api/products/' . $product->id);

        $response->assertStatus(200);

        $this->assertEquals('Product Deleted', $response->json()['message']);
        $this->assertEquals('Testing Product', $response->json()['data']['name']);

        // Check that ManyToMany relation was removed
        $this->assertFalse($product->attributes()->exists($attribute->id));
    }

    public function testApiDeleteReturns404IfProductDoesNotExist()
    {
        $response = $this->call('DELETE', '/api/product/9999');

        $response->assertStatus(404);
    }
}
