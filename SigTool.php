<?php
/**
 * SigTool v2.0.1 (last modified: 2025.03.20).
 * @link https://github.com/phpMussel/SigTool
 *
 * Generates signatures for phpMussel using main.cvd and daily.cvd from ClamAV.
 *
 * @author Caleb M (Maikuolan) <https://github.com/Maikuolan>.
 */

namespace phpMussel\SigTool;

require __DIR__ . '/CommonAbstract.php';
require __DIR__ . '/YAML.php';
require __DIR__ . '/Cvd.php';

class SigTool extends \Maikuolan\Common\YAML
{
    /**
     * @var string Script version.
     * @link https://github.com/phpMussel/SigTool/tags
     */
    public const VERSION = '2.0.1';

    /**
     * @var string Last modified date.
     */
    public const MODIFIED = '2025.03.20';

    /**
     * @var int Safe file chunk size for when reading files.
     */
    public const SAFE_READ_SIZE = 131072;

    /**
     * @var string SigTool YAML data pre-processed raw data.
     */
    private $Raw = '';

    /**
     * @var string Most recent line sent to output.
     */
    private $RecentLine = '';

    /**
     * Parse locally.
     *
     * @return bool
     */
    public function readIn(): bool
    {
        $Arr = &$this->Data;
        $Raw = $this->Raw;
        return $this->process($Raw, $Arr);
    }

    /**
     * Set raw data.
     *
     * @param string $Raw The raw data.
     * @return void
     */
    public function setRaw(string $Raw)
    {
        if (substr($Raw, 0, 4) === "---\n") {
            $Raw = substr($Raw, 4);
        }
        $this->Raw = $Raw;
    }

