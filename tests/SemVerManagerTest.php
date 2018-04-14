<?php

/*
 * This file is part of questocat/version-comparator package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests;

use Questocat\VersionComparator\SemVerManager;

class SemVerManagerTest extends TestCase
{
    public function testCompareAndReturnInt()
    {
        $semVerManager = new SemVerManager();
        $this->assertEquals(-1, $semVerManager->compare('2.9.0', '2.9.6'));
        $this->assertEquals(1, $semVerManager->compare('5.1.0', '5.1.0-alpha'));
        $this->assertEquals(1, $semVerManager->compare('2.0.1-alpha.2', '2.0.1-alpha.1'));
        $this->assertEquals(0, $semVerManager->compare('1.0.0-rc.1+build.1', '1.0.0-rc.1'));
    }

    public function testCompareAndReturnBool()
    {
        $semVerManager = new SemVerManager();
        $this->assertTrue($semVerManager->compare('2.9.0', '2.9.6', '<'));
        $this->assertTrue($semVerManager->compare('5.1.0', '5.1.0-alpha', '>'));
        $this->assertTrue($semVerManager->compare('2.0.1-alpha.2', '2.0.1-alpha.1', '>'));
        $this->assertTrue($semVerManager->compare('1.0.0-rc.1+build.1', '1.0.0-rc.1', '='));
    }

    public function testCompareTo()
    {
        $semVerManager = new SemVerManager('2.9.0');
        $this->assertTrue($semVerManager->compareTo('2.9.6', '<'));
        $this->assertTrue($semVerManager->compareTo('2.8.9', '>'));
        $this->assertTrue($semVerManager->compareTo('2.8.9-alpha', '>'));
    }
}
