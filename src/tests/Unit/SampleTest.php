<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 *  Check more details about phpUnit at https://docs.phpunit.de/en/
 * 
 */
final class SampleTest extends TestCase
{
    private $count;

    /**
     * Think of this as your constructor
     * 
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->count += 2;
        
    }

    /**
     * @test
     * 
     */
    public function it_does_some_intresting_stuff(): void
    {
        $this->assertSame(1, $this->count);
    }
}