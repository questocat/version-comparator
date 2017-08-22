<?php

namespace Emanci\VersionCompare;

class VersionCompare
{
    /**
     * Compares two "PHP-standardized" version number strings.
     *
     * @param string $version1
     * @param string $operator
     * @param string $version2
     *
     * @return bool
     */
    public function compare($version1, $operator, $version2)
    {
        $newVersion1 = trim(str_replace('.', '', $version1));
        $newVersion2 = trim(str_replace('.', '', $version2));

        if (is_numeric($newVersion1) && is_numeric($newVersion2)) {
            $newVersion1Len = strlen($newVersion1);
            $newVersion2Len = strlen($newVersion2);

            if ($newVersion1Len > $newVersion2Len) {
                $newVersion2 = str_pad($newVersion2, $newVersion1Len, 0);
            }

            if ($newVersion1Len < $newVersion2Len) {
                $newVersion1 = str_pad($newVersion1, $newVersion2Len, 0);
            }
        } else {
            $newVersion1 = trim($version1);
            $newVersion2 = trim($version2);
        }

        return version_compare((string) $newVersion1, (string) $newVersion2, $operator);
    }
}
