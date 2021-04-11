<?php
declare(strict_types=1);

namespace App\Infra\Repositories;

use App\Adapters\ParkedCarAdapter;
use App\Core\Entities\ParkedCar;
use App\Core\Repositories\ParkedCarRepository;
use App\Infra\DataBase\DataBase;
use Illuminate\Database\Eloquent\Model;

class ParkedCarRepositoryEloquent extends Model implements ParkedCarRepository
{
    protected $table = 'parked_cars';
    protected $dates = ['date'];

    public function __construct()
    {
        DataBase::bootEloquent();
        parent::__construct();
    }

    public function createParkedCar(string $code, string $plate, \DateTime $date): void
    {
        $obj = new self();
        $obj->code = $code;
        $obj->plate = $plate;
        $obj->date = $date;
        $obj->save();
    }

    public function getParkedCars(string $code): array
    {
        return $this->newQuery()->whereCode($code)->get()->toArray();
    }

    public function getParkedCarByPlate(string $code, string $plate): ParkedCar|null
    {
        $model = $this->newQuery()
            ->whereCode($code)
            ->wherePlate($plate)
            ->first();

        if($model){
            return ParkedCarAdapter::create($model->code, $model->plate, $model->date);
        }

        return null;
    }
}
