<?php

namespace App\Infra\Repositories;

use App\Adapters\ParkingLotAdapter;
use App\Core\Entities\ParkingLot;
use App\Core\Repositories\ParkedCarRepository;
use App\Core\Repositories\ParkingLotRepository;

class ParkingLotRepositoryMemory implements ParkingLotRepository
{
    public array $parkingLots = [];

    public function getParkingLot(string $code, ParkedCarRepository $parkedCarRepository): ParkingLot
    {
        foreach($this->parkingLots as $parkingLotData){
            if($parkingLotData['code'] === $code){
                $occupiedSpaces = count($parkedCarRepository->getParkedCars($code));
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
}
