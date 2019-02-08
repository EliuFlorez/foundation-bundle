<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 08/02/19
 * Time: 00:34
 */

namespace MoncareyWS\FoundationBundle\Bundle;


interface BundleHasAssetsToMove
{
    /**
     * @param string $bundleAssetsDir
     * @return array
     */
    public function getFilesToMove(string $bundleAssetsDir): array;
}