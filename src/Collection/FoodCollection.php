<?php

namespace App\Collection;

use App\Domain\Food;
use App\Helper\QuantityType;

class FoodCollection
{
    private array $foods = [];

    public function add(Food $food): self
    {
        $id = $food->getId();
        $foodItem = $this->getFood($id);

        if ($foodItem) {
            $foodItem->setQuantity($foodItem->getQuantity() + $food->getQuantity());
        } else {
            $this->foods[$id] = $food;
        }

        return $this;
    }

    public function list($search = null, $unit = QuantityType::GRAM): array
    {
        $result = [];

        if (!in_array($unit, QuantityType::getTypes())) {
            throw new \InvalidArgumentException('Invalid unit');
        }

        foreach ($this->foods as $food) {
            if ($search && !str_contains($food->getName(), $search)) {
                continue;
            }

            $result[] = [
                'id' => $food->getId(),
                'name' => $food->getName(),
                'type' => $food->getType(),
                'quantity' => $food->getQuantity($unit),
                'unit' => $unit,
            ];
        }

        return $result;
    }

    public function remove(int $id): self
    {
        foreach ($this->foods as $food) {
            if ($food->getId() === $id) {
                unset($this->foods[$id]);
                break;
            }
        }

        return $this;
    }

    private function getFood(int $id): ?Food
    {
        $food = null;

        foreach ($this->foods as $foodItem) {
            if ($foodItem->getId() === $id) {
                $food = $foodItem;
                break;
            }
        }

        return $food;
    }
}