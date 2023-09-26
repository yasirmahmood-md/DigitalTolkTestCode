<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;


use DTApi\Helpers\TeHelper;

class TeHelperTest extends TestCase
{
    /**
     * Test the has() method in Room class
     *
     * @return void
     */
    public function test_tehelper_willExpireAt()
    {
        $this->assertContains("2023-09-26 03:59:59", TeHelper::willExpireAt('2023-09-26 23:59:59', '2023-09-26 20:59:59'));
    }
}