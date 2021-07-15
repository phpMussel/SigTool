<?php
/**
 * Zip handler (last modified: 2021.07.10).
 * Source: https://github.com/phpMussel/SigTool/blob/master/Zip.php
 *
 * Adapted from phpMussel's ZipHandler class.
 * Source: https://github.com/phpMussel/Core/blob/v3/src/ZipHandler.php
 *
 * Package location: GitHub <https://github.com/phpMussel/SigTool>.
 * Author: Caleb M (Maikuolan) <https://github.com/Maikuolan>.
 */

namespace phpMussel\SigTool;

class Zip
{
    /**
     * @var int The instance's error state (in case something goes wrong).
     *
     * -1: Object not constructed (default state; shouldn't normally be seen).
     * 0: Object constructed successfully. No problems, as far as we know.
     * 1: Necessary prerequisites/extensions aren't installed/available.
     * 2: Pointer isn't valid, isn't accessible, or failed to open/stream.
     */
    public $ErrorState = -1;

    /**
     * @var \ZipArchive The zip object.
     */
    private $ZipObject;

    /**
     * @var int The number of files in the archive.
     */
    private $NumFiles = 0;

    /**
     * @var int The current entry index.
     */
    private $Index = -1;

    /**
     * @var array The current entry's attributes.
     */
    private $StatIndex = [];

    /**
     * Construct the zip archive object.
     *
     * @param string $Pointer
     * @return void
     */
    public function __construct($Pointer)
    {
        /** Zip class requirements guard. */
        if (!class_exists('\ZipArchive')) {
            $this->ErrorState = 1;
            return;
        }

        /** Bad pointer guard. */
        if (!is_readable($Pointer)) {
            $this->ErrorState = 2;
            return;
        }

        $this->ZipObject = new \ZipArchive();
        if (!$this->ZipObject->open($Pointer)) {
            $this->ErrorState = 2;
            return;
        }
        $this->ErrorState = is_object($this->ZipObject) ? 0 : 2;
        $this->NumFiles = $this->ZipObject->numFiles;
    }

    /**
     * Destruct the Zip archive object.
     *
     * @return void
     */
    public function __destruct()
    {
        if (is_object($this->ZipObject) && $this->ErrorState === 0) {
            $this->ZipObject->close();
        }
    }

    /**
     * Return the actual entry in the archive at the current entry pointer.
     *
     * @param int $Bytes Optionally, how many bytes to read from the entry.
     * @return string The entry's content, or an empty string if not available.
     */
    public function EntryRead($Bytes = -1)
    {
        $Actual = $this->EntryActualSize();
        if ($Bytes < 0 || $Bytes > $Actual) {
            $Bytes = $Actual;
        }
        return $Bytes > 0 ? $this->ZipObject->getFromIndex($this->Index, $Bytes) : '';
    }

    /**
     * Return the compressed size of the entry at the current entry pointer.
     */
    public function EntryCompressedSize()
    {
        return isset($this->StatIndex['comp_size']) ? $this->StatIndex['comp_size'] : 0;
    }

    /**
     * Return the actual size of the entry at the current entry pointer.
     */
    public function EntryActualSize()
    {
        return isset($this->StatIndex['size']) ? $this->StatIndex['size'] : 0;
    }

    /**
     * Return whether the entry at the current entry pointer is a directory.
     *
     * @return bool True = Is a directory. False = Isn't a directory.
     */
    public function EntryIsDirectory()
    {
        return (!$this->EntryActualSize() && !$this->EntryCompressedSize() && substr($this->EntryName(), -1) === '/');
    }

    /**
     * Return whether the entry at the current entry pointer is encrypted.
     *
     * @return bool True = Is encrypted. False = Isn't encrypted.
     */
    public function EntryIsEncrypted()
    {
        return !empty($this->StatIndex['encryption_method']);
    }

    /**
     * Return the reported internal CRC hash for the entry, if it exists.
     *
     * @return string
     */
    public function EntryCRC()
    {
        return (isset($this->StatIndex['crc']) && is_int($this->StatIndex['crc'])) ? dechex($this->StatIndex['crc']) : '';
    }

    /**
     * Return the name of the entry at the current entry pointer.
     *
     * @return string The name of the entry at the current entry pointer, or an
     *      empty string if there's no entry or if the entry pointer is invalid.
     */
    public function EntryName()
    {
        return (isset($this->StatIndex['name']) && is_string($this->StatIndex['name'])) ? $this->StatIndex['name'] : '';
    }

    /**
     * Move the entry pointer ahead.
     *
     * @return bool False if there aren't any more entries.
     */
    public function EntryNext()
    {
        $this->Index++;
        if ($this->Index < $this->NumFiles) {
            $this->StatIndex = $this->ZipObject->statIndex($this->Index);
            return true;
        }
        return false;
    }
}
