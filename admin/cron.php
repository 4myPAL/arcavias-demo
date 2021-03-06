<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://www.arcavias.com/en/license
*/

try
{
	if( $_SERVER['argc'] < 2 )
	{
		printf( 'Usage: %1$s "<job1> [<job2> ...]" ["<site> ..."]' . PHP_EOL, $_SERVER['argv'][0] );
		exit( 1 );
	}

	$jobnames = explode( ' ', $_SERVER['argv'][1] );
	$sites = ( isset( $_SERVER['argv'][2] ) ? explode( ' ', $_SERVER['argv'][2] ) : array( 'default' ) );


	date_default_timezone_set( 'UTC' );

	$appdir = dirname( __FILE__ )  . DIRECTORY_SEPARATOR;
	$basedir = dirname( $appdir ) . DIRECTORY_SEPARATOR;

	$includePaths = array(
		$basedir. 'zendlib',
		get_include_path(),
	);

	if( set_include_path( implode( PATH_SEPARATOR, $includePaths ) ) === false ) {
		throw new Exception( 'Unable to set include path' );
	}


	require_once $basedir . 'vendor/autoload.php';

	$configPaths = $arcavias->getConfigPaths( 'mysql' );
	$configPaths[] = $basedir . 'config';
	$configPaths[] = $appdir . 'config';

	$arcavias = new Arcavias( array( $basedir . 'ext' ) );
	$jobs = new Jobs( $arcavias, $configPaths );
	$jobs->execute( $jobnames, $sites );
}
catch( Exception $e )
{
	error_log( sprintf( 'Caught exception: "%1$s"', $e->getMessage() ) );
	error_log( $e->getTraceAsString() );
}
