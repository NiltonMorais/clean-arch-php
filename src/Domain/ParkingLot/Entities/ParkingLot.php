<?php
declare(strict_types=1);

namespace App\Domain\ParkingLot\Entities;

class ParkingLot
{
    public string $code;
    public int $capacity;
    public \DateTime $openHour;
    public \DateTime $closeHour;
    public int $occupiedSpaces;

    public function __construct(string $code, int $capacity, \DateTime $openHour, \DateTime $closeHour, int $occupiedSpaces = 0)
    {
        $this->code = $code;
        $this->capacity = $capacity;
        $this->openHour = $openHour;
        $this->closeHour = $closeHour;
        $this->occupiedSpaces = $occupiedSpaces;
    }

    public function isOpen(\DateTime $date): bool
    {
        return ($date->format('H:i') >= $this->openHour->format('H:i') && $date->format('H:i') <= $this->closeHour->format('H:i'));
    }

    public function isFull(): bool
    {
        return $this->occupiedSpaces >= $this->capacity;
    }
}
