<?php

namespace App\Behavioral\Command;

/**
 * Интерфейс Команды объявляет метод для выполнения команд.
 */
interface Command
{
    public function execute(): void;
}

/**
 * Некоторые команды способны выполнять простые операции самостоятельно.
 */
class SimpleCommand implements Command
{
    private $payload;

    public function __construct(string $payload)
    {
        $this->payload = $payload;
    }

    public function execute(): void
    {
        echo "SimpleCommand: Видите, я могу делать простые вещи, например, печатать. (" . $this->payload . ")<br>";
    }
}

/**
 * Но есть и команды, которые делегируют более сложные операции другим объектам,
 * называемым «получателями».
 */
class ComplexCommand implements Command
{
    /**
     * Данные о контексте, необходимые для запуска методов получателя.
     */

    /**
     * Сложные команды могут принимать один или несколько объектов-получателей
     * вместе с любыми данными о контексте через конструктор.
     */
    public function __construct(private Receiver $receiver, private string $a, private string $b)
    {}

    /**
     * Команды могут делегировать выполнение любым методам получателя.
     */
    public function execute(): void
    {
        echo "ComplexCommand: сложные вещи должны выполняться объектом-получателем...<br>";
        $this->receiver->doSomething($this->a);
        $this->receiver->doSomethingElse($this->b);
    }
}

/**
 * Классы Получателей содержат некую важную бизнес-логику. Они умеют выполнять
 * все виды операций, связанных с выполнением запроса. Фактически, любой класс
 * может выступать Получателем.
 */
class Receiver
{
    public function doSomething(string $a): void
    {
        echo "Receiver: Работа над (" . $a . ".)<br>";
    }

    public function doSomethingElse(string $b): void
    {
        echo "Receiver: Также работаем над (" . $b . ".)<br>";
    }
}

/**
 * Отправитель связан с одной или несколькими командами. Он отправляет запрос
 * команде.
 */
class Invoker
{
    private Command $onStart;

    private Command $onFinish;

    /**
     * Инициализация команд.
     */
    public function setOnStart(Command $command): void
    {
        $this->onStart = $command;
    }

    public function setOnFinish(Command $command): void
    {
        $this->onFinish = $command;
    }

    /**
     * Отправитель не зависит от классов конкретных команд и получателей.
     * Отправитель передаёт запрос получателю косвенно, выполняя команду.
     */
    public function doSomethingImportant(): void
    {
        echo "Invoker: Кто-нибудь хочет, чтобы что-то было сделано до того, как я начну?<br>";
        if ($this->onStart instanceof Command) {
            $this->onStart->execute();
        }

        echo "Invoker: ...делать что-то действительно важное...<br>";

        echo "Invoker: Кто-нибудь хочет что-то сделать после того, как я закончу??<br>";
        if ($this->onFinish instanceof Command) {
            $this->onFinish->execute();
        }
    }
}

/**
 * Клиентский код может параметризовать отправителя любыми командами.
 */
$invoker = new Invoker();
$invoker->setOnStart(new SimpleCommand("Скажи привет!"));
$receiver = new Receiver();
$invoker->setOnFinish(new ComplexCommand($receiver, "Отправить письмо", "Сохранить отчет"));

$invoker->doSomethingImportant();