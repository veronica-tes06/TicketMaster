<?php
use PHPUnit\Framework\TestCase;

class DummyTest extends TestCase
{
    public function testTrueIsTrue(): void
    {
        $this->assertTrue(true, 'True should always be true');
    }
}
?>