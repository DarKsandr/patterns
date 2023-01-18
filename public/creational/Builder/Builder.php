<?php

namespace App\Creational\Builder;

/**
 * Интерфейс Строителя объявляет создающие методы для различных частей объектов
 * Продуктов.
 */
interface Builder
{
    public function producePartA(): Builder;
    public function producePartB(): Builder;
    public function producePartC(): Builder;
}

/**
 * Классы Конкретного Строителя следуют интерфейсу Строителя и предоставляют
 * конкретные реализации шагов построения. Ваша программа может иметь несколько
 * вариантов Строителей, реализованных по-разному.
 */


class ConcreteBuilder1 implements Builder
{
    private $product;
    /**
     * Новый экземпляр строителя должен содержать пустой объект продукта,
     * который используется в дальнейшей сборке.
     */

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->product = new Product1();
    }

    /**
     * Все этапы производства работают с одним и тем же экземпляром продукта.
     */

    public function producePartA(): ConcreteBuilder1
    {
        $this->product->parts[] = "PartA1";
        return $this;
    }

    public function producePartB(): ConcreteBuilder1
    {
        $this->product->parts[] = "PartB1";
        return $this;
    }

    public function producePartC(): ConcreteBuilder1
    {
        $this->product->parts[] = "PartC1";
        return $this;
    }

    /**
     * Конкретные Строители должны предоставить свои собственные методы
     * получения результатов. Это связано с тем, что различные типы строителей
     * могут создавать совершенно разные продукты с разными интерфейсами.
     * Поэтому такие методы не могут быть объявлены в базовом интерфейсе
     * Строителя (по крайней мере, в статически типизированном языке
     * программирования). Обратите внимание, что PHP является динамически
     * типизированным языком, и этот метод может быть в базовом интерфейсе.
     * Однако мы не будем объявлять его здесь для ясности.
     *
     * Как правило, после возвращения конечного результата клиенту, экземпляр
     * строителя должен быть готов к началу производства следующего продукта.
     * Поэтому обычной практикой является вызов метода сброса в конце тела
     * метода getProduct. Однако такое поведение не является обязательным, вы
     * можете заставить своих строителей ждать явного запроса на сброс из кода
     * клиента, прежде чем избавиться от предыдущего результата.
     */

    public function getProduct(): Product1
    {
        $result = $this->product;
        $this->reset();
        return $result;
    }
}

/**
 * Имеет смысл использовать паттерн Строитель только тогда, когда ваши продукты
 * достаточно сложны и требуют обширной конфигурации.
 *
 * В отличие от других порождающих паттернов, различные конкретные строители
 * могут производить несвязанные продукты. Другими словами, результаты различных
 * строителей могут не всегда следовать одному и тому же интерфейсу.
 */

class Product1
{
    public $parts = [];

    public function listParts(): void
    {
        echo "Части продукта: " . implode(', ', $this->parts) . "<br><br>";
    }
}

/**
 * Директор отвечает только за выполнение шагов построения в определённой
 * последовательности. Это полезно при производстве продуктов в определённом
 * порядке или особой конфигурации. Строго говоря, класс Директор необязателен,
 * так как клиент может напрямую управлять строителями.
 */

class Director
{
    private $builder;

    public function setBuilder(Builder $builder): Director
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * Директор может строить несколько вариаций продукта, используя одинаковые
     * шаги построения.
     */

    public function buildMinimalViableProduct(): Builder
    {
        $this->builder->producePartA();
        return $this->builder;
    }

    public function buildFullFeaturedProduct(): Builder
    {
        $this->builder->producePartA()->producePartB()->producePartC();
        return $this->builder;
    }
}

/**
 * Клиентский код создаёт объект-строитель, передаёт его директору, а затем
 * инициирует процесс построения. Конечный результат извлекается из объекта-
 * строителя.
 */

function clientCode(Director $director)
{
    $builder = new ConcreteBuilder1;
    $director->setBuilder($builder);

    echo "Стандартный базовый продукт:<br>";
    $director->buildMinimalViableProduct();
    $builder->getProduct()->listParts();

    echo "Стандартный полнофункциональный продукт:<br>";
    $director->buildFullFeaturedProduct();
    $builder->getProduct()->listParts();

    // Помните, что паттерн Строитель можно использовать без класса Директор.
    echo "Пользовательский продукт:<br>";
    $builder->producePartA()->producePartC()->getProduct()->listParts();
}

$director = new Director();
clientCode($director);
