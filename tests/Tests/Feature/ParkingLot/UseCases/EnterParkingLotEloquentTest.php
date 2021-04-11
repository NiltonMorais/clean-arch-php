<?php
declare(strict_types=1);

namespace Tests\Feature\ParkingLot\UseCases;

use App\Domain\ParkingLot\Repositories\ParkedCarRepository;
use App\Domain\ParkingLot\Repositories\ParkingLotRepository;
use App\Domain\ParkingLot\UseCases\EnterParkingLotUseCase;
use App\Infra\Repositories\ParkingLot\ParkedCarRepositoryEloquent;
use App\Infra\Repositories\ParkingLot\ParkingLotRepositoryEloquent;
use PHPUnit\Framework\TestCase;

final class EnterParkingLotEloquentTest extends TestCase
{
    private ParkingLotRepository $parkingLotRepository;
    private ParkedCarRepository $parkedCarRepository;

    public function __construct()
    {
        parent::__construct();

        $this->parkingLotRepository = new ParkingLotRepositoryEloquent();
        $this->parkedCarRepository = new ParkedCarRepositoryEloquent();
    }

    public function testEnterParkingLotWithSuccess(): void
    {
        $parkingLotData = [
            'code' => 'test_'.rand(),
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository, $this->parkedCarRepository);
        $parkingLotBeforeEnter = $this->parkingLotRepository->getParkingLot($parkingLotData['code'], $this->parkedCarRepository);
        $this->assertEquals(0, $parkingLotBeforeEnter->occupiedSpaces);

        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1232', new \DateTime('2021-05-10T18:00:00'));

        $parkingLotAfterEnter = $this->parkingLotRepository->getParkingLot($parkingLotData['code'], $this->parkedCarRepository);
        $this->assertEquals(1, $parkingLotAfterEnter->occupiedSpaces);
    }

    public function testParkingLotIsClosed(): void
    {
        $parkingLotData = [
            'code' => 'test_'.rand(),
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository, $this->parkedCarRepository);

        $this->expectExceptionMessage('The parking lot is closed.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1234', new \DateTime('2021-05-10T19:00:00'));
    }

    public function testParkingLotHasNotOpened(): void
    {
        $parkingLotData = [
            'code' => 'test_'.rand(),
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository, $this->parkedCarRepository);

        $this->expectExceptionMessage('The parking lot is closed.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1235', new \DateTime('2021-05-10T08:00:00'));
    }

    public function testParkingLotNotFound(): void
    {
        $parkingLotData = [
            'code' => 'test_'.rand(),
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository, $this->parkedCarRepository);

        $this->expectExceptionMessage('The parking lot not found.');
        $enterParkingLotUseCase->execute('não existe', 'ABC-1236', new \DateTime('2021-05-10T08:00:00'));
    }

    public function testParkingLotIsFull(): void
    {
        $parkingLotData = [
            'code' => 'test_fll'.rand(),
            'capacity' => 2,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository, $this->parkedCarRepository);
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'LSX-1091', new \DateTime('2021-05-10T10:00:00'));
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'APQ-0901', new \DateTime('2021-05-10T11:00:00'));

        $this->expectExceptionMessage('The parking lot is full.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'KJS-8789', new \DateTime('2021-05-10T13:00:00'));
    }

    public function testCarAlreadyParked(): void
    {
        $parkingLotData = [
            'code' => 'test_'.rand(),
            'capacity' => 2,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository, $this->parkedCarRepository);
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1238', new \DateTime('2021-05-10T10:00:00'));

        $this->expectExceptionMessage('This car already parked.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1238', new \DateTime('2021-05-10T10:00:00'));
    }
}
