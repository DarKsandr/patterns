<?php

namespace App\Behavioral\Iterator;

/**
 * Конкретные Итераторы реализуют различные алгоритмы обхода. Эти классы
 * постоянно хранят текущее положение обхода.
 */
class AlphabeticalOrderIterator implements \Iterator
{
    private WordsCollection $collection;

    /**
     * Хранит текущее положение обхода. У итератора может быть
     * множество других полей для хранения состояния итерации, особенно когда он
     * должен работать с определённым типом коллекции.
     */
    private int $position = 0;

    /**
     * Эта переменная указывает направление обхода.
     */
    private bool $reverse = false;

    public function __construct(WordsCollection $collection, Bool $reverse = false)
    {
        $this->collection = $collection;
        $this->reverse = $reverse;
    }

    public function rewind(): void
    {
        $this->position = $this->reverse ?
            count($this->collection->getItems()) - 1 : 0;
    }

    public function current(): mixed
    {
        return $this->collection->getItems()[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position = $this->position + ($this->reverse ? -1 : 1);
    }

    public function valid(): bool
    {
        return isset($this->collection->getItems()[$this->position]);
    }
}

/**
 * Конкретные Коллекции предоставляют один или несколько методов для получения
 * новых экземпляров итератора, совместимых с классом коллекции.
 */
class WordsCollection implements \IteratorAggregate
{
    private $items = [];

    public function getItems()
    {
        return $this->items;
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function getIterator(): \Iterator
    {
        return new AlphabeticalOrderIterator($this);
    }

    public function getReverseIterator(): \Iterator
    {
        return new AlphabeticalOrderIterator($this, true);
    }
}

/**
 * Клиентский код может знать или не знать о Конкретном Итераторе или классах
 * Коллекций, в зависимости от уровня косвенности, который вы хотите сохранить в
 * своей программе.
 */
$collection = new WordsCollection();
$collection->addItem("Первый");
$collection->addItem("Второй");
$collection->addItem("Третьий");

echo "Прямой обход:<br>";
foreach ($collection->getIterator() as $item) {
    echo $item . "<br>";
}

echo "<br>";
echo "Обратный обход:<br>";
foreach ($collection->getReverseIterator() as $item) {
    echo $item . "<br>";
}