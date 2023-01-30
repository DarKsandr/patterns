<?php

namespace App\Behavioral\State;

/**
 * Контекст определяет интерфейс, представляющий интерес для клиентов. Он также
 * хранит ссылку на экземпляр подкласса Состояния, который отображает текущее
 * состояние Контекста.
 */
class Context
{
    /**
     * Ссылка на текущее состояние Контекста.
     */
    private State $state;

    public function __construct(State $state)
    {
        $this->transitionTo($state);
    }

    /**
     * Контекст позволяет изменять объект Состояния во время выполнения.
     */
    public function transitionTo(State $state): void
    {
        echo "Context: Переход к " . get_class($state) . ".<br>";
        $this->state = $state;
        $this->state->setContext($this);
    }

    /**
     * Контекст делегирует часть своего поведения текущему объекту Состояния.
     */
    public function request1(): void
    {
        $this->state->handle1();
    }

    public function request2(): void
    {
        $this->state->handle2();
    }
}

/**
 * Базовый класс Состояния объявляет методы, которые должны реализовать все
 * Конкретные Состояния, а также предоставляет обратную ссылку на объект
 * Контекст, связанный с Состоянием. Эта обратная ссылка может использоваться
 * Состояниями для передачи Контекста другому Состоянию.
 */
abstract class State
{
    protected Context $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    abstract public function handle1(): void;

    abstract public function handle2(): void;
}

/**
 * Конкретные Состояния реализуют различные модели поведения, связанные с
 * состоянием Контекста.
 */
class ConcreteStateA extends State
{
    public function handle1(): void
    {
        echo "ConcreteStateA обрабатывает request1.<br>";
        echo "ConcreteStateA хочет изменить состояние контекста.<br>";
        $this->context->transitionTo(new ConcreteStateB());
    }

    public function handle2(): void
    {
        echo "ConcreteStateA обрабатывает request2.<br>";
    }
}

class ConcreteStateB extends State
{
    public function handle1(): void
    {
        echo "ConcreteStateB обрабатывает request1.<br>";
    }

    public function handle2(): void
    {
        echo "ConcreteStateB обрабатывает request2.<br>";
        echo "ConcreteStateB хочет изменить состояние контекста.<br>";
        $this->context->transitionTo(new ConcreteStateA());
    }
}

/**
 * Клиентский код.
 */
$context = new Context(new ConcreteStateA());
$context->request1();
$context->request2();