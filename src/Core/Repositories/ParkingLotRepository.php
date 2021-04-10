<?php

namespace App\Core\Repositories;

use App\Core\Entities\ParkedCar;
use App\Core\Entities\ParkingLot;

interface ParkingLotRepository
{
    public function getParkingLot(string $code): ParkingLot;
    public function createParkedCar(string $code, string $plate, \DateTime $date): void;
    public function createParkingLot(string $code, int $capacity, \DateTime $openHour, \DateTime $closeHour): void;
    public function getParkedCars(string $code): array;
    public function getParkedCarByPlate(string $code, string $plate): ParkedCar|null;
}
