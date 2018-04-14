<?php

/*
 * This file is part of questocat/version-comparator package.
 *
 * (c) emanci <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Questocat\VersionComparator;

class Version
{
    /**
     * The major version number.
     *
     * @var int
     */
    protected $major = 0;

    /**
     * The minor version number.
     *
     * @var int
     */
    protected $minor = 0;

    /**
     * The patch number.
     *
     * @var int
     */
    protected $patch = 0;

    /**
     * The pre-release version number.
     *
     * @var array
     */
    protected $preRelease = [];

    /**
     * The build metadata identifiers.
     *
     * @var array
     */
    protected $buildMetadata = [];

    /**
     * Get major.
     *
     * @return int
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Get minor.
     *
     * @return int
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * Get patch.
     *
     * @return int
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * Get preRelease.
     *
     * @return array
     */
    public function getPreRelease()
    {
        return $this->preRelease;
    }

    /**
     * Get buildMetadata.
     *
     * @return array
     */
    public function getBuildMetadata()
    {
        return $this->buildMetadata;
    }

    /**
     * Map the given array onto the version's properties.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function map(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Checks if the version number is stable.
     *
     * @return bool
     */
    public function isStable()
    {
        return empty($this->preRelease) && 0 !== $this->major;
    }
}
