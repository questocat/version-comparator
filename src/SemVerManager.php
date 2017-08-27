<?php

/*
 * This file is part of version-compare package.
 *
 * (c) emanci <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Emanci\VersionCompare;

class SemVerManager
{
    /**
     * The Version instance.
     *
     * @var Version
     */
    protected $version;

    /**
     * The VersionCompare instance.
     *
     * @var VersionCompare
     */
    protected $versionCompare;

    /**
     * VersionCompare construct.
     *
     * @param string $versionStr
     */
    public function __construct($versionStr = null)
    {
        $this->setVersion(new Version());
        $this->setVersionCompare(new VersionCompare());

        if (!is_null($versionStr)) {
            $this->mapVersionObject($versionStr);
        }
    }

    /**
     * Set version.
     *
     * @param Version $version
     *
     * @return SemVerManager
     */
    public function setVersion(Version $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set versionCompare.
     *
     * @param VersionCompare $versionCompare
     *
     * @return SemVerManager
     */
    public function setVersionCompare(VersionCompare $versionCompare)
    {
        $this->versionCompare = $versionCompare;

        return $this;
    }

    /**
     * Compares one version number string to another.
     *
     * @param string      $versionStr
     * @param string|null $operator
     *
     * @return int|bool
     */
    public function compareTo($versionStr, $operator = null)
    {
        $version = static::create($versionStr)->getVersion();
        $compare = $this->versionCompare->versionCompare($this->version, $version);

        return $operator ? $this->versionCompare->returnBool($compare, $operator) : $compare;
    }

    /**
     * Compares two version number strings.
     *
     * @param string      $version1
     * @param string      $version2
     * @param string|null $operator
     *
     * @return int|bool
     */
    public function compare($version1, $version2, $operator = null)
    {
        $version1 = static::create($version1)->getVersion();
        $version2 = static::create($version2)->getVersion();
        $compare = $this->versionCompare->versionCompare($version1, $version2);

        return $operator ? $this->versionCompare->returnBool($compare, $operator) : $compare;
    }

    /**
     * Map the parse's raw to Semantic Version object.
     *
     * @param string $versionStr
     */
    protected function mapVersionObject($versionStr)
    {
        $version = VersionParser::parse($versionStr);

        $this->version->map($version);
    }

    /**
     * Creates a new Version instance.
     *
     * @param string $versionStr
     *
     * @return VersionCompare
     */
    protected static function create($versionStr)
    {
        return new static($versionStr);
    }
}
