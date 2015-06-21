<?php
/*
 * This file is part of the PHP_Invoker package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(ticks = 1);

/**
 * Utility class for invoking callables with a timeout.
 *
 * @since      Class available since Release 1.0.0
 */
class PHP_Invoker
{
    /**
     * @var int
     */
    protected $timeout;

    /**
     * Invokes a callable and raises an exception when the execution does not
     * finish before the specified timeout.
     *
     * @param  callable                 $callable
     * @param  array                    $arguments
     * @param  int                      $timeout   in seconds
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function invoke($callable, array $arguments, $timeout)
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException;
        }

        if (!is_integer($timeout)) {
            throw new InvalidArgumentException;
        }

        pcntl_signal(SIGALRM, array($this, 'callback'), TRUE);
        pcntl_alarm($timeout);

        $this->timeout = $timeout;

        try {
            $result = call_user_func_array($callable, $arguments);
        }

        catch (Exception $e) {
            pcntl_alarm(0);
            throw $e;
        }

        pcntl_alarm(0);

        return $result;
    }

    /**
     * Invoked by pcntl_signal() when a SIGALRM occurs.
     */
    public function callback()
    {
        throw new PHP_Invoker_TimeoutException(
          sprintf(
            'Execution aborted after %s',
            PHP_Timer::secondsToTimeString($this->timeout)
          )
        );
    }
}
