<?php

namespace App\Creational\FactoryMethod;

/**
 * Класс Создатель объявляет фабричный метод, который должен возвращать объект
 * класса Продукт. Подклассы Создателя обычно предоставляют реализацию этого
 * метода.
 */

abstract class Creator
{
    /**
     * Обратите внимание, что Создатель может также обеспечить реализацию
     * фабричного метода по умолчанию.
     */
    abstract public function factoryMethod(): Product;

    /**
     * Также заметьте, что, несмотря на название, основная обязанность Создателя
     * не заключается в создании продуктов. Обычно он содержит некоторую базовую
     * бизнес-логику, которая основана на объектах Продуктов, возвращаемых
     * фабричным методом. Подклассы могут косвенно изменять эту бизнес-логику,
     * переопределяя фабричный метод и возвращая из него другой тип продукта.
     */

    public function someOperator(): string
    {
        // Вызываем фабричный метод, чтобы получить объект-продукт.
        $product = $this->factoryMethod();
        // Далее, работаем с этим продуктом.
        $result = "Создатель: тот же код создателя только что работал с " . $product->operation();

        return $result;
    }
}

/**
 * Конкретные Создатели переопределяют фабричный метод для того, чтобы изменить
 * тип результирующего продукта.
 */

class CreatorFirst extends Creator
{
    /**
     * Обратите внимание, что сигнатура метода по-прежнему использует тип
     * абстрактного продукта, хотя фактически из метода возвращается конкретный
     * продукт. Таким образом, Создатель может оставаться независимым от
     * конкретных классов продуктов.
     */

    public function factoryMethod(): Product
    {
        return new ProductFirst;
    }
}

class CreatorSecond extends Creator
{
    public function factoryMethod(): Product
    {
        return new ProductSecond;
    }
}

/**
 * Интерфейс Продукта объявляет операции, которые должны выполнять все
 * конкретные продукты.
 */
interface Product
{
    public function operation(): string;
}

/**
 * Конкретные Продукты предоставляют различные реализации интерфейса Продукта.
 */
class ProductFirst implements Product
{
    public function operation(): string
    {
        return "{Результат ProductFirst}";
    }
}

class ProductSecond implements Product
{
    public function operation(): string
    {
        return "{Результат ProductSecond}";
    }
}

/**
 * Клиентский код работает с экземпляром конкретного создателя, хотя и через его
 * базовый интерфейс. Пока клиент продолжает работать с создателем через базовый
 * интерфейс, вы можете передать ему любой подкласс создателя.
 */

function clientCode(Creator $creator)
{
    echo "Клиент: Я не знаю класс создателя, но он все еще работает.<br>" . $creator->someOperator();
}

/**
 * Приложение выбирает тип создателя в зависимости от конфигурации или среды.
 */

echo "Приложение: запущено с CreatorFirst.<br>";
clientCode(new CreatorFirst);
echo "<br><br>";

echo "Приложение: запущено с CreatorSecond.<br>";
clientCode(new CreatorSecond);