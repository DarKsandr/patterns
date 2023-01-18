<?php

namespace App\Structural\Adapter;

/**
 * Целевой класс объявляет интерфейс, с которым может работать клиентский код.
 */

class Target
{
    public function request(): string
    {
        return "Цель: поведение цели по умолчанию.";
    }
}

/**
 * Адаптируемый класс содержит некоторое полезное поведение, но его интерфейс
 * несовместим с существующим клиентским кодом. Адаптируемый класс нуждается в
 * некоторой доработке, прежде чем клиентский код сможет его использовать.
 */

class Adaptee
{
    public function specificRequest(): string
    {
        return "eetpadA eht fo roivaheb laicepS";
    }
}

/**
 * Адаптер делает интерфейс Адаптируемого класса совместимым с целевым
 * интерфейсом.
 */

class Adapter extends Target
{
    private $adaptee;

    public function __construct(Adaptee $adaptee)
    {
        $this->adaptee = $adaptee;
    }
    public function request(): string
    {
        return "Adapter: (ПЕРЕВОД) " . strrev($this->adaptee->specificRequest());
    }
}

/**
 * Клиентский код поддерживает все классы, использующие целевой интерфейс.
 */

function clientCode(Target $target)
{
    echo $target->request();
}

echo "Клиент: Я прекрасно могу работать с объектами Target:<br>";
$target = new Target;
clientCode($target);
echo "<br><br>";

$adaptee = new Adaptee;
echo "Клиент: Класс Adaptee имеет странный интерфейс. Видишь ли, я не понимаю:<br>";
echo "Adaptee: " . $adaptee->specificRequest();
echo "<br><br>";

echo "Клиент: Но я могу работать с ним через Адаптер<br>";
$adapter = new Adapter($adaptee);
clientCode($adapter);
