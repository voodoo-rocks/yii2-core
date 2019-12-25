<?php


namespace vr\core;

use Closure;
use yii\base\Component;
use yii\base\Event;

/**
 * Class Counter
 * @package app\components
 * @property-read float $elapsed
 * @property-read float $remaining
 * @property-read string $formattedElapsed
 * @property-read string $formattedRemaining
 * @property int $total
 *
 *
 * How to use:
 *
 * $total   = 100;
 * $counter = new Counter($total);
 *
 * $counter->launch(function (Counter $counter) {
 *      $counter->on(Counter::EVENT_TICK, function (Event $event) {
 *          echo $event->sender->log;
 *      });
 *
 *      foreach (range(0, $counter->total) as $i) {
 *          // do something
 *          $counter->setProgress($i);
 *      }
 * }, 5);
 */
class Counter extends Component
{
    /**
     *
     */
    const EVENT_TICK = 'event-tick';

    /**
     *
     */
    const DEFAULT_TICK_EACH = 5;

    /**
     * @var
     */
    private $_timestamp;

    /**
     * @var int
     */
    private $_tickEach = 1;

    /**
     * @var
     */
    private $_current;

    /**
     * @var
     */
    private $_total;

    /**
     * Counter constructor.
     * @param $total
     * @param int $tickEach
     */
    public function __construct($total, $tickEach = self::DEFAULT_TICK_EACH)
    {
        parent::__construct([]);

        $this->_total    = $total;
        $this->_tickEach = $tickEach;

        $this->_timestamp = microtime(true);
    }

    public static function enumerate($total, callable $closure, $tick = self::DEFAULT_TICK_EACH)
    {
        $counter = new self($total, $tick);
        $counter->launch(function (self $counter) use ($closure) {
            foreach (range(0, $counter->total - 1) as $i) {
                call_user_func($closure, $i);

                $counter->setProgress($i + 1);
            }
        });
    }

    /**
     * @param Closure $todo
     * @return mixed
     */
    public function launch(Closure $todo)
    {
        $this->_timestamp = microtime(true);

        $this->off(Counter::EVENT_TICK);
        $this->on(Counter::EVENT_TICK, function (Event $event) {
            echo $event->sender->log;
        });

        return call_user_func($todo, $this);
    }

    /**
     * @param int $current
     */
    public function setProgress(int $current)
    {
        $this->_current = $current;

        if ($this->_tickEach != -1 && $current && $current % $this->_tickEach == 0) {
            $this->trigger(self::EVENT_TICK, new Event([
                'sender' => $this
            ]));
        }
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return "{$this->_current} of {$this->_total}. Remaining {$this->formattedRemaining}" . PHP_EOL;
    }

    /**
     * @return float
     */
    public function getElapsed()
    {
        return microtime(true) - $this->_timestamp;
    }

    /**
     * @return float
     */
    public function getRemaining()
    {
        return $this->elapsed * ($this->_total - $this->_current) / $this->_current;
    }

    /**
     * @return float
     */
    public function getFormattedElapsed()
    {
        return $this->format($this->elapsed);
    }

    /**
     * @param float $duration
     * @return string
     */
    private function format(float $duration)
    {
        $hours   = floor($duration / (60 * 60));
        $minutes = floor(($duration % (60 * 60)) / 60);
        $seconds = floor($duration % 60);

        return sprintf('[%02d:%02d:%02d]', $hours, $minutes, $seconds);
    }

    /**
     * @return float
     */
    public function getFormattedRemaining()
    {
        return $this->format($this->remaining);
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->_total;
    }
}