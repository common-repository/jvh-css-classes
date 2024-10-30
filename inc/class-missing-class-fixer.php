<?php

namespace JVH\CSS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MissingClassFixer {
	const FILE_PATH = WP_CONTENT_DIR . '/plugins/js_composer/include/classes/shortcodes/core/class-wpbakeryshortcode.php';

	const OLD_LINE = 'JGVsZW0gPSBzdHJfaXJlcGxhY2UoICcld3BiX2VsZW1lbnRfY29udGVudCUnLCAkdGhpcy0+Y3VzdG9tTWFya3VwKCAkbWFya3VwLCAkY29udGVudCApLCAkZWxlbSApOw==';
	const NEW_LINE = 'JGVsZW0gPSBzdHJfaXJlcGxhY2UoICcld3BiX2VsZW1lbnRfY29udGVudCUnLCAkdGhpcy0+Y3VzdG9tTWFya3VwKCAkbWFya3VwLCAkY29udGVudCApIC4gJHRoaXMtPnBhcmFtc0h0bWxIb2xkZXJzKCAkYXR0cyApLCAkZWxlbSApOyAvLyBKVkggcXVpY2tmaXg=';

	public function maybeFix()
	{
		if ( $this->shouldFix() ) {
			$this->fix();
		}
	}

	private function shouldFix()
	{
		return strpos( $this->getFileContent(), 'JVH quickfix' ) === false;
	}

	private function fix()
	{
		file_put_contents( self::FILE_PATH, $this->getFixedFileContent() );
	}

	private function getFileContent()
	{
		return file_get_contents( self::FILE_PATH );
	}

	private function getFixedLines()
	{
		$lines = $this->getLines();

		foreach ( $lines as $index => $line ) {
			$lines[$index] = str_replace( base64_decode( self::OLD_LINE ), base64_decode( self::NEW_LINE ), $lines[$index] );
		}

		return $lines;
	}

	private function getFixedFileContent()
	{
		return implode( '', $this->getFixedLines() );
	}

	private function getLines()
	{
		return file( self::FILE_PATH );
	}
}

$fixer = new MissingClassFixer();
$fixer->maybeFix();
