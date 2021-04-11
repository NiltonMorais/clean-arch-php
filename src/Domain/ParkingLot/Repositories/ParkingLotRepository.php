<?php
declare(strict_types=1);

namespace App\Domain\ParkingLot\Repositories;

use App\Domain\ParkingLot\Entities\ParkingLot;

interface ParkingLotRepository
{
    public function getParkingLot(string $code, ParkedCarRepository $parkedCarRepository): ParkingLot;
    public function createParkingLot(string $code, int $capacity, \DateTime $openHour, \DateTime $closeHour): void;
}
