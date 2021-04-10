<?php
declare(strict_types=1);

namespace App\Core\UseCases;

use App\Core\Entities\ParkedCar;
use App\Core\Repositories\ParkingLotRepository;

class EnterParkingLotUseCase
{
    /**
     * @var ParkingLotRepository
     */
    private ParkingLotRepository $parkingLotRepository;

    public function __construct(ParkingLotRepository $parkingLotRepository)
    {
        $this->parkingLotRepository = $parkingLotRepository;
    }

    public function execute(string $code, string $plate, \DateTime $date): void
    {
        $parkingLot = $this->parkingLotRepository->getParkingLot($code);
        $parkedCar = new ParkedCar($code, $plate, $date);

        if(!$parkingLot->isOpen($parkedCar->date)){
            throw new \Exception('The parking lot is closed.');
        }

        if($parkingLot->isFull()){
            throw new \Exception('The parking lot is full.');
        }

        if($this->parkingLotRepository->getParkedCarByPlate($code, $plate)){
            throw new \Exception('This car already parked.');
        }

        $this->parkingLotRepository->createParkedCar($parkedCar->code, $parkedCar->plate, $parkedCar->date);
    }
}
