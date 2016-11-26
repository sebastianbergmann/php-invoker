<?php
/*
 * This file is part of the PHP_Invoker package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SebastianBergmann\Invoker;

final class Invoker
{
    /**
     * @var int
     */
    private $timeout;

    public function invoke(callable $callable, array $arguments, int $timeout)
    {
        pcntl_signal(
            SIGALRM,
            function () {
                throw new TimeoutException(
                    sprintf(
                        'Execution aborted after %s',
                        \PHP_Timer::secondsToTimeString($this->timeout)
                    )
                );
            },
            true
        );

        $this->timeout = $timeout;

        pcntl_async_signals(true);
        pcntl_alarm($timeout);

        try {
            $result = call_user_func_array($callable, $arguments);
        }

        catch (\Throwable $t) {
            pcntl_alarm(0);
            throw $t;
        }

        pcntl_alarm(0);

        return $result;
    }
}
