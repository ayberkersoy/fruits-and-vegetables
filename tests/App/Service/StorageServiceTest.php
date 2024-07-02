<?php

namespace App\Tests\App\Service;

use App\Helper\FoodType;
use App\Helper\QuantityType;
use App\Service\StorageService;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    public function testReceivingRequest(): void
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $this->assertNotEmpty($storageService->getRequest());
        $this->assertIsString($storageService->getRequest());
    }

    public function testAddExistingFood()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $food = [
            'id' => 14,
            'name' => 'Banana',
            'type' => FoodType::FRUIT,
            'quantity' => 1,
            'unit' => QuantityType::KILOGRAM
        ];

        $storageService->add($food);

        $result = $storageService->list('Banana');

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result[FoodType::FRUIT]);
        $this->assertEquals(101000.0, $result[FoodType::FRUIT][0]['quantity']);
    }

    public function testAddNewFood()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $food = [
            'id' => 21,
            'name' => 'Strawberry',
            'type' => FoodType::FRUIT,
            'quantity' => 5,
            'unit' => QuantityType::KILOGRAM
        ];

        $storageService->add($food);

        $result = $storageService->list('Strawberry');

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result[FoodType::FRUIT]);
        $this->assertEquals(5000.0, $result[FoodType::FRUIT][0]['quantity']);
    }

    public function testAddFoodInvalidFoodType()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $food = [
            'id' => 21,
            'name' => 'Strawberry',
            'type' => 'invalid',
            'quantity' => 5,
            'unit' => QuantityType::KILOGRAM
        ];

        $this->expectException(\InvalidArgumentException::class);
        $storageService->add($food);
    }

    public function testAddFoodInvalidUnit()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $food = [
            'id' => 21,
            'name' => 'Strawberry',
            'type' => FoodType::FRUIT,
            'quantity' => 5,
            'unit' => 'invalid'
        ];

        $this->expectException(\InvalidArgumentException::class);
        $storageService->add($food);
    }

    public function testAddFoodInvalidQuantity()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $food = [
            'id' => 14,
            'name' => 'Banana',
            'type' => FoodType::FRUIT,
            'quantity' => -1,
            'unit' => QuantityType::KILOGRAM
        ];

        $this->expectException(\InvalidArgumentException::class);
        $storageService->add($food);
    }

    public function testList()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $result = $storageService->list();

        $this->assertNotEmpty($result);
        $this->assertCount(10, $result[FoodType::FRUIT]);
        $this->assertCount(10, $result[FoodType::VEGETABLE]);
    }

    public function testListSearch()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $result = $storageService->list('Banana');

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result[FoodType::FRUIT]);
        $this->assertCount(0, $result[FoodType::VEGETABLE]);
    }

    public function testListUnit()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $result = $storageService->list('Banana');

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result[FoodType::FRUIT]);
        $this->assertEquals(100000.0, $result[FoodType::FRUIT][0]['quantity']);
        $this->assertEquals(QuantityType::GRAM, $result[FoodType::FRUIT][0]['unit']);

        $result = $storageService->list('Banana', QuantityType::KILOGRAM);

        $this->assertEquals(100.0, $result[FoodType::FRUIT][0]['quantity']);
        $this->assertEquals(QuantityType::KILOGRAM, $result[FoodType::FRUIT][0]['unit']);
    }

    public function testListInvalidQuantity()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $this->expectException(\InvalidArgumentException::class);
        $storageService->list(null, 'invalid');
    }

    public function testRemove()
    {
        $request = file_get_contents('request.json');

        $storageService = new StorageService($request);

        $storageService->remove(1);

        $result = $storageService->list();

        $this->assertNotEmpty($result);
        $this->assertCount(10, $result[FoodType::FRUIT]);
        $this->assertCount(9, $result[FoodType::VEGETABLE]);
    }
}
