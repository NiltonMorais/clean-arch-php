<?php
declare(strict_types=1);

namespace App\Adapters;

use App\Core\Entities\ParkingLot;

class ParkingLotAdapter
{
    public static function create(string $code, int $capacity, \DateTime $openHour, \DateTime $closeHour, int $occupiedSpaces): ParkingLot
    {
        return new ParkingLot($code, $capacity, $openHour, $closeHour, $occupiedSpaces);
    }
}
