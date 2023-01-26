<?php

namespace App\Behavioral\Memento;

/**
 * Создатель содержит некоторое важное состояние, которое может со временем
 * меняться. Он также объявляет метод сохранения состояния внутри снимка и метод
 * восстановления состояния из него.
 */
class Originator
{
    /**
     * Для удобства состояние создателя хранится внутри одной
     * переменной.
     */
    private string $state;

    public function __construct(string $state)
    {
        $this->state = $state;
        echo "Originator: Мое начальное состояние: {$this->state}<br>";
    }

    /**
     * Бизнес-логика Создателя может повлиять на его внутреннее состояние.
     * Поэтому клиент должен выполнить резервное копирование состояния с помощью
     * метода save перед запуском методов бизнес-логики.
     */
    public function doSomething(): void
    {
        echo "Originator: Я делаю что-то важное.<br>";
        $this->state = $this->generateRandomString(30);
        echo "Originator: и мое состояние изменилось на: {$this->state}<br>";
    }

    private function generateRandomString(int $length = 10): string
    {
        $str = mb_str_split(str_repeat(
            $x = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ',
            ceil($length / strlen($x))
        ));
        shuffle($str);
        return mb_substr(
            implode("", $str),
            1,
            $length,
        );
    }

    /**
     * Сохраняет текущее состояние внутри снимка.
     */
    public function save(): Memento
    {
        return new ConcreteMemento($this->state);
    }

    /**
     * Восстанавливает состояние Создателя из объекта снимка.
     */
    public function restore(Memento $memento): void
    {
        $this->state = $memento->getState();
        echo "Originator: Мое состояние изменилось на: {$this->state}<br>";
    }
}

/**
 * Интерфейс Снимка предоставляет способ извлечения метаданных снимка, таких как
 * дата создания или название. Однако он не раскрывает состояние Создателя.
 */
interface Memento
{
    public function getName(): string;

    public function getDate(): string;

    public function getState(): string;
}

/**
 * Конкретный снимок содержит инфраструктуру для хранения состояния Создателя.
 */
class ConcreteMemento implements Memento
{
    private $state;

    private $date;

    public function __construct(string $state)
    {
        $this->state = $state;
        $this->date = date('Y-m-d H:i:s');
    }

    /**
     * Создатель использует этот метод, когда восстанавливает своё состояние.
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Остальные методы используются Опекуном для отображения метаданных.
     */
    public function getName(): string
    {
        return $this->date . " / (" . mb_substr($this->state, 0, 9) . "...)";
    }

    public function getDate(): string
    {
        return $this->date;
    }
}

/**
 * Опекун не зависит от класса Конкретного Снимка. Таким образом, он не имеет
 * доступа к состоянию создателя, хранящемуся внутри снимка. Он работает со
 * всеми снимками через базовый интерфейс Снимка.
 */
class Caretaker
{
    /**
     * @var Memento[]
     */
    private $mementos = [];

    private Originator $originator;

    public function __construct(Originator $originator)
    {
        $this->originator = $originator;
    }

    public function backup(): void
    {
        echo "<br>Caretaker: Сохранение Originator's состояния...<br>";
        $this->mementos[] = $this->originator->save();
    }

    public function undo(): void
    {
        if (!count($this->mementos)) {
            return;
        }
        $memento = array_pop($this->mementos);

        echo "Caretaker: Восстановление состояния до: " . $memento->getName() . "<br>";
        try {
            $this->originator->restore($memento);
        } catch (\Exception $e) {
            $this->undo();
        }
    }

    public function showHistory(): void
    {
        echo "Caretaker: Вот список памятных вещей:<br>";
        foreach ($this->mementos as $memento) {
            echo $memento->getName() . "<br>";
        }
    }
}

/**
 * Клиентский код.
 */
$originator = new Originator("Супер-пупер-супер-пупер-супер.");
$caretaker = new Caretaker($originator);

$caretaker->backup();
$originator->doSomething();

$caretaker->backup();
$originator->doSomething();

$caretaker->backup();
$originator->doSomething();

echo "<br>";
$caretaker->showHistory();

echo "<br>Client: Теперь откатимся!<br><br>";
$caretaker->undo();

echo "<br>Client: Еще раз!<br><br>";
$caretaker->undo();