<?php
class Debug
{
	public static function IsEnabled()
	{
		return (defined("DEBUG") && DEBUG);
	}

	public static function Write($message)
	{
		try {
			if (self::IsEnabled()) {
				$stderr = fopen("php://stderr", "w");
				fprintf($stderr, $message);
				fclose($stderr);
			}
		} catch (Exception $ex) {
			// Fall back to the shadows!
		}
	}

	public static function WriteLine($message)
	{
		self::Write($message . "\n");
	}

	public static function Dump($object)
	{
		Debug::WriteLine(var_export($object, true));
	}
}