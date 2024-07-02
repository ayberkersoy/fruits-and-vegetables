<?php

namespace App\Service;

use App\Collection\FoodCollection;
use App\Domain\Food;
use App\Helper\FoodType;
use App\Helper\QuantityType;

class StorageService
{
    protected string $request = '';
    protected array $data = [];

    public function __construct(
        string $request
    )
    {
        $this->request = $request;
        $foods = json_decode($request, true);

        foreach (FoodType::getTypes() as $type) {
            $this->data[$type] = new FoodCollection();
        }

        foreach ($foods as $food) {
            $foodItem = new Food(
                $food['id'],
                $food['name'],
                $food['type'],
                $food['quantity'],
                $food['unit']
            );

            $this->data[$food['type']]->add($foodItem);
        }
    }

    public function getRequest(): string
    {
        return $this->request;
    }

    public function list(?string $search = null, string $unit = QuantityType::GRAM): array
    {
        $result = [];

        if (!in_array($unit, QuantityType::getTypes())) {
            throw new \InvalidArgumentException('Invalid unit');
        }

        foreach ($this->data as $type => $collection) {
            $result[$type] = $collection->list($search, $unit);
        }

        return $result;
    }

    public function add(array $food): void
    {
        $foodItem = new Food(
            $food['id'],
            $food['name'],
            $food['type'],
            $food['quantity'],
            $food['unit']
        );

        $this->data[$food['type']]->add($foodItem);
    }

    public function remove($id): void
    {
        foreach ($this->data as $datum) {
            $datum->remove($id);
        }
    }
}
