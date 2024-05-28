<?php
namespace Cjel\TemplatesAide\Utility;

/***
 *
 * This file is part of the "Templates Aide" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * filey as part of the extension by 2024 Philipp Dieter <philipp.dieter@attic-media.net>
 *
 ***/

/**
 *
 */
class SessionUtility
{

    /**
     *
     */
    public static function setSessionValue(
        $type, $key, $value
    ) {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $frontendUser = $request->getAttribute('frontend.user');
        $frontendUser->setKey($type, $key, $value);
        $frontendUser->storeSessionData();
    }

    /**
     *
     */
    public static function getSessionValue(
        $type, $key
    ) {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $frontendUser = $request->getAttribute('frontend.user');
        return $frontendUser->getKey($type, $key);
    }
}
