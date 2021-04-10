<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Core\UseCases\EnterParkingLotUseCase;
use App\Infra\Repositories\ParkingLotRepositoryMemory;
use PHPUnit\Framework\TestCase;

final class EnterParkingLotTest extends TestCase
{
    private ParkingLotRepositoryMemory $parkingLotRepository;

    public function __construct()
    {
        parent::__construct();

        $this->parkingLotRepository = new ParkingLotRepositoryMemory();
    }

    public function testEnterParkingLotWithSuccess(): void
    {
        $parkingLotData = [
            'code' => 'shopping',
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository);
        $parkingLotBeforeEnter = $this->parkingLotRepository->getParkingLot($parkingLotData['code']);
        $this->assertEquals(0, $parkingLotBeforeEnter->occupiedSpaces);

        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1232', new \DateTime('2021-05-10T18:00:00'));

        $parkingLotAfterEnter = $this->parkingLotRepository->getParkingLot($parkingLotData['code']);
        $this->assertEquals(1, $parkingLotAfterEnter->occupiedSpaces);
    }

    public function testParkingLotIsClosed(): void
    {
        $parkingLotData = [
            'code' => 'shopping',
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository);

        $this->expectExceptionMessage('The parking lot is closed.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1234', new \DateTime('2021-05-10T19:00:00'));
    }

    public function testParkingLotHasNotOpened(): void
    {
        $parkingLotData = [
            'code' => 'shopping',
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository);

        $this->expectExceptionMessage('The parking lot is closed.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1235', new \DateTime('2021-05-10T08:00:00'));
    }

    public function testParkingLotNotFound(): void
    {
        $parkingLotData = [
            'code' => 'shopping',
            'capacity' => 3,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository);

        $this->expectExceptionMessage('The parking lot not found.');
        $enterParkingLotUseCase->execute('não existe', 'ABC-1236', new \DateTime('2021-05-10T08:00:00'));
    }

    public function testParkingLotIsFull(): void
    {
        $parkingLotData = [
            'code' => 'shopping',
            'capacity' => 2,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository);
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1237', new \DateTime('2021-05-10T10:00:00'));
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'LKO-3218', new \DateTime('2021-05-10T11:00:00'));

        $this->expectExceptionMessage('The parking lot is full.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'KJS-8789', new \DateTime('2021-05-10T13:00:00'));
    }

    public function testCarAlreadyParked(): void
    {
        $parkingLotData = [
            'code' => 'shopping',
            'capacity' => 2,
            'open_hour' => new \DateTime('2021-04-10T09:00:00'),
            'close_hour' => new \DateTime('2021-04-10T18:00:00')
        ];

        $this->parkingLotRepository->createParkingLot($parkingLotData['code'], $parkingLotData['capacity'], $parkingLotData['open_hour'], $parkingLotData['close_hour']);

        $enterParkingLotUseCase = new EnterParkingLotUseCase($this->parkingLotRepository);
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1238', new \DateTime('2021-05-10T10:00:00'));

        $this->expectExceptionMessage('This car already parked.');
        $enterParkingLotUseCase->execute($parkingLotData['code'], 'ABC-1238', new \DateTime('2021-05-10T10:00:00'));
    }
}
