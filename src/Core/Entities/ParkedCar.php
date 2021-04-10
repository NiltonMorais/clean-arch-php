<?php
declare(strict_types=1);

namespace App\Core\Entities;

class ParkedCar
{
    public string $code;
    public string $plate;
    public \DateTime $date;

    public function __construct(string $code, string $plate, \DateTime $date)
    {
        if(!preg_match('/[A-Z]{3}-[0-9]{4}/', $plate)){
            throw new \Exception('Invalid plate');
        }

        $this->code = $code;
        $this->plate = $plate;
        $this->date = $date;
    }
}
