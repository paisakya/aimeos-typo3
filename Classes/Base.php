<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos;


/**
 * Aimeos base class with common functionality.
 *
 * @package TYPO3
 */
class Base
{
	private static $extConfig;


	/**
	 * Returns the Aimeos bootstrap object
	 *
	 * @param array $extDirs List of directories with Aimeos extensions
	 * @return \Aimeos\Bootstrap Aimeos bootstrap object
	 */
	public static function getAimeos( $extDirs = array() )
	{
		$className = 'Aimeos\Aimeos\Base\Aimeos';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos'];
		}

		$object =  new $className();
		return $object->get( $extDirs );
	}


	/**
	 * Creates a new configuration object
	 *
	 * @param array $local Multi-dimensional associative list with local configuration
	 * @return MW_Config_Interface Configuration object
	 */
	public static function getConfig( array $local = array() )
	{
		$className = 'Aimeos\Aimeos\Base\Config';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_config'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_config'];
		}

		$object =  new $className( self::getAimeos() );
		return $object->get( $local );
	}


	/**
	 * Returns the current context
	 *
	 * @param \Aimeos\MW\Config\Iface Configuration object
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public static function getContext( \Aimeos\MW\Config\Iface $config )
	{
		$className = 'Aimeos\Aimeos\Base\Context';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_context'];
		}

		$object =  new $className();
		return $object->get( $config );
	}


	/**
	 * Returns the extension configuration
	 *
	 * @param string Name of the configuration setting
	 * @param mixed Value returned if no value in extension configuration was found
	 * @return mixed Value associated with the configuration setting
	 */
	public static function getExtConfig( $name, $default = null )
	{
		if( self::$extConfig === null )
		{
			if( ( $conf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['aimeos'] ) ) === false ) {
				$conf = array();
			}

			self::$extConfig = $conf;
		}

		if( isset( self::$extConfig[$name] ) ) {
			return self::$extConfig[$name];
		}

		return $default;
	}


	/**
	 * Creates new translation objects
	 *
	 * @param array $languageIds List of two letter ISO language IDs
	 * @param array $local List of local translation entries overwriting the standard ones
	 * @return array List of translation objects implementing MW_Translation_Interface
	 */
	public static function getI18n( array $languageIds, array $local = array() )
	{
		$className = 'Aimeos\Aimeos\Base\I18n';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_i18n'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_i18n'];
		}

		$object =  new $className( self::getAimeos() );
		return $object->get( $languageIds, $local );
	}


	/**
	 * Creates a new locale object
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public static function getLocale( \Aimeos\MShop\Context\Item\Iface $context, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null )
	{
		$className = 'Aimeos\Aimeos\Base\Locale';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_locale'];
		}

		$object =  new $className();
		return $object->get( $context, $request );
	}


	/**
	 * Returns the version of the Aimeos TYPO3 extension
	 *
	 * @return string Version string
	 */
	public static function getVersion()
	{
		$match = array();
		$content = @file_get_contents( dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'ext_emconf.php' );

		if( preg_match( "/'version' => '([^']+)'/", $content, $match ) === 1 ) {
			return $match[1];
		}

		return '';
	}


	/**
	 * Creates the view object for the HTML client.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder URL builder object
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface|null $request Request object
	 * @param string|null $locale Code of the current language or null for no translation
	 * @param boolean $frontend True if the view is for the frontend, false for the backend
	 * @return \Aimeos\MW\View\Iface View object
	 */
	public static function getView( \Aimeos\MShop\Context\Item\Iface $context, \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder,
		array $templatePaths, \TYPO3\CMS\Extbase\Mvc\RequestInterface $request = null, $locale = null, $frontend = true )
	{
		$className = 'Aimeos\Aimeos\Base\View';

		if( isset( $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view'] ) ) {
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['aimeos']['aimeos_view'];
		}

		$object =  new $className();
		return $object->get( $context, $uriBuilder, $templatePaths, $request, $locale, $frontend );
	}


	/**
	 * Parses TypoScript configuration string.
	 *
	 * @param string $tsString TypoScript string
	 * @return array Mulit-dimensional, associative list of key/value pairs
	 * @throws Exception If parsing the configuration string fails
	 */
	public static function parseTS( $tsString )
	{
		$parser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\TypoScript\\Parser\\TypoScriptParser' );
		$parser->parse( $tsString );

		if( !empty( $parser->errors ) )
		{
			$msg = $GLOBALS['LANG']->sL( 'LLL:EXT:aimeos/Resources/Private/Language/Scheduler.xml:default.error.tsconfig.invalid' );
			throw new \Exception( $msg );
		}

		$tsConfig = self::convertTypoScriptArrayToPlainArray( $parser->setup );

		// Allows "plugin.tx_aimeos.settings." prefix everywhere
		if( isset( $tsConfig['plugin']['tx_aimeos']['settings'] )
			&& is_array( $tsConfig['plugin']['tx_aimeos']['settings'] )
		) {
			return $tsConfig['plugin']['tx_aimeos']['settings'];
		}

		return $tsConfig;
	}


	/**
	 * Removes dots from config keys (copied from Extbase TypoScriptService class available since TYPO3 6.0)
	 *
	 * @param array $typoScriptArray TypoScript configuration array
	 * @return array Multi-dimensional, associative list of key/value pairs without dots in keys
	 */
	protected static function convertTypoScriptArrayToPlainArray(array $typoScriptArray)
	{
		foreach ($typoScriptArray as $key => &$value) {
			if (substr($key, -1) === '.') {
				$keyWithoutDot = substr($key, 0, -1);
				$hasNodeWithoutDot = array_key_exists($keyWithoutDot, $typoScriptArray);
				$typoScriptNodeValue = $hasNodeWithoutDot ? $typoScriptArray[$keyWithoutDot] : NULL;
				if (is_array($value)) {
					$typoScriptArray[$keyWithoutDot] = self::convertTypoScriptArrayToPlainArray($value);
					if (!is_null($typoScriptNodeValue)) {
						$typoScriptArray[$keyWithoutDot]['_typoScriptNodeValue'] = $typoScriptNodeValue;
					}
					unset($typoScriptArray[$key]);
				} else {
					$typoScriptArray[$keyWithoutDot] = NULL;
				}
			}
		}
		return $typoScriptArray;
	}
}
