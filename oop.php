<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy
 * Date: 18.03.2018
 * Time: 14:12
 */
//Задание #1. Практика с ООП
//Опишите несложный, но вполне конкретный автомобиль с помощью ООП.
//1. Автомобиль должен иметь функцию движения с заданным расстоянием и
//скоростью и направлением. Например “Автомобиль->Двигаться(200 метров, 10
//м\с, вперед)”. При начале движения автомобиля:
//a. вы включаете двигатель
//b. Включаете нужную передачу (вперед\назад)
//c. двигаетесь в соответствии с параметрами двигателя, при необходимости
//включая охлаждения.
//d. выключаете двигатель и коробку передач
//2. Ваш автомобиль должен иметь двигатель. Двигатель должен иметь функцию
//включения, выключения и охлаждения. Считается что двигатель мгновенно
//разгоняется до указанной скорости и двигается равномерно все это время.
//Охлаждение может быть включено ТОЛЬКО двигателем. Параметры двигателя:
//a. Кол-во лошадиных сил. 1 лошадиная сила позволяет развивать 2м\с.
//b. температура. Каждые пройденные 10 метров повышают температуру на
//5 градусов. При достижении 90 градусов, двигатель нужно охладить с
//помощью включения вентилятора. Каждое включение вентилятора
//охлаждает на 10 градусов.
//3. Ваш автомобиль может поддерживать одну из двух коробок передач -
//автоматическую или ручную. Необязательно​: добавить нейтральную передачу
//a. Автоматическая имеет два состояния:
//i. Режим езды вперед
//ii. Режим езды назад
//b. Ручная коробка передач имеет:
//i. передачи от 1 до 2. При скорости от 0 до 20 используется
//передача №1, в противном случае передача №2.
//1. Т.к. характеристика разгона в данной задаче отсутствует - как только передана скорость
//больше 20 - всегда используется передача №2. Переключение между передачами внутри
//программы не требуется
//ii. задняя
//4. Остальные опции автомобиля остаются на ваши усмотрение, например, вы
//можете установить сиденье для водителя или руль.


trait Transmission
{
    function backward()
    {
        echo "Включен режим езды назад" . PHP_EOL;
    }
}

trait TransmissionAuto
{
    use Transmission;

    function forwardAuto()
    {
        echo "Используется автоматическая коробка передач, едем вперёд" . PHP_EOL;
    }
}

trait TransmissionManual
{
    use Transmission;

    function forwardManual($speed)
    {
        if ($speed <= 20) {
            echo "Используется первая передача" . PHP_EOL;
        } else {
            echo "Используется вторая передача" . PHP_EOL;
        }
    }

}

trait Engine
{
    public $distance;
    public $horse;
    public $temperature;

    function __construct($distance, $horse)
    {
        $this->distance = $distance;
        $this->horse = $horse;
        $this->temperature = 0;
    }

    function horsepower()
    {
        $speed = $this->horse / 2;
        echo "Количество лошидинных сид $speed" . PHP_EOL;
        return $speed;
    }

    function cooling()
    {
        $this->temperature -= 10;
        echo "Включился вентилятор температура сбросилась до $this->temperature" . PHP_EOL;
    }

    function changeTemperature()
    {
        $this->temperature += 5;
        echo "Температура двигателя $this->temperature" . PHP_EOL;
    }

    function onEngine($distanceMove)
    {
        $meter = 10;
        if ($distanceMove >= $meter) {
            $this->changeTemperature();
            if ($this->temperature >= 90) {
                $this->cooling();
            }
            $meter += 10;
        } else {
            echo 'Двигаетль включён' . PHP_EOL;
        }
    }

    function offEngine()
    {
        echo 'Двигаетль выключен' . PHP_EOL;
    }

}

class Car
{
    use Engine;
    public $distance;
    public $horse;

    public function __construct($distance, $horse)
    {
        $this->distance = $distance;
        $this->horse = $horse;
    }


    function move()
    {
        $distanceMove = 0;
        $this->onEngine($distanceMove);
        $speed = $this->horsepower();
        echo "Скорость автомобиля $speed" . PHP_EOL;
        while (true) {
            $distanceMove = $distanceMove + $speed;
            echo "Проехали $distanceMove метров" . PHP_EOL;
            $this->onEngine($distanceMove);
            if ($this->distance - $distanceMove < $speed) {
                echo "Поездка закончилась" . PHP_EOL;
                break;
            }
        }
        $this->offEngine();
    }
}

class NivaAuto extends Car
{
    use TransmissionAuto;
    public $side;

    function __construct($distance, $speed, $side)
    {
        parent::__construct($distance, $speed);
        $this->side = $side;
        if ($side == 1) {
            $this->forwardAuto();
        } elseif ($side == 2) {
            $this->backward();
        } else{
            throw new Exception('Ошибка в выборе направления');
        }
    }

}

class NivaManual extends Car
{
    use TransmissionManual;
    public $side;

    function __construct($distance, $speed, $side)
    {
        parent::__construct($distance, $speed);
        $this->side = $side;
        if ($side == 1) {
            $this->forwardManual($speed);
        } elseif ($side == 2) {
            $this->backward();
        }
    }

}

interface Direction
{
    const FORWARD = 1;
    const BACKWARD = 2;
}

$nivaA = new NivaAuto(200, 20, Direction::FORWARD);
$nivaA->move();
$nivaM = new NivaManual(200, 20, Direction::FORWARD);
$nivaM->move();