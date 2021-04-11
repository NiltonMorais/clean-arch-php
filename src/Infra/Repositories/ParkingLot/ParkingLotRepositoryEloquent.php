<?php
declare(strict_types=1);

namespace App\Infra\Repositories\ParkingLot;

use App\Adapters\ParkingLot\ParkingLotAdapter;
use App\Domain\ParkingLot\Entities\ParkingLot;
use App\Domain\ParkingLot\Repositories\ParkedCarRepository;
use App\Domain\ParkingLot\Repositories\ParkingLotRepository;
use App\Infra\DataBase\DataBase;
use Illuminate\Database\Eloquent\Model;

class ParkingLotRepositoryEloquent extends Model implements ParkingLotRepository
{
    protected $table = 'parking_lots';
    protected $dates = ['open_hour', 'close_hour'];

    public function __construct()
    {
        DataBase::bootEloquent();
        parent::__construct();
    }

    public function getParkingLot(string $code, ParkedCarRepository $parkedCarRepository): ParkingLot
    {
        $model = $this->newQuery()->whereCode($code)->first();

        if($model){
            $occupiedSpaces = count($parkedCarRepository->getParkedCars($code));
            return ParkingLotAdapter::create($model->code, $model->capacity, $model->open_hour->toDateTime(), $model->close_hour->toDateTime(), $occupiedSpaces);
        }

        throw new \Exception('The parking lot not found.');
    }

    public function createParkingLot(string $code, int $capacity, \DateTime $openHour, \DateTime $closeHour): void
    {
        $obj = new self();
        $obj->code = $code;
        $obj->capacity = $capacity;
        $obj->open_hour = $openHour;
        $obj->close_hour = $closeHour;
        $obj->save();
    }
}
