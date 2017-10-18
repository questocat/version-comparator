<?php

/*
 * This file is part of emanci/version-comparator package.
 *
 * (c) emanci <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests;

use Emanci\VersionComparator\Version;
use Emanci\VersionComparator\VersionComparator;
use Emanci\VersionComparator\VersionParser;

/**
 * Convention:.
 *
 * @see  http://php.net/manual/zh/function.version-compare.php
 *
 * -1 , v1 < v2
 * 0 , v1 == v2
 * 1 , v1 > v2
 */
class VersionComparatorTest extends TestCase
{
    protected $versionComparator;

    public function setUp()
    {
        $this->versionComparator = new VersionComparator();
    }

    public function testVersionCompareByStandardVersion()
    {
        $version1 = $this->createVersion('1.0.2');
        $version2 = $this->createVersion('1.3.2');

        $this->assertEquals(-1, $this->versionComparator->versionCompare($version1, $version2));
        $this->assertEquals(1, $this->versionComparator->versionCompare($version2, $version1));
    }

    public function testVersionCompareByPreReleaseVersion()
    {
        $version1 = $this->createVersion('2.0.1-alpha-abc.2');
        $version2 = $this->createVersion('2.0.1-alpha-abc.1');

        $this->assertEquals(1, $this->versionComparator->versionCompare($version1, $version2));
        $this->assertEquals(-1, $this->versionComparator->versionCompare($version2, $version1));
    }

    public function testVersionCompareByPreReleaseVersionWithDifferentLength()
    {
        $version1 = $this->createVersion('2.0.1-alpha.2');
        $version2 = $this->createVersion('2.0.1-alpha');

        $this->assertEquals(1, $this->versionComparator->versionCompare($version1, $version2));
        $this->assertEquals(-1, $this->versionComparator->versionCompare($version2, $version1));

        $version1 = $this->createVersion('1.0.0-alpha.1');
        $version2 = $this->createVersion('1.0.0-alpha.beta');

        $this->assertEquals(-1, $this->versionComparator->versionCompare($version1, $version2));
        $this->assertEquals(1, $this->versionComparator->versionCompare($version2, $version1));

        $version1 = $this->createVersion('1.0.0-alpha.beta');
        $version2 = $this->createVersion('1.0.0-beta');

        $this->assertEquals(-1, $this->versionComparator->versionCompare($version1, $version2));
        $this->assertEquals(1, $this->versionComparator->versionCompare($version2, $version1));
    }

    public function testVersionCompareByStandardVersionWithPreReleaseVersion()
    {
        $version1 = $this->createVersion('5.1.0');
        $version2 = $this->createVersion('5.1.0-alpha');

        $this->assertEquals(1, $this->versionComparator->versionCompare($version1, $version2));
        $this->assertEquals(-1, $this->versionComparator->versionCompare($version2, $version1));
    }

    public function testVersionCompareByPreReleaseVersionWithBuildMetadata()
    {
        $version1 = $this->createVersion('1.0.0-rc.1+build.1');
        $version2 = $this->createVersion('1.0.0-rc.1');

        $this->assertEquals(0, $this->versionComparator->versionCompare($version1, $version2));
    }

    public function testReturnBool()
    {
        $this->assertTrue($this->versionComparator->returnBool(-1, '<'));
        $this->assertTrue($this->versionComparator->returnBool(-1, 'lt'));
        $this->assertTrue($this->versionComparator->returnBool(0, '='));
        $this->assertTrue($this->versionComparator->returnBool(0, 'eq'));
        $this->assertTrue($this->versionComparator->returnBool(0, '=='));
        $this->assertTrue($this->versionComparator->returnBool(1, '>'));
        $this->assertTrue($this->versionComparator->returnBool(1, 'gt'));

        $this->assertFalse($this->versionComparator->returnBool(0, '!='));
        $this->assertTrue($this->versionComparator->returnBool(-1, '!='));
        $this->assertTrue($this->versionComparator->returnBool(1, '!='));

        $this->assertTrue($this->versionComparator->returnBool(-1, '<='));
        $this->assertTrue($this->versionComparator->returnBool(0, '<='));
        $this->assertFalse($this->versionComparator->returnBool(1, '<='));

        $this->assertFalse($this->versionComparator->returnBool(-1, '>='));
        $this->assertTrue($this->versionComparator->returnBool(0, '>='));
        $this->assertTrue($this->versionComparator->returnBool(1, '>='));

        $this->assertNull($this->versionComparator->returnBool(0, '>=='));
    }

    protected function createVersion($versionStr)
    {
        $result = VersionParser::parse($versionStr);

        return (new Version())->map($result);
    }
}
