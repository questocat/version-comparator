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

use Emanci\VersionComparator\InvalidVersionException;
use Emanci\VersionComparator\VersionParser;

class VersionParserTest extends TestCase
{
    public function testParse()
    {
        $versionStr = '1.0.0-rc.1+build.1';
        $result = VersionParser::parse($versionStr);

        $this->assertEquals(1, $result['major']);
        $this->assertEquals(0, $result['minor']);
        $this->assertEquals(0, $result['patch']);
        $this->assertCount(2, $result['preRelease']);
        $this->assertCount(2, $result['buildMetadata']);
    }

    public function testInvalidVersionException()
    {
        $this->expectException(InvalidVersionException::class);

        $versionStr = '1.0-rc.1+build.1';
        VersionParser::parse($versionStr);
    }
}
