<?php
declare(strict_types=1);

namespace App\Adapters\ParkingLot;

use App\Domain\ParkingLot\Entities\ParkedCar;

class ParkedCarAdapter
{
    public static function create(string $code, string $plate, \DateTime $date): ParkedCar
    {
        return new ParkedCar($code, $plate, $date);
    }
}
