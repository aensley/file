<?php

namespace Aensley\File;

/**
 * File Manipulation Utilities
 *
 * @package Aensley/File
 * @author  Andrew Ensley
 */
class File extends Base
{
    /**
     * Returns the extension of the file (without the '.').
     *
     * @param string $file The file name.
     *
     * @return string The extension.
     */
    public static function extension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }


    /**
     * Returns the name of the file without the extension.
     *
     * @param string $file The file name.
     *
     * @return string The name of the file without the extension.
     */
    public static function name($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }


    /**
     * Gets the file date/time from EXIF data in the requested format.
     *
     * @param string           $file   The absolute path to the file to check.
     * @param string[optional] $format The format to return the date/time in. Defaults to 'Y-m-d H:i:s'.
     *
     * @return string The date/time in the requested format if found. Empty string if not.
     */
    public static function exifDateTime($file, $format = 'Y-m-d H:i:s')
    {
        $exif = function_exists('exif_read_data') ? @exif_read_data($file, 'EXIF') : [];
        if (empty($exif)) {
            return '';
        }

        // Fields in which to find the date, in order of preference.
        $exifFields = ['DateTime', 'DateTimeOriginal', 'DateTimeDigitized'];
        foreach ($exifFields as $exifField) {
            if (empty($exif[$exifField])) {
                continue;
            }

            $date = trim($exif[$exifField]);
            if (preg_match('/^\d{4}\:\d{2}\:\d{2} \d{2}\:\d{2}\:\d{2}$/', $date)) {
                return \DateTime::createFromFormat('Y:m:d H:i:s', $date)->format($format);
            }
        }

        return '';
    }


    /**
     * Gets the file date/time from modified time.
     *
     * @param string           $file   The absolute path to the file to check.
     * @param string[optional] $format The format to return the date/time in. Defaults to 'Y-m-d H:i:s'.
     *
     * @return string The date/time in the requested format if found. Empty string if not.
     */
    public static function modifiedDateTime($file, $format = 'Y-m-d H:i:s')
    {
        $time = filemtime($file);
        if ($time) {
            return date($format, $time);
        }

        return '';
    }


    /**
     * Deletes a file.
     *
     * @param string $file The absolute path to the file to delete.
     *
     * @return bool True on success. False on failure.
     */
    public static function delete($file)
    {
        if (is_link($file)) {
            return unlink($file);
        }

        return self::isWritable($file) && unlink($file);
    }


    /**
     * Checks if the given string represents a file that exists and is a file (not a link or directory).
     *
     * @param string $file The absolute path to the file to check.
     *
     * @return bool True if the file exists and is a file. False if either are false.
     */
    public static function exists($file)
    {
        return parent::exists($file) && is_file($file);
    }


    /**
     * Checks if the given string represents a file that exists, is a file (not a link or directory), and is readable.
     *
     * @param string $file The absolute path to the file to check.
     *
     * @return bool True if the file exists, is a file, and is readable. False if any are false.
     */
    public static function isReadable($file)
    {
        return parent::isReadable($file) && is_file($file);
    }


    /**
     * Checks if the given string represents a file that exists, is a file (not a link or directory), and is writable.
     *
     * @param string $file The absolute path to the file to check.
     *
     * @return bool True if the file exists, is a file, and is writable. False if any are false.
     */
    public static function isWritable($file)
    {
        return parent::isWritable($file) && is_file($file);
    }
}
