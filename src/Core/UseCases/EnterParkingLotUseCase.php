<?php
declare(strict_types=1);

namespace App\Core\UseCases;

use App\Core\Entities\ParkedCar;
use App\Core\Repositories\ParkedCarRepository;
use App\Core\Repositories\ParkingLotRepository;

class EnterParkingLotUseCase
{

    private ParkingLotRepository $parkingLotRepository;
    private ParkedCarRepository $parkedCarRepository;

    public function __construct(ParkingLotRepository $parkingLotRepository, ParkedCarRepository $parkedCarRepository)
    {
        $this->parkingLotRepository = $parkingLotRepository;
        $this->parkedCarRepository = $parkedCarRepository;
    }

    public function execute(string $code, string $plate, \DateTime $date): void
    {
        $parkingLot = $this->parkingLotRepository->getParkingLot($code, $this->parkedCarRepository);
        $parkedCar = new ParkedCar($code, $plate, $date);

        if(!$parkingLot->isOpen($parkedCar->date)){
            throw new \Exception('The parking lot is closed.');
        }

        if($parkingLot->isFull()){
            throw new \Exception('The parking lot is full.');
        }

        if($this->parkedCarRepository->getParkedCarByPlate($code, $plate)){
            throw new \Exception('This car already parked.');
        }

        $this->parkedCarRepository->createParkedCar($parkedCar->code, $parkedCar->plate, $parkedCar->date);
    }
}
