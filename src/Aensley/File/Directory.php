<?php

namespace Aensley\File;

/**
 * Directory Manipulation Utilities
 *
 * @package Aensley/File
 * @author  Andrew Ensley
 */
class Directory extends Base
{


	/**
	 * Creates the given directory.
	 *
	 * @param string        $directory The absolute path to the directory to create.
	 * @param int[optional] $umask     The umask to use when creating the directory.
	 *
	 * @return bool True on success. False on failure.
	 */
	public static function create($directory, $umask = 0755)
	{
		return (!empty($directory) && mkdir($directory, $umask, true));
	}


	/**
	 * Recursively delete a directory and its contents.
	 *
	 * @param string $directory The absolute path to the directory to delete.
	 *
	 * @return bool True on success. False on failure.
	 */
	public static function delete($directory)
	{
		$directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		if (self::exists($directory)) {
			if (self::isWritable($directory)) {
				// Skip virtual paths.
				$files = array_diff(scandir($directory), array('.', '..'));
				foreach ($files as $file) {
					$file = $directory . $file;
					if (is_dir($file)) {
						// Recursion
						if (!self::delete($file)) {
							// Couldn't delete a subdirectory.
							return false;
						}

						continue;
					}

					if (!File::delete($file)) {
						// Couldn't delete a file inside the directory.
						return false;
					}
				}

				// Directory is empty
				return rmdir($directory);
			}

			// Directory is not writable. Won't be able to delete.
			return false;
		}

		// No directory to delete. Technically a success.
		return true;
	}


	/**
	 * Checks if the given path exists and is a directory.
	 *
	 * @param string $directory The absolute path to the directory to check.
	 *
	 * @return bool True if the directory exists. False otherwise.
	 */
	public static function exists($directory)
	{
		return (parent::exists($directory) && is_dir($directory));
	}


	/**
	 * Checks if the given path exists, is a directory (not a file or link), and is writable.
	 *
	 * @param string $directory The absolute path to the directory to check.
	 *
	 * @return bool True if the path exists, is a directory, and is writable. False otherwise.
	 */
	public static function isWritable($directory)
	{
		return (parent::isWritable($directory) && is_dir($directory));
	}


	/**
	 * Checks if the given path exists, is a directory (not a file or link), and is readable.
	 *
	 * @param string $path The absolute path to check to the directory to check.
	 *
	 * @return bool True if path exists, is a directory (not a file or link), and is readable.
	 */
	public static function isReadable($path)
	{
		return (parent::isReadable($path) && is_dir($path));
	}


	/**
	 * Scans a directory for files. Returns what is found as an array of absolute file paths.
	 *
	 * @param string          $directory       The directory to scan. Must be an absolute path.
	 * @param bool[optional]  $recursive       Set to true to list files recursively within the directory.
	 * @param array[optional] $validExtensions Specify valid file extensions to filter by. Leave empty for all.
	 *
	 * @return array The absolute paths of files contained within the directory.
	 */
	public static function listFiles($directory, $recursive = false, $validExtensions = array())
	{
		$returnFiles = array();
		$directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		if (self::exists($directory)) {
			$files = array_diff(scandir($directory), array('.', '..'));
			foreach ($files as $file) {
				$file = $directory . $file;
				if (is_link($file)) {
					// Do not follow links.
					continue;
				} elseif (is_dir($file)) {
					if ($recursive) {
						// Recursion.
						$dirFiles = self::listFiles($file, $recursive, $validExtensions);
						// Add files found in sub-directory.
						$returnFiles = array_merge($returnFiles, $dirFiles);
					}

					// Do not add directories.
					continue;
				}

				// We know it's a regular file at this point.
				if (!empty($validExtensions) && !in_array(File::extension($file), $validExtensions)) {
					// Invalid file extension.
					continue;
				}

				// Passed all the exclusion tests. Add it to the list.
				$returnFiles[] = $file;
			}
		}

		// Return what we found.
		return $returnFiles;
	}
}