    /**
     * Apply shorthand to signature names and remove any unwanted lines.
     *
     * @param string $Data The verbatim signature name or identifier.
     * @return void
     */
    public function shorthand(string &$Data)
    {
        while (true) {
            $Check = hash('sha256', $Data) . ':' . strlen($Data);
            foreach ([
                ["\x11", 'Win'],
                ["\x12", 'W(?:in)?32'],
                ["\x13", 'W(?:in)?64'],
                ["\x14", '(?:ELF|Linux)'],
                ["\x15", '(?:Macho|OSX)'],
                ["\x16", 'Andr(?:oid)?'],
                ["\x17", '(?:E?mail|EML)'],
                ["\x18", '(?:Javascript|JS|Jscript)'],
                ["\x19", 'Java'],
                ["\x1A", 'XXE'],
                ["\x1B", '(?:Graphics|JPE?G|GIF|PNG)'],
                ["\x1C", '(?:Macro|OLE)'],
                ["\x1D", 'HTML?'],
                ["\x1E", 'RTF'],
                ["\x1F", '(?:Archive|[RT]AR|ZIP)'],
                ["\x20", 'PHP'],
                ["\x21", 'XML'],
                ["\x22", 'ASPX?'],
                ["\x23", 'VB[SEX]?'],
                ["\x24", 'BAT'],
                ["\x25", 'PDF'],
                ["\x26", 'SWF'],
                ["\x27", 'W97M?'],
                ["\x28", 'X97M?'],
                ["\x29", 'O97M?'],
                ["\x2A", 'ASCII'],
                ["\x2B", 'Unix'],
                ["\x2C", 'Py(?:thon)?'],
                ["\x2D", 'Perl'],
                ["\x2E", 'Ruby'],
                ["\x2F", '(?:CFG|IN[IF])'],
                ["\x30", 'CGI'],
            ] as $Param) {
                $Data = preg_replace([
                    "~\x10\x10" . $Param[1] . '[-.]~i',
                    "~\x10\x10([a-z0-9]+[._-])" . $Param[1] . '[-.]~i'
                ], [
                    $Param[0] . "\x10",
                    $Param[0] . "\x10\\1"
                ], $Data);
            }
            foreach ([
                ["\x11", 'Worm'],
                ["\x12", 'Tro?[jy]a?n?'],
                ["\x13", 'Ad(?:ware)?'],
                ["\x14", 'Flooder'],
                ["\x15", 'IRC(?:Bot)?'],
                ["\x16", 'Exp?l?o?i?t?'],
                ["\x17", 'VirTool'],
                ["\x18", 'Dial(?:er)?'],
                ["\x19", '(?:Joke|Hoax)'],
                ["\x1B", 'Malware'],
                ["\x1C", 'Risk(?:ware|y)?'],
                ["\x1D", '(?:Rkit|Rootkit|Root)'],
                ["\x1E", '(?:Backdoor|Back|BD|Door)'],
                ["\x1F", '(?:Hack|Hacktool|HT)'],
                ["\x20", '(?:Key)?logger'],
                ["\x21", 'Ransom(?:ware)?'],
                ["\x22", 'Spy(?:ware)?'],
                ["\x23", 'Vir(?:us)?'],
                ["\x24", 'Dropper'],
                ["\x25", 'Dropped'],
                ["\x26", '(?:Dldr|Downloader)'],
                ["\x27", 'Obfuscation'],
                ["\x28", 'Obfuscator'],
                ["\x29", 'Obfuscated'],
                ["\x2A", 'Packer'],
                ["\x2B", 'Packed'],
                ["\x2C", 'PU[AP]'],
                ["\x2D", 'Shell'],
                ["\x2E", 'Defacer'],
                ["\x2F", 'Defacement'],
                ["\x30", 'Crypt(?:ed|or)?'],
                ["\x31", 'Phish'],
                ["\x32", 'Spam'],
                ["\x33", 'Spammer'],
                ["\x34", 'Scam'],
                ["\x35", 'ZipBomb'],
                ["\x36", 'Fork(?:Bomb)?'],
                ["\x37", 'LogicBomb'],
                ["\x38", 'CyberBomb'],
                ["\x39", 'Malvertisement'],
                ["\x3D", 'Encrypted'],
                ["\x3F", 'BadURL'],
            ] as $Param) {
                $Data = preg_replace([
                    "~\x10" . $Param[1] . '[-.]~i',
                    "~\x10([a-z0-9]+[._-])" . $Param[1] . '[-.]~i'
                ], [
                    $Param[0],
                    $Param[0] . '\1'
                ], $Data);
            }
            $Data = preg_replace([
                /** Let's reduce our footprint. :-) */
                '~([^a-z0-9])(?:Agent|General|Generic)([.-])~i',

                /** CVDs use both; Let's normalise it. */
                '~([^a-z0-9])Downloader([.-])~i',

                /** ClamAV signature format documentation is unclear about what "[]" means. */
                '~^[^\:\n]+\:[^\n]+[\[\]][^\n]*$~m',

                /** PCRE trips over capture groups at this range sometimes. Let's play it safe and ditch the affected signatures. */
                '~^.*\{(?:-?\d{4,}|\d{4,}-)\}.*$\n~m',

                /** Not needed in the final generated signature files. */
                '~^.*This ClamAV version has reached End of Life.*$\n~im'
            ], [
                '\1X\2',
                '\1Dldr\2',
                '',
                '',
                ''
            ], $Data);
            if (hash('sha256', $Data) . ':' . strlen($Data) === $Check) {
                break;
            }
        }
    }

    /**
     * Fix path (we get funky results for "__DIR__ . '/'" in some cases, on some systems).
     *
     * @param string $Path
     * @return string
     */
    public function fixPath(string $Path): string
    {
        return str_replace(['\/', '\\', '/\\'], '/', $Path);
    }

    /**
     * Output message.
     *
     * @param string $Message The message to output.
     * @param bool $NewLine Whether to print to a new line.
     * @return void
     */
    public function outputMessage(string $Message = '', bool $NewLine = false)
    {
        if ($Message) {
            $this->RecentLine = wordwrap($Message, 64, "\n ");
        }
        if ($NewLine) {
            echo "\n" . $this->RecentLine . ' <RAM ' . $this->formatSize(memory_get_usage()) . '>';
            return;
        }
        echo "\r" . str_repeat(' ', 76) . "\r" . $this->RecentLine . ' <RAM ' . $this->formatSize(memory_get_usage()) . '>';
    }

    /**
     * Format size.
     *
     * @param int $Size
     * @return string Formatted size.
     */
    private function formatSize(int $Size): string
    {
        $Scale = ['bytes', 'KB', 'MB', 'GB', 'TB'];
        $Iterate = 0;
        while ($Size > 1024) {
            $Size /= 1024;
            $Iterate++;
            if ($Iterate > 3) {
                break;
            }
        }
        return number_format($Size, $Iterate === 0 ? 0 : 2) . ' ' . $Scale[$Iterate];
    }
}

