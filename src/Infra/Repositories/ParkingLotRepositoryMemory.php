<?php

namespace App\Infra\Repositories;

use App\Adapters\ParkedCarAdapter;
use App\Adapters\ParkingLotAdapter;
use App\Core\Entities\ParkedCar;
use App\Core\Entities\ParkingLot;
use App\Core\Repositories\ParkingLotRepository;

class ParkingLotRepositoryMemory implements ParkingLotRepository
{
    public array $parkingLots = [];
    public array $parkedCars = [];

    public function getParkingLot(string $code): ParkingLot
    {
        foreach($this->parkingLots as $parkingLotData){
            if($parkingLotData['code'] === $code){
                $occupiedSpaces = count($this->parkedCars);
                $parkingLot = ParkingLotAdapter::create($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour'], $occupiedSpaces);
                return $parkingLot;
            }
        }

        throw new \Exception('The parking lot not found.');
    }

    public function createParkingLot(string $code, int $capacity, \DateTime $openHour, \DateTime $closeHour): void
    {
        $this->parkingLots[] = [
            'code' => $code,
            'capacity' => $capacity,
            'open_hour' => $openHour,
            'close_hour' => $closeHour
        ];
    }

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
