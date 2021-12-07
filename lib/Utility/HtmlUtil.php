<?php declare(strict_types=1);

/**
 * ownCloud - Music app
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Pauli Järvinen <pauli.jarvinen@gmail.com>
 * @copyright Pauli Järvinen 2020
 */

namespace OCA\Music\Utility;

/**
 * Utility functions to be used from the front-end templates
 */
class HtmlUtil {
	/**
	 * @param string $name
	 */
	public static function addWebpackScript(string $name) {
		$manifest = self::getManifest();
		$hashedName = \substr($manifest["$name.js"], 0, -3); // the extension is cropped from the name in $manifest
		\OCP\Util::addScript('music', '../dist/' . $hashedName);
	}

	/**
	 * @param string $name
	 */
	public static function addWebpackStyle(string $name) {
		$manifest = self::getManifest();
		$hashedName = \substr($manifest["$name.css"], 0, -4); // the extension is cropped from the name in $manifest
		\OCP\Util::addStyle('music', '../dist/' . $hashedName);
	}

	private static $manifest = null;
	private static function getManifest() {
		if (self::$manifest === null) {
			$manifestPath = \join(DIRECTORY_SEPARATOR, [\dirname(__DIR__), '..', 'dist', 'manifest.json']);
			$manifestText = \file_get_contents($manifestPath);
			self::$manifest = \json_decode($manifestText, true);
		}
		return self::$manifest;
	}
}
