<?php
declare(ticks = 1);

/**
 * PHP_Invoker
 *
 * Copyright (c) 2011, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHP
 * @subpackage Invoker
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://github.com/sebastianbergmann/php-invoker
 * @since      File available since Release 1.0.0
 */

/**
 * Utility class for invoking callables with a timeout.
 *
 * @package    PHP
 * @subpackage Invoker
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://github.com/sebastianbergmann/php-invoker
 * @since      Class available since Release 1.0.0
 */
class PHP_Invoker
{
    /**
     * Invokes a callable and raises an exception when the execution does not
     * finish before the specified timeout.
     *
     * @param  callable $callable
     * @param  array    $arguments
     * @param  integer  $timeout in seconds
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

        try {
            $result = call_user_func_array($callable, $arguments);
        } catch (Exception $e) {
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
        throw new PHP_Invoker_TimeoutException;
    }
}
