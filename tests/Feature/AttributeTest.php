<?php

namespace Tests\Feature;

use App\Models\Attribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttributeTest extends TestCase
{
    use RefreshDatabase;

    public function testApiGetReturnsAttributesList()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Testing Attribute 1';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Testing Attribute 2';
        $attribute2->save();

        $response = $this->call('GET', '/api/attributes');

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                [
                    'id' => $attribute1->id,
                    'name' => $attribute1->name,
                ],
                [
                    'id' => $attribute2->id,
                    'name' => $attribute2->name,
                ],
            ],
        ]);
    }

    public function testApiGetReturnsSingleAttributeById()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Testing Attribute 1';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Testing Attribute 2';
        $attribute2->save();

        $response = $this->call('GET', '/api/attributes/' . $attribute1->id);

        $response->assertStatus(200);

        $response->assertExactJson([
            'data' => [
                'id' => $attribute1->id,
                'name' => $attribute1->name,
            ],
        ]);
    }

    public function testApiGetReturns404IfAttributeDoesNotExist()
    {
        $response = $this->call('GET', '/api/attributes/9999');

        $response->assertStatus(404);
    }

    public function testApiPostCreatesAttribute()
    {
        $response = $this->call('POST', '/api/attributes/', [
            'name' => 'Testing Attribute'
        ]);

        $response->assertStatus(201);

        $this->assertEquals('Attribute Created', $response->json()['message']);
        $this->assertEquals('Testing Attribute', $response->json()['data']['name']);
    }

    public function testApiPostReturns422IfNameRequiredValidationFails()
    {
        $response = $this->call('POST', '/api/attributes/', [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name field is required.', $response->json()['errors']['name'][0]);
    }

    public function testApiPostReturns422IfNameFieldLengthValidationFails()
    {
        $response = $this->call('POST', '/api/attributes/', [
            'name' => 'upF1V5uO3O3wHzZvTzWfFfwZBR6owc3OlG3E0Xxn7SSZxyOb0mRUL3HiAbQrFnbbN8HSmKz79oICn0VhlFjUqOjunqtD7ZRp0J7RCBEcwhyo840ojONap6zDjMOobwBzpxITvgyuFgt5ylC7KZy0mTUquAYeWlmIoeX3F5WIptTSDCknDAX9dCcboFwz9X5bkJsXCOiOFXamars1pwZIHCxk0yElnV4PvxK9GcOWiRJD882RfoNhYDrVdTI70mlk',
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name must not be greater than 255 characters.', $response->json()['errors']['name'][0]);
    }

    public function testApiPostReturns422IfNameFieldUniqueValidationFails()
    {
        // Create attributes
        $attribute = new Attribute();
        $attribute->name = 'Testing Attribute';
        $attribute->save();

        $response = $this->call('POST', '/api/attributes/', [
            'name' => 'Testing Attribute',
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name has already been taken.', $response->json()['errors']['name'][0]);
    }

    public function testApiPutUpdatesAttribute()
    {
        // Create attribute
        $attribute = new Attribute();
        $attribute->name = 'Testing Attribute';
        $attribute->save();

        $response = $this->call('PUT', '/api/attributes/' . $attribute->id, [
            'name' => 'Testing Attribute Modified'
        ]);

        $response->assertStatus(200);

        $this->assertEquals('Attribute Updated', $response->json()['message']);
        $this->assertEquals('Testing Attribute Modified', $response->json()['data']['name']);
    }

    public function testApiPutReturns404IfAttributeDoesNotExist()
    {
        $response = $this->call('PUT', '/api/attributes/9999');

        $response->assertStatus(404);
    }

    public function testApiPutReturns422IfValidationFails()
    {
        // Create attribute
        $attribute = new Attribute();
        $attribute->name = 'Testing Attribute';
        $attribute->save();

        $response = $this->call('PUT', '/api/attributes/' . $attribute->id, [
            'name' => ''
        ]);

        $response->assertStatus(422);

        $this->assertEquals('The name field is required.', $response->json()['errors']['name'][0]);
    }

    public function testApiPutReturns422IfNameFieldLengthValidationFails()
    {
        // Create attribute
        $attribute = new Attribute();
        $attribute->name = 'Testing Attribute';
        $attribute->save();

        $response = $this->call('PUT', '/api/attributes/' . $attribute->id, [
            'name' => 'upF1V5uO3O3wHzZvTzWfFfwZBR6owc3OlG3E0Xxn7SSZxyOb0mRUL3HiAbQrFnbbN8HSmKz79oICn0VhlFjUqOjunqtD7ZRp0J7RCBEcwhyo840ojONap6zDjMOobwBzpxITvgyuFgt5ylC7KZy0mTUquAYeWlmIoeX3F5WIptTSDCknDAX9dCcboFwz9X5bkJsXCOiOFXamars1pwZIHCxk0yElnV4PvxK9GcOWiRJD882RfoNhYDrVdTI70mlk',
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name must not be greater than 255 characters.', $response->json()['errors']['name'][0]);
    }

    public function testApiPutReturns422IfNameFieldUniqueValidationFails()
    {
        // Create attributes
        $attribute1 = new Attribute();
        $attribute1->name = 'Testing Attribute 1';
        $attribute1->save();

        $attribute2 = new Attribute();
        $attribute2->name = 'Testing Attribute 2';
        $attribute2->save();

        $response = $this->call('PUT', '/api/attributes/' . $attribute1->id, [
            'name' => 'Testing Attribute 2',
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The name has already been taken.', $response->json()['errors']['name'][0]);
    }

    public function testApiDeleteDeletesAttribute()
    {
        // Create attribute
        $attribute = new Attribute();
        $attribute->name = 'Testing Attribute';
        $attribute->save();

        $response = $this->call('DELETE', '/api/attributes/' . $attribute->id);

        $response->assertStatus(200);

        $this->assertEquals('Attribute Deleted', $response->json()['message']);
        $this->assertEquals('Testing Attribute', $response->json()['data']['name']);
    }

    public function testApiDeleteReturns404IfAttributeDoesNotExist()
    {
        $response = $this->call('DELETE', '/api/attributes/9999');

        $response->assertStatus(404);
    }
}
