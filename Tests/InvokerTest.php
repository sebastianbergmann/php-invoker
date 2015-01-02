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

if (!defined('FIXTURE_PATH')) {
    define(
      'FIXTURE_PATH',
      dirname(__FILE__) . DIRECTORY_SEPARATOR .
      '_fixture' . DIRECTORY_SEPARATOR
    );
}

require_once FIXTURE_PATH . 'TestCallable.php';

/**
 * Tests for PHP_Invoker.
 *
 * @package    PHP
 * @subpackage Invoker
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://github.com/sebastianbergmann/php-invoker
 * @since      Class available since Release 1.0.0
 */
class PHP_InvokerTest extends PHPUnit_Framework_TestCase
{
    protected $callable;
    protected $invoker;

    protected function setUp()
    {
        $this->callable = new TestCallable;
        $this->invoker  = new PHP_Invoker;
    }

    public function testCallableIsCorrectlyInvoked()
    {
        $this->assertTrue(
          $this->invoker->invoke(array($this->callable, 'test'), array(0), 1)
        );
    }

    /**
     * @expectedException PHP_Invoker_TimeoutException
     */
    public function testExceptionIsRaisedOnTimeout()
    {
        $this->invoker->invoke(array($this->callable, 'test'), array(2), 1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionIsRaisedOnInvalidCallable()
    {
        $this->invoker->invoke(NULL, array(), 1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionIsRaisedOnInvalidTimeout()
    {
        $this->invoker->invoke(array($this->callable, 'test'), array(), NULL);
    }
}