/** Fetch arguments. */
$RunMode = !empty($argv[1]) ? strtolower($argv[1]) : '';

/** Instantiate the SigTool object. */
$SigTool = new SigTool();

/** L10N. */
$L10N = [
    'Help' => sprintf(
        " SigTool v%s (last modified: %s).\n\n%s",
        SigTool::VERSION,
        SigTool::MODIFIED,
        " Generates signatures for phpMussel using main.cvd and daily.cvd from ClamAV.\n\n" .
        " Syntax:\n  \$ php SigTool.php [arguments]\n\n Example:\n  php SigTool.php xp\n\n" .
        " Arguments (all are OFF by default; include to turn ON):\n" .
        "  - No arguments: Display this help information.\n" .
        "  - x: Extract signature files from \"daily.cvd\" and \"main.cvd\".\n" .
        "  - p: Process signature files for use with phpMussel.\n\n"
    ),
    'Accessing' => ' Accessing %s ...',
    'Decompressing' => ' Decompressing %s ...',
    'Deleting' => ' Deleting %s ...',
    'Done' => " Done!",
    'Extracting_to_Cvd' => ' Extracting contents from %s to Cvd object ...',
    'Failed' => " Failed!",
    'Processing' => ' Processing ...',
    'Writing' => ' Writing %s ...',
    '_Error_Corrupted' => ' Reading "%2$s" failed at line "%1$d"! "%2$s" could be corrupted! SigTool terminated.',
    '_Error_Other' => ' Error at line "%d"! SigTool terminated.',
    '_Error_Reading' => ' Reading from "%2$s" failed at line "%1$d"! SigTool terminated.',
    '_Error_Writing' => ' Writing to "%2$s" failed at line "%1$d"! SigTool terminated.'
];

/**
 * Terminate with debug information.
 */
$Terminate = function ($Err = '_Error_Other', $Msg = '') use (&$SigTool, &$L10N) {
    $Debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
    $SigTool->outputMessage(sprintf($L10N[$Err] ?? $L10N['_Error_Other'], $Debug['line'], $Msg), true);
    echo "\n\n";
    die;
};

/**
 * Display help information.
 */
if ($RunMode === '') {
    die($L10N['Help']);
}

/**
 * We'll use Zürich time for our timezone (closest approximate to CET, and
 * required for our "Y.z.B" dates to actually make sense).
 */
date_default_timezone_set('Europe/Zurich');

/**
 * Extract ClamAV signature files from "daily.cvd" and "main.cvd" packages.
 */
if (strpos($RunMode, 'x') !== false) {
    foreach (['daily.cvd', 'main.cvd'] as $Set) {
        $File = $SigTool->fixPath(__DIR__ . '/' . $Set);

        /** Terminate if the file is missing or unreadable. */
        if (!file_exists($File) || !is_readable($File)) {
            $Terminate('_Error_Reading', $Set);
        }

        $SigTool->outputMessage(sprintf($L10N['Decompressing'], $Set), true);
        $Files = new Cvd($File);
        $SigTool->outputMessage(sprintf($L10N['Decompressing'], $Set) . $L10N['Done']);
        $SigTool->outputMessage(sprintf($L10N['Extracting_to_Cvd'], $Set), true);
        if ($Files->ErrorState !== 0) {
            $Terminate('_Error_Corrupted', $File);
        }
        $SigTool->outputMessage(sprintf($L10N['Extracting_to_Cvd'], $Set) . $L10N['Done']);
        while (true) {
            $Name = $Files->EntryName();
            $Data = $Files->EntryRead();
            if ($Name !== '' && $Data !== '' && $Files->EntryIsDirectory() === false) {
                $SigTool->outputMessage(sprintf($L10N['Writing'], $Name), true);
                $Handle = fopen($SigTool->fixPath(__DIR__ . '/' . $Name), 'wb');
                if (!is_resource($Handle)) {
                    $Terminate('_Error_Writing', $Name);
                }
                fwrite($Handle, $Data);
                fclose($Handle);
                $SigTool->outputMessage(sprintf($L10N['Writing'], $Name) . $L10N['Done']);
            }
            if ($Files->EntryNext() === false) {
                break;
            }
        }
    }

    /** Cleanup. */
    unset($Handle, $Data, $Name, $Files, $File, $Set);
}

/**
 * Process signature files for use with phpMussel.
 */
