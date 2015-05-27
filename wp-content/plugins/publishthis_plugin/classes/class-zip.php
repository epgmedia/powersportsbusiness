<?php

/**
 * Compress text data into zip format
 */

class Publishthis_Zip {

	private $datasec = array();
	private $ctrl_dir = array();
	private $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	private $old_offset = 0;

	/**
	 * Publishthis constructor.
	 */
	function __construct() {
	}

	/**
	 * Add specified folder to zip format
	 *
	 * @param string  $name Folder name
	 */
	function add_dir( $name ) {

		$name = str_replace( "\\", "/", $name );

		$out = "\x50\x4b\x03\x04";
		$out .= "\x0a\x00";
		$out .= "\x00\x00";
		$out .= "\x00\x00";
		$out .= "\x00\x00\x00\x00";
		$out .= pack( "V", 0 );
		$out .= pack( "V", 0 );
		$out .= pack( "V", 0 );
		$out .= pack( "v", strlen( $name ) );
		$out .= pack( "v", 0 );
		$out .= $name;
		$out .= pack( "V", $crc );
		$out .= pack( "V", $c_len );
		$out .= pack( "V", $unc_len );

		$this -> datasec[] = $out;

		$new_offset = strlen( implode( "", $this->datasec ) );

		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .="\x00\x00";
		$cdrec .="\x0a\x00";
		$cdrec .="\x00\x00";
		$cdrec .="\x00\x00";
		$cdrec .="\x00\x00\x00\x00";
		$cdrec .= pack( "V", 0 );
		$cdrec .= pack( "V", 0 );
		$cdrec .= pack( "V", 0 );
		$cdrec .= pack( "v", strlen( $name ) );
		$cdrec .= pack( "v", 0 );
		$cdrec .= pack( "v", 0 );
		$cdrec .= pack( "v", 0 );
		$cdrec .= pack( "v", 0 );
		$ext = "\x00\x00\x10\x00";
		$ext = "\xff\xff\xff\xff";
		$cdrec .= pack( "V", 16 );

		$cdrec .= pack( "V", $this -> old_offset );

		$this -> old_offset = $new_offset;

		$cdrec .= $name;

		$this -> ctrl_dir[] = $cdrec;
	}

	/**
	 * Compress data to zip format
	 *
	 * @param string  $data Text data to compress
	 * @param string  $name File name compressed to zip
	 */
	function add_file( $data, $name ) {

		$name = str_replace( "\\", "/", $name );

		$out = "\x50\x4b\x03\x04";
		$out .= "\x14\x00";
		$out .= "\x00\x00";
		$out .= "\x08\x00";
		$out .= "\x00\x00\x00\x00";

		$unc_len = strlen( $data );

		$crc = crc32( $data );

		$zdata = gzcompress( $data );
		$zdata = substr( substr( $zdata, 0, strlen( $zdata ) - 4 ), 2 ); // fix crc bug

		$c_len = strlen( $zdata );

		$out .= pack( "V", $crc );
		$out .= pack( "V", $c_len );
		$out .= pack( "V", $unc_len );
		$out .= pack( "v", strlen( $name ) );
		$out .= pack( "v", 0 );
		$out .= $name;
		$out .= $zdata;
		$out .= pack( "V", $crc );
		$out .= pack( "V", $c_len );
		$out .= pack( "V", $unc_len );

		$this->datasec[] = $out;

		$new_offset = strlen( implode( "", $this->datasec ) );

		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .= "\x00\x00";
		$cdrec .= "\x14\x00";
		$cdrec .= "\x00\x00";
		$cdrec .= "\x08\x00";
		$cdrec .= "\x00\x00\x00\x00";
		$cdrec .= pack( "V", $crc );
		$cdrec .= pack( "V", $c_len );
		$cdrec .= pack( "V", $unc_len );
		$cdrec .= pack( "v", strlen( $name ) );
		$cdrec .= pack( "v", 0 );
		$cdrec .= pack( "v", 0 );
		$cdrec .= pack( "v", 0 );
		$cdrec .= pack( "v", 0 );
		$cdrec .= pack( "V", 32 );
		$cdrec .= pack( "V", $this -> old_offset );

		$this->old_offset = $new_offset;

		$cdrec .= $name;

		$this -> ctrl_dir[] = $cdrec;
	}

	/**
	 * Returns compressed data as plain text
	 */
	function plain_text() {

		$data = implode( "", $this -> datasec );

		$ctrldir = implode( "", $this -> ctrl_dir );

		return $data . $ctrldir . $this -> eof_ctrl_dir .
			pack( "v", sizeof( $this -> ctrl_dir ) ) .
			pack( "v", sizeof( $this -> ctrl_dir ) ) .
			pack( "V", strlen( $ctrldir ) ) .
			pack( "V", strlen( $data ) ) .
			"\x00\x00";
	}

	/**
	 * Returns compressed data as zip file
	 */
	function file( $filename='publishthis.zip' ) {
		// Set headers
		header( "Content-type: application/octet-stream" );
		header( "Content-disposition: attachment; filename=".$filename );

		// Output compressed data
		echo $this->plain_text();
	}
}
