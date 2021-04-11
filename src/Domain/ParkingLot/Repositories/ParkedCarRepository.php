<?php
declare(strict_types=1);

namespace App\Domain\ParkingLot\Repositories;

use App\Domain\ParkingLot\Entities\ParkedCar;

interface ParkedCarRepository
{
    public function createParkedCar(string $code, string $plate, \DateTime $date): void;
    public function getParkedCars(string $code): array;
    public function getParkedCarByPlate(string $code, string $plate): ParkedCar|null;
}
