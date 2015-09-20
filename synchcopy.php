<?php

/*

Copies a source directory to the destination directory.
Skips existing and zero size files.

Destination directory is the current working directory.
Can handle large directory sizes.

Usage:
php synchcopy.php SourceDirectory

*/

// Exit if source directory argument is not given
if( !isset( $argv[ 1 ] ) ) {

	print( "Source Directory Required!\n" ) ;
	exit ;

}

// Work out source and destination directories
// Source directory path is coming in from the command line parameter
// Destination computed from current working directory
$src = $argv[ 1 ] ;
$dest = getcwd( ) ;

// Strip trailing slash from source directory path if found
if( substr( $src , -1 ) == '/' ) $src = substr( $src , 0 , -1 ) ;

print( "Source = $src\n" ) ;
print( "Dest = $dest\n" ) ;

// Loop and gather all source directory file stats
$srcFiles = array( ) ;

if( $handle = opendir( $src ) ) {

    while( false !== ( $entry = readdir( $handle ) ) ) {

    	if( substr( $entry , 0 , 1 ) == '.' ) continue ;

		$srcFiles[ $entry ] = stat( "$src/$entry" ) ;    	

    }

    closedir( $handle ) ;

}

//print_r($srcFiles);

$srcTotal = count( $srcFiles ) ;

print( "Total Source = $srcTotal\n" ) ;


// Loop and gather all directory directory file stats
$destFiles = array( ) ;

if( $handle = opendir( $dest ) ) {

    while( false !== ( $entry = readdir( $handle ) ) ) {


      	if( substr( $entry , 0 , 1 ) == '.' ) continue ;

		$destFiles[ $entry ] = stat( "$dest/$entry" ) ;
		  	
    }

    closedir( $handle ) ;

}

$destTotal = count( $destFiles ) ;

print( "Total Destination = $destTotal\n" ) ;


// Generate copy commands
$cmds = array( ) ;

foreach( $srcFiles as $sf => $sk ) {

	$flagCopy = false ;

	// Skip if source file size is empty
	if( $sk[ 'size' ] == 0 ) continue ;

	// Get destination file size
	// Set to zero if it does not exist
	if( isset( $destFiles[ $sf ][ 'size' ] ) ) {

		$destFileSize = $destFiles[ $sf ][ 'size' ] ;

	} else {

		$destFileSize = 0 ;

	}

	// If destination file size is empty then add it to commands list
	if( $destFileSize == 0 ) {

		$cmds[ ] = "cp $src/$sf $dest/$sf" ;

	}

}

//print_r($cmds);

// Clue on number of commands to run
$totalCmds = count( $cmds ) ;

// Run all commands
foreach( $cmds as $k => $cmd ) {

	print( "[$k/$totalCmds] $cmd\n" ) ;
	system( $cmd ) ;	

}





