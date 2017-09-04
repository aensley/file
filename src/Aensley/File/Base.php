<?php

namespace Aensley\File;

/**
 * Base File Manipulation Utilities. Not intended to be used directly for most cases.
 *
 * @package Aensley/File
 * @author  Andrew Ensley
 */
class Base
{


	/**
	 * Returns the name of the directory containing the file/directory.
	 *
	 * @param string $path The absolute path to check.
	 *
	 * @return string The name of the directory containing the file/directory.
	 */
	public static function directoryName($path)
	{
		return pathinfo($path, PATHINFO_DIRNAME);
	}


	/**
	 * Returns the base name of the file/directory.
	 *
	 * @param string $path The absolute path to check.
	 *
	 * @return string The base name of the file/directory.
	 */
	public static function baseName($path)
	{
		return pathinfo($path, PATHINFO_BASENAME);
	}


	/**
	 * Checks if the given path (file, directory, or link) exists.
	 *
	 * @param string $path The absolute path to check.
	 *
	 * @return bool True if it exists. False if not.
	 */
	public static function exists($path)
	{
		return (!empty($path) && file_exists($path));
	}


	/**
	 * Checks if the given path (file, directory, or link) exists and is readable.
	 *
	 * @param string $path The absolute path to check.
	 *
	 * @return bool True if it exists and is readable.
	 */
	public static function isReadable($path)
	{
		return (self::exists($path) && is_readable($path));
	}


	/**
	 * Checks if the given path (file, directory, or link) exists and is writable.
	 *
	 * @param string $path The absolute path to check.
	 *
	 * @return bool True if it exists and is writable.
	 */
	public static function isWritable($path)
	{
		return (self::exists($path) && is_writable($path));
	}


	/**
	 * Moves a file/directory from $source to $target.
	 *
	 * @param string         $source The absolute path of the source to move.
	 * @param string         $target The absolute path to move the source to.
	 * @param bool[optional] $rename Set to true to automatically rename the target to avoid overwriting an existing file/directory.
	 *
	 * @return string The absolute path to where the file was moved on success. Empty string on failure.
	 */
	public static function move($source, $target, $rename = false)
	{
		if (!self::isReadable($source) || empty($target)) {
			// Bad source or target.
			return '';
		}

		$extension = '';
		if (is_file($source) && $rename) {
			$extension = File::extension($target);
			// Keep file name short enough to allow for up to 9,999 of the same file name without collision.
			$targetFile = substr(File::name($target), 0, (255 - (strlen($extension) + 6)));
		} else {
			$targetFile = self::baseName($target);
		}

		$targetDirectory = self::directoryName($target) . DIRECTORY_SEPARATOR;
		if (!Directory::isWritable($targetDirectory) && !Directory::create($targetDirectory)) {
			//	Target directory does not exist or is unwritable.
			return '';
		}

		$target = $targetDirectory . $targetFile . ($extension ? '.' . $extension : '');
		if ($rename && self::exists($target)) {
			$counter = 0;
			do {
				$target = $targetDirectory . $targetFile . '_' . $counter++ . ($extension ? '.' . $extension : '');
			} while (self::exists($target) && $counter < 10000);

			if (self::exists($target)) {
				// Could not find an available target (tried 10,000 variations).
				return '';
			}
		}

		if (rename($source, $target)) {
			return $target;
		}

		// Could not move $source to $target
		return '';
	}
}
