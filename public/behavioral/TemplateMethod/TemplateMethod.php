<?php

namespace App\Behavioral\TemplateMethod;

/**
 * Абстрактный Класс определяет шаблонный метод, содержащий скелет некоторого
 * алгоритма, состоящего из вызовов (обычно) абстрактных примитивных операций.
 *
 * Конкретные подклассы должны реализовать эти операции, но оставить сам
 * шаблонный метод без изменений.
 */
abstract class AbstractClass
{
    /**
     * Шаблонный метод определяет скелет алгоритма.
     */
    final public function templateMethod(): void
    {
        $this->baseOperation1();
        $this->requiredOperations1();
        $this->baseOperation2();
        $this->hook1();
        $this->requiredOperation2();
        $this->baseOperation3();
        $this->hook2();
    }

    /**
     * Эти операции уже имеют реализации.
     */
    protected function baseOperation1(): void
    {
        echo "AbstractClass говорит: я делаю основную часть работы<br>";
    }

    protected function baseOperation2(): void
    {
        echo "AbstractClass говорит: Но я позволяю подклассам переопределять некоторые операции<br>";
    }

    protected function baseOperation3(): void
    {
        echo "AbstractClass говорит: Но я все равно делаю основную часть работы<br>";
    }

    /**
     * А эти операции должны быть реализованы в подклассах.
     */
    abstract protected function requiredOperations1(): void;

    abstract protected function requiredOperation2(): void;

    /**
     * Это «хуки». Подклассы могут переопределять их, но это не обязательно,
     * поскольку у хуков уже есть стандартная (но пустая) реализация. Хуки
     * предоставляют дополнительные точки расширения в некоторых критических
     * местах алгоритма.
     */
    protected function hook1(): void { }

    protected function hook2(): void { }
}

/**
 * Конкретные классы должны реализовать все абстрактные операции базового
 * класса. Они также могут переопределить некоторые операции с реализацией по
 * умолчанию.
 */
class ConcreteClass1 extends AbstractClass
{
    protected function requiredOperations1(): void
    {
        echo "ConcreteClass1 говорит: Реализована Operation1<br>";
    }

    protected function requiredOperation2(): void
    {
        echo "ConcreteClass1 говорит: Реализована Operation2<br>";
    }
}

/**
 * Обычно конкретные классы переопределяют только часть операций базового
 * класса.
 */
class ConcreteClass2 extends AbstractClass
{
    protected function requiredOperations1(): void
    {
        echo "ConcreteClass2 говорит: Реализована Operation1<br>";
    }

    protected function requiredOperation2(): void
    {
        echo "ConcreteClass2 говорит: Реализована Operation2<br>";
    }

    protected function hook1(): void
    {
        echo "ConcreteClass2 говорит: Переопределенный Hook1<br>";
    }
}

/**
 * Клиентский код вызывает шаблонный метод для выполнения алгоритма. Клиентский
 * код не должен знать конкретный класс объекта, с которым работает, при
 * условии, что он работает с объектами через интерфейс их базового класса.
 */
function clientCode(AbstractClass $class)
{
    // ...
    $class->templateMethod();
    // ...
}

echo "Один и тот же клиентский код может работать с разными подклассами:<br>";
clientCode(new ConcreteClass1());
echo "<br>";

echo "Один и тот же клиентский код может работать с разными подклассами:<br>";
clientCode(new ConcreteClass2());