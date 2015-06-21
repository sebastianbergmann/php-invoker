<?php
class TestCallable
{
    public function test($sleep)
    {
        sleep($sleep);
        return TRUE;
    }
}
