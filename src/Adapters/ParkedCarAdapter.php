<?php
declare(strict_types=1);

namespace App\Adapters;

use App\Core\Entities\ParkedCar;

class ParkedCarAdapter
{
    public static function create(string $code, string $plate, \DateTime $date): ParkedCar
    {
        return new ParkedCar($code, $plate, $date);
    }
}
