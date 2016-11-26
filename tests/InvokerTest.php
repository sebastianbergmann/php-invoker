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

use PHPUnit\Framework\TestCase;

class PHP_InvokerTest extends TestCase
{
    /**
     * @var \TestCallable
     */
    private $callable;

    /**
     * @var Invoker
     */
    private $invoker;

    protected function setUp()
    {
        $this->callable = new \TestCallable;
        $this->invoker  = new Invoker;
    }

    public function testCallableIsCorrectlyInvoked()
    {
        $this->assertTrue(
            $this->invoker->invoke(array($this->callable, 'test'), array(0), 1)
        );
    }

    public function testExceptionIsRaisedOnTimeout()
    {
        $this->expectException(TimeoutException::class);

        $this->invoker->invoke(array($this->callable, 'test'), array(2), 1);
    }
}