if (strpos($RunMode, 'p') !== false) {
    /** Check if signatures.dat exists; If so, we'll read it for updating. */
    if (is_readable(($DatFile = $SigTool->fixPath(__DIR__ . '/signatures.dat')))) {
        $SigTool->outputMessage(sprintf($L10N['Accessing'], 'signatures.dat'), true);
        $Handle = fopen($DatFile, 'rb');
        $SigTool->setRaw(fread($Handle, filesize($DatFile)));
        fclose($Handle);
        $SigTool->readIn();
        $Meta = &$SigTool->Data;
        $SigTool->outputMessage(sprintf($L10N['Accessing'], 'signatures.dat') . $L10N['Done']);
    }

    /** Don't need these (not currently used by this tool or by phpMussel). */
    foreach ([
        'COPYING',
        'daily.cdb',
        'daily.cfg',
        'daily.crb',
        'daily.fp',
        'daily.ftm',
        'daily.hdu',
        'daily.hsb',
        'daily.hsu',
        'daily.idb',
        'daily.ign',
        'daily.ign2',
        'daily.info',
        'daily.ldb',
        'daily.ldu',
        'daily.mdu',
        'daily.msb',
        'daily.msu',
        'daily.ndu',
        'daily.pdb',
        'daily.sfp',
        'daily.wdb',
        'main.cdb',
        'main.crb',
        'main.fp',
        'main.hdu',
        'main.hsb',
        'main.info',
        'main.ldb',
        'main.msb',
        'main.sfp',
    ] as $File) {
        if (file_exists($SigTool->fixPath(__DIR__ . '/' . $File))) {
            $SigTool->outputMessage(sprintf($L10N['Deleting'], $File), true);
            $SigTool->outputMessage(sprintf($L10N['Deleting'], $File) . (unlink($SigTool->fixPath(__DIR__ . '/' . $File)) ? $L10N['Done'] : $L10N['Failed']));
        }
    }

    /** Main sequence. */
    foreach ([
        ['daily.hdb', 'main.hdb', '~([\da-f]{32}\:\d+\:)([^\n]+)\n~', "\\1\x1A\x20\x10\x10\\2\n", 'clamav.hdb', "\x20", 16777216],
        ['daily.mdb', 'main.mdb', '~(\d+\:[\da-f]{32}\:)([^\n]+)\n~', "\\1\x1A\x20\x10\x10\\2\n", 'clamav.mdb', "\xA0", 16777216],
        ['daily.ndb', 'main.ndb', '~^([^:\n]+\:)~m', "\x1A\x20\x10\x10\\1", 'clamav.ndb', false, 0],
    ] as $Set) {
        /** Fetch and build. */
        if (file_exists($SigTool->fixPath(__DIR__ . '/' . $Set[0])) && file_exists($SigTool->fixPath(__DIR__ . '/' . $Set[1]))) {
            $UseMains = false;
            $FileData = '';
            $Size = 0;
            $SigTool->outputMessage(sprintf($L10N['Accessing'], $Set[0]), true);
            if (!is_resource($Handle = fopen($SigTool->fixPath(__DIR__ . '/' . $Set[0]), 'rb'))) {
                $SigTool->outputMessage(sprintf($L10N['Accessing'], $Set[0]) . $L10N['Failed']);
            } else {
                $Size += filesize($SigTool->fixPath(__DIR__ . '/' . $Set[0]));
                if ($Set[6] > 0 && $Size > $Set[6]) {
                    fseek($Handle, $Size - $Set[6]);
                } else {
                    $UseMains = true;
                }
                while (!feof($Handle)) {
                    $FileData .= fread($Handle, SigTool::SAFE_READ_SIZE);
                }
                fclose($Handle);
                $SigTool->outputMessage(sprintf($L10N['Accessing'], $Set[0]) . $L10N['Done']);
            }
            if ($UseMains) {
                $SigTool->outputMessage(sprintf($L10N['Accessing'], $Set[1]), true);
                if (!is_resource($Handle = fopen($SigTool->fixPath(__DIR__ . '/' . $Set[1]), 'rb'))) {
                    $SigTool->outputMessage(sprintf($L10N['Accessing'], $Set[1]) . $L10N['Failed']);
                } else {
                    $Size += filesize($SigTool->fixPath(__DIR__ . '/' . $Set[1]));
                    if ($Set[6] > 0 && $Size > $Set[6]) {
                        fseek($Handle, $Size - $Set[6]);
                    }
                    $RemSize = $Set[6] ? $Set[6] : $Size;
                    while (!feof($Handle) && $RemSize > 0) {
                        $RemSize -= SigTool::SAFE_READ_SIZE;
                        $FileData = fread($Handle, SigTool::SAFE_READ_SIZE) . $FileData;
                    }
                    if ($RemSize < 1 && substr($FileData, -1, 1) !== "\n" && ($EoF = strrpos($FileData, "\n")) !== false) {
                        $FileData = substr($FileData, 0, $EoF) . "\n";
                    }
                    fclose($Handle);
                    $SigTool->outputMessage(sprintf($L10N['Accessing'], $Set[1]) . $L10N['Done']);
                }
            } elseif (($EoL = strpos($FileData, "\n")) !== false) {
                $FileData = substr($FileData, $EoL + 1);
            }
            $SigTool->outputMessage(sprintf($L10N['Writing'], $Set[4]), true);
            if ($Set[5]) {
                $FileData = 'phpMussel' . $Set[5] . "\n" . $FileData;
            }
            $FileData = preg_replace($Set[2], $Set[3], $FileData);

            /** Apply shorthand to signature names and remove any unwanted lines. */
            $SigTool->shorthand($FileData);

            /** Remove erroneous lines. */
            $FileData = preg_replace('~^(?!phpMussel|\n)[^\x1A\n]+$\n~im', '', $FileData);

            /** Write to file. */
            if (!is_resource($Handle = fopen($SigTool->fixPath(__DIR__ . '/' . $Set[4]), 'wb'))) {
                $Terminate('_Error_Writing', $Set[4] . '.gz');
            }
            fwrite($Handle, $FileData);
            fclose($Handle);
            if ($Set[5]) {
                if (!is_resource($Handle = gzopen($SigTool->fixPath(__DIR__ . '/' . $Set[4] . '.gz'), 'wb'))) {
                    $Terminate('_Error_Writing', $Set[4] . '.gz');
                }
                gzwrite($Handle, $FileData);
                gzclose($Handle);
            }

            /** YAML metadata stuff here. */
            if (!empty($Set[5]) && !empty($Set[4]) && !empty($Meta[$Set[4]]['Files']['Checksum'][0]) && !empty($Meta[$Set[4]]['Version'])) {
                /** We use the format Y.z.B for signature file versioning. */
                $Meta[$Set[4]]['Version'] = date('Y.z.B', time());
                $Meta[$Set[4]]['Files']['Checksum'][0] = hash('sha256', $FileData) . ':' . strlen($FileData);
            }

            $SigTool->outputMessage(sprintf($L10N['Writing'], $Set[4]) . $L10N['Done']);
            $FileData = '';
        }

        /** Don't need these anymore. */
        foreach ([$Set[0], $Set[1]] as $File) {
            if (file_exists($SigTool->fixPath(__DIR__ . '/' . $File))) {
                $SigTool->outputMessage(sprintf($L10N['Deleting'], $File), true);
                $SigTool->outputMessage(sprintf($L10N['Deleting'], $File) . (unlink($SigTool->fixPath(__DIR__ . '/' . $File)) ? $L10N['Done'] : $L10N['Failed']));
            }
        }
    }

    /** NDB sequence. */
    if (is_readable($SigTool->fixPath(__DIR__ . '/clamav.ndb'))) {
        $SigTool->outputMessage(sprintf($L10N['Accessing'], 'clamav.ndb'), true);
        $FileData = '';
        if (!is_resource($Handle = fopen($SigTool->fixPath(__DIR__ . '/clamav.ndb'), 'rb'))) {
            $SigTool->outputMessage(sprintf($L10N['Accessing'], 'clamav.ndb') . $L10N['Failed']);
        } else {
            while (!feof($Handle)) {
                $FileData .= fread($Handle, SigTool::SAFE_READ_SIZE);
            }
            fclose($Handle);
            $SigTool->outputMessage(sprintf($L10N['Accessing'], 'clamav.ndb') . $L10N['Done']);
        }
        if (!empty($FileData)) {
            $SigTool->outputMessage($L10N['Processing'], true);

            /** All the signature files that we're generating from our clamav.ndb file. */
            $FileSets = [
                'clamav.db' => "phpMussel0\n>!\$fileswitch>pefile>-1\n>\$fileswitch>infectable>-1\n",
                'clamav_regex.db' => "phpMussel@\n>!\$fileswitch>pefile>-1\n>\$fileswitch>infectable>-1\n",
                'clamav.htdb' => "phpMusselp\n>\$is_html>1>-1\n",
                'clamav_regex.htdb' => "phpMussel\x80\n>\$is_html>1>-1\n",
                'clamav.ndb' => "phpMusselP\n>!\$fileswitch>pefile>-1\n>\$fileswitch>infectable>-1\n",
                'clamav_regex.ndb' => "phpMussel`\n>!\$fileswitch>pefile>-1\n>\$fileswitch>infectable>-1\n",
                'clamav_elf.db' => "phpMussel0\n>\$is_elf>1>-1\n",
                'clamav_elf_regex.db' => "phpMussel@\n>\$is_elf>1>-1\n",
                'clamav_email.db' => "phpMussel0\n>\$is_email>1>-1\n",
                'clamav_email_regex.db' => "phpMussel@\n>\$is_email>1>-1\n",
                'clamav_exe.db' => "phpMussel0\n>\$is_pe>1>-1\n",
                'clamav_exe_regex.db' => "phpMussel@\n>\$is_pe>1>-1\n",
                'clamav_graphics.db' => "phpMussel0\n>\$is_graphics>1>-1\n",
                'clamav_graphics_regex.db' => "phpMussel@\n>\$is_graphics>1>-1\n",
                'clamav_java.db' => "phpMussel0\n>!\$fileswitch>pefile>-1\n",
                'clamav_java_regex.db' => "phpMussel@\n>!\$fileswitch>pefile>-1\n",
                'clamav_macho.db' => "phpMussel0\n>\$is_macho>1>-1\n",
                'clamav_macho_regex.db' => "phpMussel@\n>\$is_macho>1>-1\n",
                'clamav_ole.db' => "phpMussel0\n>\$is_ole>1>-1\n",
                'clamav_ole_regex.db' => "phpMussel@\n>\$is_ole>1>-1\n",
                'clamav_pdf.db' => "phpMussel0\n>\$is_pdf>1>-1\n",
                'clamav_pdf_regex.db' => "phpMussel@\n>\$is_pdf>1>-1\n",
                'clamav_swf.db' => "phpMussel0\n>\$is_swf>1>-1\n",
                'clamav_swf_regex.db' => "phpMussel@\n>\$is_swf>1>-1\n",
            ];

            $Offset = 0;
            $SigsNDB = substr_count($FileData, "\n");
            $SigsThis = 0;
            $Percent = '';

            /** Signature type to standard signature file pointer correlations. */
            $CorrelationsStandard = [
                'clamav.db',
                'clamav_exe.db',
                'clamav_ole.db',
                'clamav.htdb',
                'clamav_email.db',
                'clamav_graphics.db',
                'clamav_elf.db',
                'clamav.ndb',
                'clamav_macho.db',
                'clamav_pdf.db',
                'clamav_swf.db',
                'clamav_java.db'
            ];

            /** Signature type to regex signature file pointer correlations. */
            $CorrelationsRegex = [
                'clamav_regex.db',
                'clamav_exe_regex.db',
                'clamav_ole_regex.db',
                'clamav_regex.htdb',
                'clamav_email_regex.db',
                'clamav_graphics_regex.db',
                'clamav_elf_regex.db',
                'clamav_regex.ndb',
                'clamav_macho_regex.db',
                'clamav_pdf_regex.db',
                'clamav_swf_regex.db',
                'clamav_java_regex.db'
            ];

            /** Target guess to signature type correlations. */
            $CorrelationsTargetGuess = [
                "\x11" => 1,
                "\x12" => 1,
                "\x13" => 1,
                "\x14" => 6,
                "\x15" => 9,
                "\x17" => 4,
                "\x19" => 12,
                "\x1B" => 5,
                "\x1C" => 2,
                "\x1D" => 3,
                "\x25" => 10,
                "\x26" => 11
            ];

            /** Begin working through individual signatures. */
            while (($Pos = strpos($FileData, "\n", $Offset)) !== false) {
                $Last = $Percent;
                $Percent = number_format(($SigsThis / $SigsNDB) * 100, 2) . '%';
                $SigsThis++;
                if ($Percent !== $Last) {
                    $SigTool->outputMessage($L10N['Processing'] . ' ' . $Percent);
                }

                /** The current line in the signature file. */
                $ThisLine = substr($FileData, $Offset, $Pos - $Offset);

                $Offset = $Pos + 1;

                /** Helps to prevent invalid signatures sneaking in. */
                if (strpos($ThisLine, ':') === false) {
                    continue;
                }

                /** Helps to prevent "Compilation failed: number too big in {} quantifier ..." errors. */
                if (preg_match('/\{(?:\d{6,}|6[6-9]\d{3,}|65[6-9]\d{2,}|655[4-9]\d+|6553[6-9])/', $ThisLine)) {
                    continue;
                }

                $ThisLine = explode(':', $ThisLine);
                $SigName = $ThisLine[0] ?? '';
                $SigType = empty($ThisLine[1]) ? 0 : (int)$ThisLine[1];
                $SigOffset = $ThisLine[2] ?? '';
                $SigHex = $ThisLine[3] ?? '';
                $StartStop = '';

                /** Sort offsets. */
                if (!empty($SigOffset) && $SigOffset !== '*') {
                    $Start = $SigOffset;

                    /**
                     * Signatures with entry point offsets disregarded for now,
                     * because phpMussel hasn't been coded to handle them yet
                     * anyway (we'll get around to sorting it out eventually).
                     */
                    if (substr($SigOffset, 0, 2) === 'EP') {
                        continue;
                    }

                    if (substr($SigOffset, 0, 4) === 'EOF-') {
                        $StartStop = ':' . (int)substr($SigOffset, 3);
                    } elseif (substr($SigOffset, 0, 1) === 'S') {
                        $StartStop = ':'. $SigOffset;
                    } else {
                        /** Ignoring float shifts because we're not using them. */
                        if (($Comma = strpos($SigOffset, ',')) !== false) {
                            $Start = substr($SigOffset, 0, $Comma);
                        }

                        if ($Start !== '*') {
                            $Start = (int)$Start;
                            if ($Start === 0) {
                                $Start = 'A';
                            }
                            $StartStop = ':' . $Start;
                        }
                    }
                }

                /** Try to avoid dumping into general signatures whenever possible. */
                if ($SigType === 0) {
                    $TargetGuess = substr($SigName, 2, 1);
                    if (!empty($CorrelationsTargetGuess[$TargetGuess])) {
                        $SigType = $CorrelationsTargetGuess[$TargetGuess];
                    }
                }

                /** Normalise to lower-case. */
                $SigHex = strtolower($SigHex);

                if (preg_match('/[^a-f\d*]/i', $SigHex)) {
                    /** Convert from ClamAV's pattern syntax to PCRE syntax. */
                    $SigHex = preg_replace([
                        '~^.*\{(?:-?\d{4,}|\d{4,}-)\}.*$~',
                        '~\{(\d+)-(?:\d{4,})?\}~',
                        '~\{(\d+)-(\d+)\}~',
                        '~\{-(\d+)\}~',
                        '~\{(\d+)\}~',
                    ], [
                        '',
                        '(?:..){\1,}',
                        '(?:..){\1,\2}',
                        '(?:..){0,\1}',
                        '(?:..){\1}',
                    ], str_replace([
                        '*',
                        '?',
                        '{1}',
                        '{0-1}',
                        '{0-}',
                        '{1-}',
                        '(22|27)',
                        '(27|22)',
                    ], [
                        '.*',
                        '.',
                        '.',
                        '.?',
                        '.*',
                        '.+',
                        '2[27]',
                        '2[27]',
                    ], $SigHex));

                    /** Possible character range. */
                    $CharRange = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'];

                    /** Simplify all the (xx|xx|xx|xx...) stuff into something smaller and more readable. */
                    foreach ($CharRange as $Char) {
                        $InnerCharRange = $CharRange;
                        while (true) {
                            $Replacer = '(';
                            foreach ($InnerCharRange as $InnerChar) {
                                $Replacer .= $Char . $InnerChar . '|';
                            }
                            $Replacer = substr($Replacer, 0, -1) . ')';
                            $FinalLast = array_pop($InnerCharRange) ?: '';
                            $InnerCharCount = count($InnerCharRange);
                            if (!$InnerCharCount) {
                                break;
                            }
                            if ($InnerCharCount === 9) {
                                $Replacement = $Char . '\d';
                            } elseif ($InnerCharCount < 9) {
                                $Replacement = $Char . '[0-' . $FinalLast . ']';
                            } else {
                                $Replacement = $InnerCharCount === 10 ? $Char . '[\da]' : $Char . '[\da-' . $FinalLast . ']';
                            }
                            $SigHex = str_ireplace($Replacer, $Replacement, $SigHex);
                        }
                    }

                    /** Upper-lower case stuff, and further simplification. */
                    foreach ($CharRange as $Char) {
                        $SigHex = str_ireplace([
                            '(4' . $Char . '|6' . $Char . ')',
                            '(6' . $Char . '|4' . $Char . ')',
                            '(5' . $Char . '|7' . $Char . ')',
                            '(7' . $Char . '|5' . $Char . ')',
                            '(?:..){4}',
                            '(?:..){3}',
                            '(?:..){2}',
                            '(?:..){1}'
                        ], [
                            '[46]' . $Char,
                            '[46]' . $Char,
                            '[57]' . $Char,
                            '[57]' . $Char,
                            '.{8}',
                            '.{6}',
                            '....',
                            '..'
                        ], $SigHex);
                    }

                    /** Reduce footprint. */
                    foreach ($CharRange as $Char) {
                        $Matches = [];
                        $Lengths = [];
                        if (preg_match_all('~' . $Char . '{16,}~', $SigHex, $Matches) !== false && isset($Matches[0])) {
                            foreach ($Matches[0] as $Match) {
                                $Lengths[] = strlen($Match);
                            }
                            rsort($Lengths);
                        }
                        foreach ($Lengths as $Length) {
                            $SigHex = preg_replace_callback(
                                '~(?P<_before>[^' . $Char . '])' . $Char . '{' . $Length . '}(?P<_after>[^' . $Char . '])~',
                                function ($Matches) use ($Char, $Length) {
                                    return $Matches['_before'] . $Char . '{' . $Length . '}' . $Matches['_after'];
                                },
                                $SigHex
                            );
                        }
                    }

                    /** Newly formatted signature line. */
                    $ThisLine = $SigName . ':' . $SigHex . $StartStop . "\n";

                    /** Add to file based on signature type (regex). */
                    if (!empty($CorrelationsRegex[$SigType])) {
                        $FileSets[$CorrelationsRegex[$SigType]] .= $ThisLine;
                    }
                } else {
                    /** Wildcards and other tricks. */
                    $SigHex = str_replace('*', '>', $SigHex);

                    /** Newly formatted signature line. */
                    $ThisLine = $SigName . ':' . $SigHex . $StartStop . "\n";

                    /** Add to file based on signature type (non-regex). */
                    if (!empty($CorrelationsStandard[$SigType])) {
                        $FileSets[$CorrelationsStandard[$SigType]] .= $ThisLine;
                    }
                }
            }

            $SigTool->outputMessage($L10N['Processing'] . $L10N['Done']);
            foreach ($FileSets as $FileSet => $FileData) {
                $SigTool->outputMessage(sprintf($L10N['Writing'], $FileSet), true);
                if (!empty($Meta[$FileSet]['Files']['Checksum'][0]) && !empty($Meta[$FileSet]['Version'])) {
                    /** We use the format Y.z.B for signature file versioning. */
                    $Meta[$FileSet]['Version'] = date('Y.z.B', time());

                    $Meta[$FileSet]['Files']['Checksum'][0] = hash('sha256', $FileData) . ':' . strlen($FileData);
                }
                file_put_contents($SigTool->fixPath(__DIR__ . '/' . $FileSet), $FileData);
                $Handle = gzopen($SigTool->fixPath(__DIR__ . '/' . $FileSet . '.gz'), 'wb');
                gzwrite($Handle, $FileData);
                gzclose($Handle);
                $SigTool->outputMessage(sprintf($L10N['Writing'], $FileSet) . $L10N['Done']);
            }
        }
    }

    /** Update signatures.dat if necessary. */
    if (!empty($Meta)) {
        $SigTool->outputMessage(sprintf($L10N['Writing'], 'signatures.dat'), true);
        $NewMeta = "---\n" . $SigTool->reconstruct($SigTool->Data);
        $Handle = fopen($DatFile, 'wb');
        fwrite($Handle, $NewMeta);
        fclose($Handle);
        $SigTool->outputMessage(sprintf($L10N['Writing'], 'signatures.dat') . $L10N['Done']);
    }
}

echo "\n";
