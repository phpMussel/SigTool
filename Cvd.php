<?php
/**
 * Cvd handler (last modified: 2022.02.12).
 * @link https://github.com/phpMussel/SigTool/blob/master/Cvd.php
 *
 * Adapted from phpMussel's TarHandler class.
 * @link https://github.com/phpMussel/Core/blob/v3/src/TarHandler.php
 *
 * @author Caleb M (Maikuolan) <https://github.com/Maikuolan>.
 */

namespace phpMussel\SigTool;

class Cvd
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
     * @var int Archive seek offset.
     */
    private $Offset = 0;

    /**
     * @var int The total size of the archive.
     */
    private $TotalSize = 0;

    /**
     * @var string The archive's actual content.
     */
    private $Data = '';

    /**
     * @var bool Whether we've initialised an entry yet.
     */
    private $Initialised = false;

    /**
     * Construct the tar archive object.
     *
     * @param string $Data The cvd data.
     * @return void
     */
    public function __construct(string $File)
    {
        /** Attempt to open the file. */
        if (
            !is_readable($File) ||
            !is_file($File) ||
            !is_resource(($Handle = fopen($File, 'rb')))
        ) {
            $this->ErrorState = 2;
            return;
        }

        /** Attempt to read the file. */
        while (!feof($Handle)) {
            $this->Data .= fread($Handle, 131072);
        }
        fclose($Handle);

        /** Set total size. */
        $this->TotalSize = strlen($this->Data);

        /** Guard. */
        if ($this->TotalSize <= 512) {
            $this->ErrorState = 2;
            return;
        }

        /** Attempt to decompress the cvd data. */
        $this->Data = gzdecode(substr($this->Data, 512));

        /** Pad the cvd data. */
        $this->Data .= str_repeat("\0", 512 - (strlen($this->Data) % 512));

        /** Adjust total size. */
        if (($this->TotalSize = strlen($this->Data)) < 1) {
            $this->ErrorState = 2;
            return;
        }

        /** All is good. */
        $this->ErrorState = 0;
    }

    /**
     * Return the actual entry in the archive at the current entry pointer.
     *
     * @param int $Bytes Optionally, how many bytes to read from the entry.
     * @return string The entry's content, or an empty string if not available.
     */
    public function EntryRead(int $Bytes = -1): string
    {
        $Actual = $this->EntryActualSize();
        if ($Bytes < 0 || $Bytes > $Actual) {
            $Bytes = $Actual;
        }
        return substr($this->Data, $this->Offset + 512, $Bytes);
    }

    /**
     * Return the actual size of the entry at the current entry pointer.
     *
     * @return int
     */
    public function EntryActualSize(): int
    {
        return octdec(preg_replace('/\D/', '', substr($this->Data, $this->Offset + 124, 12))) ?: 0;
    }

    /**
     * Return whether the entry at the current entry pointer is a directory.
     *
     * @return bool True = Is a directory. False = Isn't a directory.
     */
    public function EntryIsDirectory(): bool
    {
        $Name = $this->EntryName();
        $Separator = substr($Name, -1, 1);
        return (($Separator === "\\" || $Separator === '/') && $this->EntryActualSize() === 0);
    }

    /**
     * Return the name of the entry at the current entry pointer.
     *
     * @return string The name of the entry at the current entry pointer, or an
     *      empty string if there's no entry or if the entry pointer is invalid.
     */
    public function EntryName(): string
    {
        return preg_replace('/[^\x20-\xff]/', '', substr($this->Data, $this->Offset, 100));
    }

    /**
     * Move the entry pointer ahead.
     *
     * @return bool False if there aren't any more entries.
     */
    public function EntryNext(): bool
    {
        if (($this->Offset + 1024) > $this->TotalSize) {
            return false;
        }
        if (!$this->Initialised) {
            return ($this->Initialised = true);
        }
        $Actual = $this->EntryActualSize();
        $Blocks = $Actual > 0 ? ceil($Actual / 512) + 1 : 1;
        $this->Offset += $Blocks * 512;
        return true;
    }
}
