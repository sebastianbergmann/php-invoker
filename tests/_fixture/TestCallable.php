<?php
declare(strict_types=1);

class TestCallable
{
    public function test(int $sleep): bool
    {
        sleep($sleep);

        return true;
    }
}
