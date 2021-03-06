<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://www.arcavias.com/en/license
 */

header( "Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'" );
header( "X-Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'" );
header( "X-Webkit-CSP: default-src 'self'; style-src 'self' 'unsafe-inline'" );


define( 'ZFAPP_ROOT', dirname( dirname( __FILE__ ) ) );
define( 'APPLICATION_PATH', ZFAPP_ROOT . DIRECTORY_SEPARATOR . 'application' );
define( 'APPLICATION_ENV', 'development' ); // development | production

if ( APPLICATION_ENV == 'development' ) {
	error_reporting( -1 );
	ini_set( 'display_errors', true );
}

setlocale( LC_CTYPE, 'en_US.UTF8' );

try
{
	require_once dirname( ZFAPP_ROOT ) . '/vendor/autoload.php';

	spl_autoload_register( 'Arcavias::autoload' );

	$includePaths = array(
		ZFAPP_ROOT . DIRECTORY_SEPARATOR . 'library',
		dirname( ZFAPP_ROOT ) . DIRECTORY_SEPARATOR . 'zendlib',
		get_include_path(),
	);
	set_include_path( implode( PATH_SEPARATOR, $includePaths ) );

	include_once ZFAPP_ROOT . '/data/cache/pluginLoaderCache.php';

	$application = new Application_Application(
		APPLICATION_ENV,
		include_once realpath( APPLICATION_PATH . '/configs/application.php' )
	);

	$application->bootstrap()->run();

} catch ( Zend_Controller_Exception $e ) {
	include 'errors/404.phtml';
} catch ( Exception $e ) {
	include 'errors/500.phtml';
}