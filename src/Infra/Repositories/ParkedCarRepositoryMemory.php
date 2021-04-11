<?php

namespace App\Infra\Repositories;

use App\Adapters\ParkedCarAdapter;
use App\Core\Entities\ParkedCar;
use App\Core\Repositories\ParkedCarRepository;;

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
