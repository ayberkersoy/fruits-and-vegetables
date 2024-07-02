<?php

namespace App\Domain;

use App\Helper\FoodType;
use App\Helper\QuantityType;

class Food
{
    public function __construct(
        private int $id,
        private string $name,
        private string $type,
        private float $quantity,
        private string $unit,
    )
    {
        if (!in_array($this->type, FoodType::getTypes())) {
            throw new \InvalidArgumentException('Invalid type');
        }

        if (!in_array($this->unit, QuantityType::getTypes())) {
            throw new \InvalidArgumentException('Invalid unit');
        }

        if ($this->quantity <= 0) {
            throw new \InvalidArgumentException('Invalid quantity');
        }

        if ($this->unit === QuantityType::KILOGRAM) {
            $this->quantity = $this->quantity * 1000;
            $this->unit = QuantityType::GRAM;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuantity($unit = QuantityType::GRAM): float
    {
        if ($unit === QuantityType::KILOGRAM) {
            return $this->quantity / 1000;
        }

        return $this->quantity;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function setQuantity($quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}