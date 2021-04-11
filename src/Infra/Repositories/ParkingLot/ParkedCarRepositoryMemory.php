<?php

namespace App\Infra\Repositories\ParkingLot;

use App\Adapters\ParkingLot\ParkedCarAdapter;
use App\Domain\ParkingLot\Entities\ParkedCar;
use App\Domain\ParkingLot\Repositories\ParkedCarRepository;;

class ParkedCarRepositoryMemory implements ParkedCarRepository
{
    public array $parkedCars = [];

    public function createParkedCar(string $code, string $plate, \DateTime $date): void
    {
        $this->parkedCars[] = [
            'code' => $code,
            'plate' => $plate,
            'date' => $date
        ];
    }

    /**
     * @param string $code
     * @return ParkedCar[]
     */
    public function getParkedCars(string $code): array
    {
        $parkedCarsFound = [];

        foreach ($this->parkedCars as $parkedCar){
            if($parkedCar['code'] === $code){
                $parkedCarsFound[] = ParkedCarAdapter::create($parkedCar['code'], $parkedCar['plate'], $parkedCar['date']);
            }
        }

        return $parkedCarsFound;
    }

    public function getParkedCarByPlate(string $code, string $plate): ParkedCar|null
    {
        foreach ($this->parkedCars as $parkedCar){
            if($parkedCar['code'] === $code && $parkedCar['plate'] === $plate){
                return ParkedCarAdapter::create($parkedCar['code'], $parkedCar['plate'], $parkedCar['date']);
            }
        }

        return null;
    }
}
