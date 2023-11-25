<?php

namespace App\Helpers;

/**
 * Class ExecTimeHelpers
 *
 * A helper class for measuring execution time.
 *
 * @package App\Helpers
 */
class ExecTimeHelpers
{
    /**
     * The microtime instance.
     *
     * @var float
     */
    private float $microtime;

    /**
     * The execution time.
     *
     * @var float
     */
    private float $time;

    /**
     * Set the microtime instance.
     *
     * @return \App\Helpers\ExecTimeHelpers
     */
    public static function setTime(): self
    {
        return (new self())->setMicrotime();
    }

    /**
     * Set the microtime instance.
     *
     * @return $this
     */
    private function setMicrotime(): self
    {
        $this->microtime = microtime(true);
        return $this;
    }

    /**
     * Get the execution time in seconds.
     *
     * @return float
     */
    public function getTime(): float
    {
        $this->time = microtime(true) - $this->microtime;
        return $this->time;
    }

    /**
     * Get the execution time in minutes.
     *
     * @return float
     */
    public function getMinute(): float
    {
        return $this->getTime() / 60;
    }

    /**
     * Get the execution time in seconds.
     *
     * @return float
     */
    public function getSecond(): float
    {
        return $this->getTime() % 60;
    }

    /**
     * Get the execution time in seconds, minutes, and hours.
     *
     * @return array
     */
    public function get(): array
    {
        return [
            'time' => $this->getTime(),
            'minute' => $this->getMinute(),
            'second' => $this->getSecond(),
        ];
    }
}
