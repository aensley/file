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
        return !empty($directory) && mkdir($directory, $umask, true);
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
        $directory = rtrim($directory, DIRECTORY_SEPARATOR);
        if (is_link($directory)) {
            return unlink($directory);
        }

        $directory .= DIRECTORY_SEPARATOR;
        if (!self::exists($directory)) {
            // No directory to delete. Technically a success.
            return true;
        }

        // False if not writable — short-circuits rmdir at the end without entering the loop.
        $allDeleted = self::isWritable($directory);
        if ($allDeleted) {
            // Skip virtual paths.
            $files = array_diff(scandir($directory), ['.', '..']);
            foreach ($files as $file) {
                $allDeleted = self::deleteEntry($directory . $file);
                if (!$allDeleted) {
                    break;
                }
            }
        }

        return $allDeleted && rmdir($directory);
    }


    /**
     * Deletes a single file system entry (link, directory, or file).
     *
     * @param string $file The absolute path to the entry to delete.
     *
     * @return bool True on success. False on failure.
     */
    private static function deleteEntry($file)
    {
        if (is_link($file)) {
            return unlink($file);
        }

        if (is_dir($file)) {
            return self::delete($file);
        }

        return File::delete($file);
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
        return parent::exists($directory) && is_dir($directory);
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
        return parent::isWritable($directory) && is_dir($directory);
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
        return parent::isReadable($path) && is_dir($path);
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
    public static function listFiles($directory, $recursive = false, $validExtensions = [])
    {
        $returnFiles = [];
        $directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (!self::exists($directory)) {
            return $returnFiles;
        }

        $files = array_diff(scandir($directory), ['.', '..']);
        foreach ($files as $file) {
            $file = $directory . $file;
            if (is_link($file)) {
                // Do not follow links.
                continue;
            }

            if (is_dir($file)) {
                if ($recursive) {
                    array_push($returnFiles, ...self::listFiles($file, $recursive, $validExtensions));
                }

                continue;
            }

            if (!empty($validExtensions) && !in_array(File::extension($file), $validExtensions)) {
                continue;
            }

            $returnFiles[] = $file;
        }

        return $returnFiles;
    }
}
