<?php

/**
 * ownCloud - Music app
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Pauli Järvinen <pauli.jarvinen@gmail.com>
 * @copyright Morris Jobke 2013, 2014
 * @copyright Pauli Järvinen 2017, 2018
 */

namespace OCA\Music\App;

$appName = 'music';

/**
 * Set default content security policy to allow loading media from data URL.
 * The needed API is not available on ownCloud 8.2.
 */
if (\method_exists(\OC::$server, 'getContentSecurityPolicyManager')) {
	$policy = new \OCP\AppFramework\Http\ContentSecurityPolicy();
	$policy->addAllowedMediaDomain('data:');
	\OC::$server->getContentSecurityPolicyManager()->addDefaultPolicy($policy);
}

/**
 * Load embedded music player for Files and Sharing apps
 *
 * The nice way to do this would be
 * \OC::$server->getEventDispatcher()->addListener('OCA\Files::loadAdditionalScripts', $loadEmbeddedMusicPlayer);
 * \OC::$server->getEventDispatcher()->addListener('OCA\Files_Sharing::loadAdditionalScripts', $loadEmbeddedMusicPlayer);
 * ... but this doesn't work for shared files on ownCloud 9.0, at least. Hence, we load the scripts
 * directly if the requested URL seems to be for Files or Sharing.
 *
 * Furthermore, it would be sensible to load majority of the needed scripts within the main js file (files-music-player)
 * with OC.addScript() only when the player is actually used. However, this doesn't seem to work on Nextcloud 12.0.0,
 * probably because of https://github.com/nextcloud/server/issues/5314.
 */
$loadEmbeddedMusicPlayer = function () use ($appName) {
	\OCP\Util::addScript($appName, 'vendor/soundmanager/script/soundmanager2-nodebug-jsmin');
	\OCP\Util::addScript($appName, 'vendor/aurora/aurora-bundle.min');
	\OCP\Util::addScript($appName, 'vendor/javascript-detect-element-resize/jquery.resize');
	\OCP\Util::addScript($appName, 'vendor/jquery-initialize/jquery.initialize.min');
	\OCP\Util::addScript($appName, 'vendor/js-cookie/src/js.cookie');
	\OCP\Util::addScript($appName, 'public/files-music-player');
	\OCP\Util::addStyle($appName, 'public/files-music-player');
};

$request = \OC::$server->getRequest();
if (isset($request->server['REQUEST_URI'])) {
	$url = $request->server['REQUEST_URI'];
	$isFilesUrl = \preg_match('%/apps/files(/.*)?%', $url);
	$isShareUrl = \preg_match('%/s/.+%', $url)
		&& !\preg_match('%/apps/.*%', $url)
		&& !\preg_match('%.*/authenticate%', $url);
	if ($isFilesUrl || $isShareUrl) {
		$loadEmbeddedMusicPlayer();
	}
}
