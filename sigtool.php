<?php
/** SigTool v0.0.2-ALPHA (last modified: 2017.08.06). */

/** Script version. */
$Ver = '0.0.2-ALPHA';

/** Script user agent. */
$UA = 'SigTool v' . $Ver . ' (https://github.com/phpMussel/SigTool)';

/**
 * Class for handling YAML. Adapted from YAML closures in
 * phpMussel/phpMussel->./vault/functions.php
 */
class SigToolYAML
{

    public $Arr = [];
    public $Raw = '';
    public $VM = false;

    /**
     * Normalises values defined by the YAML closure.
     *
     * @param string|int|bool $Value The value to be normalised.
     * @param int $ValueLen The length of the value to be normalised.
     * @param string|int|bool $ValueLow The value to be normalised, lowercased.
     */
    private function normalise(&$Value, int $ValueLen, $ValueLow)
    {
        if (substr($Value, 0, 1) === '"' && substr($Value, $ValueLen - 1) === '"') {
            $Value = substr($Value, 1, $ValueLen - 2);
        } elseif (substr($Value, 0, 1) === '\'' && substr($Value, $ValueLen - 1) === '\'') {
            $Value = substr($Value, 1, $ValueLen - 2);
        } elseif ($ValueLow === 'true' || $ValueLow === 'y') {
            $Value = true;
        } elseif ($ValueLow === 'false' || $ValueLow === 'n') {
            $Value = false;
        } elseif (substr($Value, 0, 2) === '0x' && ($HexTest = substr($Value, 2)) && !preg_match('/[^a-f0-9]/i', $HexTest) && !($ValueLen % 2)) {
            $Value = hex2bin($HexTest);
        } else {
            $ValueInt = (int)$Value;
            if (strlen($ValueInt) === $ValueLen && $Value == $ValueInt && $ValueLen > 1) {
                $Value = $ValueInt;
            }
        }
        if (!$Value) {
            $Value = false;
        }
    }

    /**
     * A simplified YAML-like parser. Note: This is intended to adequately serve
     * the needs of this package in a way that should feel familiar to users of
     * YAML, but it isn't a true YAML implementation and it doesn't adhere to any
     * specifications, official or otherwise.
     *
     * @param string $In The data to parse.
     * @param array $Arr Where to save the results.
     * @param bool $VM Validator Mode (if true, results won't be saved).
     * @param int $Depth Tab depth (inherited through recursion; ignore it).
     * @return bool Returns false if errors are encountered, and true otherwise.
     */
    public function read(string $In, &$Arr, bool $VM = false, int $Depth = 0)
    {
        if (!is_array($Arr)) {
            if ($VM) {
                return false;
            }
            $Arr = [];
        }
        if (!substr_count($In, "\n")) {
            return false;
        }
        $In = str_replace("\r", '', $In);
        $Key = $Value = $SendTo = '';
        $TabLen = $SoL = 0;
        while ($SoL !== false) {
            if (($EoL = strpos($In, "\n", $SoL)) === false) {
                $ThisLine = substr($In, $SoL);
            } else {
                $ThisLine = substr($In, $SoL, $EoL - $SoL);
            }
            $SoL = ($EoL === false) ? false : $EoL + 1;
            $ThisLine = preg_replace(array("/#.*$/", "/\x20+$/"), '', $ThisLine);
            if (empty($ThisLine) || $ThisLine === "\n") {
                continue;
            }
            $ThisTab = 0;
            while (($Chr = substr($ThisLine, $ThisTab, 1)) && ($Chr === ' ' || $Chr === "\t")) {
                $ThisTab++;
            }
            if ($ThisTab > $Depth) {
                if ($TabLen === 0) {
                    $TabLen = $ThisTab;
                }
                $SendTo .= $ThisLine . "\n";
                continue;
            } elseif ($ThisTab < $Depth) {
                return false;
            } elseif (!empty($SendTo)) {
                if (empty($Key)) {
                    return false;
                }
                if (!isset($Arr[$Key])) {
                    if ($VM) {
                        return false;
                    }
                    $Arr[$Key] = false;
                }
                if (!$this->read($SendTo, $Arr[$Key], $VM, $TabLen)) {
                    return false;
                }
                $SendTo = '';
            }
            if (substr($ThisLine, -1) === ':') {
                $Key = substr($ThisLine, $ThisTab, -1);
                $KeyLen = strlen($Key);
                $KeyLow = strtolower($Key);
                $this->normalise($Key, $KeyLen, $KeyLow);
                if (!isset($Arr[$Key])) {
                    if ($VM) {
                        return false;
                    }
                    $Arr[$Key] = false;
                }
            } elseif (substr($ThisLine, $ThisTab, 2) === '- ') {
                $Value = substr($ThisLine, $ThisTab + 2);
                $ValueLen = strlen($Value);
                $ValueLow = strtolower($Value);
                $this->normalise($Value, $ValueLen, $ValueLow);
                if (!$VM && $ValueLen > 0) {
                    $Arr[] = $Value;
                }
            } elseif (($DelPos = strpos($ThisLine, ': ')) !== false) {
                $Key = substr($ThisLine, $ThisTab, $DelPos - $ThisTab);
                $KeyLen = strlen($Key);
                $KeyLow = strtolower($Key);
                $this->normalise($Key, $KeyLen, $KeyLow);
                if (!$Key) {
                    return false;
                }
                $Value = substr($ThisLine, $ThisTab + $KeyLen + 2);
                $ValueLen = strlen($Value);
                $ValueLow = strtolower($Value);
                $this->normalise($Value, $ValueLen, $ValueLow);
                if (!$VM && $ValueLen > 0) {
                    $Arr[$Key] = $Value;
                }
            } elseif (strpos($ThisLine, ':') === false && strlen($ThisLine) > 1) {
                $Key = $ThisLine;
                $KeyLen = strlen($Key);
                $KeyLow = strtolower($Key);
                $this->normalise($Key, $KeyLen, $KeyLow);
                if (!isset($Arr[$Key])) {
                    if ($VM) {
                        return false;
                    }
                    $Arr[$Key] = false;
                }
            }
        }
        if (!empty($SendTo) && !empty($Key)) {
            if (!isset($Arr[$Key])) {
                if ($VM) {
                    return false;
                }
                $Arr[$Key] = [];
            }
            if (!$this->read($SendTo, $Arr[$Key], $VM, $TabLen)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Parse locally.
     */
    public function readIn()
    {
        $Arr = &$this->Arr;
        $Raw = $this->Raw;
        $VM = $this->VM;
        return $this->read($Raw, $Arr, $VM);
    }

    /**
     * Set raw data.
     */
    public function setRaw(string $Raw)
    {
        $this->Raw = $Raw;
    }

    /**
     * Set virtual mode.
     */
    public function setVM(bool $VM)
    {
        $this->VM = $VM;
    }

    /**
     * Reconstruct level.
     */
    private function inner(array $Arr, string &$Out, int $Depth = 0)
    {
        foreach ($Arr as $Key => $Value) {
            if ($Key === '---' && $Value === false) {
                $Out .= "---\n";
                continue;
            }
            if (!isset($List)) {
                $List = ($Key === 0);
            }
            $Out .= str_repeat(' ', $Depth) . (($List && is_int($Key)) ? '-' : $Key . ':');
            if (is_array($Value)) {
                $Depth++;
                $Out .= "\n";
                $this->inner($Value, $Out, $Depth);
                $Depth--;
                continue;
            }
            if ($Value === true) {
                $Out .= ' true';
            } elseif ($Value === false) {
                $Out .= ' false';
            } else {
                $Out .= ' ' . $Value;
            }
            $Out .= "\n";
        }
    }

    /**
     * Reconstruct new raw data from data array.
     */
    public function reconstruct()
    {
        $Arr = $this->Arr;
        $New = '';
        $this->inner($Arr, $New);
        return $New . "\n";
    }

}

/** Common integer values used by the script. */
$SafeReadSize = 131072;

/** Fetch arguments. */
$RunMode = !empty($argv[1]) ? strtolower($argv[1]) : '';

/** Use cURL to fetch files. */
$Fetch = function ($URI, $Timeout = 600) use (&$UA) {

    /** Initialise the cURL session. */
    $Request = curl_init($URI);

    $LCURI = strtolower($URI);
    $SSL = (substr($LCURI, 0, 6) === 'https:');

    curl_setopt($Request, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($Request, CURLOPT_HEADER, false);
    curl_setopt($Request, CURLOPT_POST, false);
    if ($SSL) {
        curl_setopt($Request, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($Request, CURLOPT_SSL_VERIFYPEER, false);
    }
    curl_setopt($Request, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($Request, CURLOPT_MAXREDIRS, 1);
    curl_setopt($Request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($Request, CURLOPT_TIMEOUT, $Timeout);
    curl_setopt($Request, CURLOPT_USERAGENT, $UA);

    /** Execute and get the response. */
    $Response = curl_exec($Request);

    /** Close the cURL session. */
    curl_close($Request);

    /** Return the results of the request. */
    return $Response;
};

/** Terminate with debug information. */
$Terminate = function ($Err = 'Err0') use (&$L10N) {
    $Debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
    die(sprintf($L10N[$Err], $Debug['line']) . "\n\n");
};

/** Fix path. */
$FixPath = function ($Path) {
    return str_replace(array('\/', '\\', '/\\'), '/', $Path);
};

/** L10N. */
$L10N = [
    'Help' =>
        " SigTool v0.0.2-ALPHA (last modified: 2017.08.06).\n" .
        " Generates signatures for phpMussel using main.cvd and daily.cvd from ClamAV.\n\n" .
        " Syntax:\n" .
        "  \$ php sigtool.php [arguments]\n" .
        " Example:\n" .
        "  php sigtool.php xpmd\n" .
        " Arguments (all are OFF by default; include to turn ON):\n" .
        "  - No arguments: Display this help information.\n" .
        "  - x Extract signature files from daily.cvd and main.cvd.\n" .
        "  - p Process signature files for use with phpMussel. --todo--\n" .
        "  - m Download main.cvd before processing.\n" .
        "  - d Download daily.cvd before processing.\n" .
        "  - u Update SigTool. --todo--\n\n",
    'Err0' => ' Can\'t continue (problem on line %s)!',
    'Accessing' => ' Accessing %s ...',
    'Deleting' => ' Deleting %s ...',
    'Downloading' => ' Downloading %s ...',
    'Done' => " Done!\n",
    'Failed' => " Failed!\n",
    'Writing' => ' Writing %s ...',
    'Processing' => ' Processing ...',
    'Phase3Step1' => ' Stripping ClamAV package header from %s ...',
    'Phase3Step2' => ' Decompressing %s (GZ) ...',
    'Phase3Step3' => ' Extracting contents from %s (TAR) to ' . $FixPath(__DIR__) . ' ...',
];

/** Apply shorthand to signature names. */
$Shorthand = function (&$Data) {
    while (true) {
        $Check = md5($Data) . ':' . strlen($Data);
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
            $Data = preg_replace("~\x10\x10" . $Param[1] . '[-.]~i', $Param[0] . "\x10", $Data);
            $Data = preg_replace("~\x10\x10([a-z0-9]+[._-])" . $Param[1] . '[-.]~i', $Param[0] . "\x10\\1", $Data);
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
            ["\x3F", 'BadURL'],
        ] as $Param) {
            $Data = preg_replace("~\x10" . $Param[1] . '[-.]~i', $Param[0], $Data);
            $Data = preg_replace("~\x10([a-z0-9]+[._-])" . $Param[1] . '[-.]~i', $Param[0] . '\1', $Data);
        }
        $Data = preg_replace(array('~([^a-z0-9])Agent([.-])~i', '~([^a-z0-9])General([.-])~i', '~([^a-z0-9])Generic([.-])~i'), '\1X\2', $Data);
        $Data = preg_replace('~([^a-z0-9])Downloader([.-])~i', '\1Dldr\2', $Data);
        $Confirm = md5($Data) . ':' . strlen($Data);
        if ($Confirm === $Check) {
            break;
        }
    }
};

/** Help. */
if ($RunMode === '') {
    die($L10N['Help']);
}

/** Phase 1: Download main.cvd. */
if (strpos($RunMode, 'm') !== false) {
    echo sprintf($L10N['Downloading'], 'main.cvd');
    try {
        $Data = $Fetch('http://database.clamav.net/main.cvd');
    } catch (\Exception $e) {
        $Terminate();
    }
    echo $L10N['Done'] . sprintf($L10N['Writing'], 'main.cvd');
    if (file_put_contents($FixPath(__DIR__ . '/main.cvd'), $Data)) {
        echo $L10N['Done'];
    } else {
        $Terminate();
    }
    unset($Data);
}

/** Phase 2: Download daily.cvd. */
if (strpos($RunMode, 'd') !== false) {
    echo sprintf($L10N['Downloading'], 'daily.cvd');
    try {
        $Data = $Fetch('http://database.clamav.net/daily.cvd');
    } catch (\Exception $e) {
        $Terminate();
    }
    echo $L10N['Done'] . sprintf($L10N['Writing'], 'daily.cvd');
    if (file_put_contents($FixPath(__DIR__ . '/daily.cvd'), $Data)) {
        echo $L10N['Done'];
    } else {
        $Terminate();
    }
    unset($Data);
}

/** Phase 3: Extract ClamAV signature files from daily.cvd and main.cvd packages. */
if (strpos($RunMode, 'x') !== false) {
    /** Terminate if daily and main CVD files are missing. */
    if (!file_exists($FixPath(__DIR__ . '/daily.cvd')) || !file_exists($FixPath(__DIR__ . '/main.cvd'))) {
        $Terminate();
    }

    foreach(['daily.cvd', 'main.cvd'] as $Set) {
        echo sprintf($L10N['Phase3Step1'], $Set);
        $File = $FixPath(__DIR__ . '/' . $Set);
        $Handle = [fopen($File, 'rb')];
        if (!is_resource($Handle[0])) {
            $Terminate();
        }
        fseek($Handle[0], 512);
        $Handle[1] = fopen($File . '.tmp', 'wb');
        if (!is_resource($Handle[1])) {
            $Terminate();
        }
        while (!feof($Handle[0])) {
            $FileData = fread($Handle[0], $SafeReadSize);
            fwrite($Handle[1], $FileData);
        }
        fclose($Handle[1]);
        fclose($Handle[0]);
        unlink($File);
        rename($File . '.tmp', $File);
        echo $L10N['Done'] . sprintf($L10N['Phase3Step2'], $Set);
        $Handle = [gzopen($File, 'rb')];
        if (!is_resource($Handle[0])) {
            $Terminate();
        }
        $Handle[1] = fopen($File . '.tmp', 'wb');
        if (!is_resource($Handle[1])) {
            $Terminate();
        }
        while (!gzeof($Handle[0])) {
            $FileData = gzread($Handle[0], $SafeReadSize);
            fwrite($Handle[1], $FileData);
        }
        fclose($Handle[1]);
        gzclose($Handle[0]);
        unlink($File);
        rename($File . '.tmp', $File);
        echo $L10N['Done'] . sprintf($L10N['Phase3Step3'], $Set);
        $Pad = str_repeat("\x00", 512 - (filesize($File) % 512));
        $Handle = fopen($File, 'ab');
        fwrite($Handle, $Pad);
        fclose($Handle);
        $Files = scandir('phar://' . $File);
        if (is_array($Files)) {
            foreach($Files as $ThisFile) {
                if (empty($ThisFile) || is_dir('phar://' . $File . '/' . $ThisFile)) {
                    continue;
                }
                $Handle = [
                    fopen($FixPath('phar://' . $File . '/' . $ThisFile), 'rb'),
                    fopen($FixPath(__DIR__ . '/' . $ThisFile), 'wb')
                ];
                if (!is_resource($Handle[0]) || !is_resource($Handle[1])) {
                    $Terminate();
                }
                while (!feof($Handle[0])) {
                    $FileData = fread($Handle[0], $SafeReadSize);
                    fwrite($Handle[1], $FileData);
                }
                fclose($Handle[1]);
                fclose($Handle[0]);
            }
        }
        echo $L10N['Done'] . sprintf($L10N['Deleting'], $Set);
        echo unlink($File) ? $L10N['Done'] : $L10N['Failed'];
    }
    /** Cleanup. */
    unset($ThisFile, $Files, $Pad);
}

/** Phase 4: Process signature files for use with phpMussel. --todo-- */
if (strpos($RunMode, 'p') !== false) {

    /** Check if signatures.dat exists; If so, we'll read it for updating. */
    if (is_readable($FixPath(__DIR__ . '/signatures.dat'))) {
        echo sprintf($L10N['Accessing'], 'signatures.dat');
        $YAML = new SigToolYAML();
        $Handle = fopen($FixPath(__DIR__ . '/signatures.dat'), 'rb');
        $YAML->setRaw(fread($Handle, filesize($FixPath(__DIR__ . '/signatures.dat'))));
        fclose($Handle);
        $YAML->readIn();
        $Meta = &$YAML->Arr;
        echo $L10N['Done'];
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
        'main.crb',
        'main.fp',
        'main.hdu',
        'main.hsb',
        'main.info',
        'main.ldb',
        'main.msb',
        'main.sfp',
    ] as $File) {
        if (file_exists($FixPath(__DIR__ . '/' . $File))) {
            echo sprintf($L10N['Deleting'], $File);
            echo unlink($FixPath(__DIR__ . '/' . $File)) ? $L10N['Done'] : $L10N['Failed'];
        }
    }

    /** Main sequence. */
    foreach ([
        ['daily.hdb', 'main.hdb', '~([0-9a-f]{32}\:[0-9]+\:)([^\n]+)\n~', "\\1\x1A\x20\x10\x10\\2\n", 'clamav.hdb', "\x20", 16777216],
        ['daily.mdb', 'main.mdb', '~([0-9]+\:[0-9a-f]{32}\:)([^\n]+)\n~', "\\1\x1A\x20\x10\x10\\2\n", 'clamav.mdb', "\xA0", 16777216],
        ['daily.ndb', 'main.ndb', '~^([^:\n]+\:)~m', "\x1A\x20\x10\x10\\1", 'clamav.ndb', false, 0],
    ] as $Set) {

        /** Fetch and build. */
        if (file_exists($FixPath(__DIR__ . '/' . $Set[0])) && file_exists($FixPath(__DIR__ . '/' . $Set[1]))) {
            $UseMains = false;
            $FileData = '';
            $Size = 0;
            echo sprintf($L10N['Accessing'], $Set[0]);
            $Handle = fopen($FixPath(__DIR__ . '/' . $Set[0]), 'rb');
            if (!is_resource($Handle)) {
                echo $L10N['Failed'];
            } else {
                $Size += filesize($FixPath(__DIR__ . '/' . $Set[0]));
                if ($Set[6] > 0 && $Size > $Set[6]) {
                    fseek($Handle, $Size - $Set[6]);
                } else {
                    $UseMains = true;
                }
                while (!feof($Handle)) {
                    $FileData .= fread($Handle, $SafeReadSize);
                }
                fclose($Handle);
                echo $L10N['Done'];
            }
            if ($UseMains) {
                echo sprintf($L10N['Accessing'], $Set[1]);
                $Handle = fopen($FixPath(__DIR__ . '/' . $Set[1]), 'rb');
                if (!is_resource($Handle)) {
                    echo $L10N['Failed'];
                } else {
                    $Size += filesize($FixPath(__DIR__ . '/' . $Set[1]));
                    if ($Set[6] > 0 && $Size > $Set[6]) {
                        fseek($Handle, $Size - $Set[6]);
                    }
                    while (!feof($Handle)) {
                        $FileData = fread($Handle, $SafeReadSize) . $FileData;
                    }
                    fclose($Handle);
                    echo $L10N['Done'];
                }
            }
            echo sprintf($L10N['Writing'], $Set[4]);
            $Handle = fopen($FixPath(__DIR__ . '/' . $Set[4]), 'wb');
            if (!is_resource($Handle)) {
                $Terminate();
            }
            if (($EoL = strpos($FileData, "\n")) !== false) {
                $FileData = substr($FileData, $EoL + 1);
            }
            if (!empty($Set[5])) {
                $FileData = 'phpMussel' . $Set[5] . "\n" . $FileData;
            }
            $FileData = preg_replace($Set[2], $Set[3], $FileData);
            $Shorthand($FileData);
            fwrite($Handle, $FileData);
            if (!empty($Set[5]) && !empty($Set[4]) && !empty($Meta[$Set[4]]['Files']['Checksum'][0]) && !empty($Meta[$Set[4]]['Version'])) {
                $Meta[$Set[4]]['Version'] = date('Y.z.B', time());
                $Meta[$Set[4]]['Files']['Checksum'][0] = md5($FileData) . ':' . strlen($FileData);
            }
            fclose($Handle);
            echo $L10N['Done'];
            $FileData = '';
        }

        /** Don't need these anymore. */
        foreach ([$Set[0], $Set[1]] as $File) {
            if (file_exists($FixPath(__DIR__ . '/' . $File))) {
                echo sprintf($L10N['Deleting'], $File);
                echo unlink($FixPath(__DIR__ . '/' . $File)) ? $L10N['Done'] : $L10N['Failed'];
            }
        }

    }

    /** NDB sequence. */
    if (is_readable($FixPath(__DIR__ . '/clamav.ndb'))) {
        echo sprintf($L10N['Accessing'], 'clamav.ndb');
        $FileData = '';
        $Handle = fopen($FixPath(__DIR__ . '/clamav.ndb'), 'rb');
        if (!is_resource($Handle)) {
            echo $L10N['Failed'];
        } else {
            while (!feof($Handle)) {
                $FileData .= fread($Handle, $SafeReadSize);
            }
            fclose($Handle);
            echo $L10N['Done'];
        }
        if (!empty($FileData)) {
            echo $L10N['Processing'];
            /** All the signature files that we're generating from our clamav.ndb file. */
            $FileSets = [
                'clamav.db' => "phpMussel0\n>!\$fileswitch>pefile>-1\n",
                'clamav_regex.db' => "phpMussel@\n>!\$fileswitch>pefile>-1\n",
                'clamav.htdb' => "phpMusselp\n>\$is_html>1>-1\n",
                'clamav_regex.htdb' => "phpMussel€\n>\$is_html>1>-1\n",
                'clamav.ndb' => "phpMusselP\n>!\$fileswitch>pefile>-1\n",
                'clamav_regex.ndb' => "phpMussel`\n>!\$fileswitch>pefile>-1\n",
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
            while (($Pos = strpos($FileData, "\n", $Offset)) !== false) {
                echo sprintf("\r%s (%s/%s)...", $L10N['Processing'], $SigsThis++, $SigsNDB);
                $ThisLine = substr($FileData, $Offset, $Pos - $Offset);
                $Offset = $Pos + 1;
                if (strpos($ThisLine, ':') === false) {
                    continue;
                }
                $ThisLine = explode(':', $ThisLine);
                $SigName = empty($ThisLine[0]) ? '' : $ThisLine[0];
                $SigType = empty($ThisLine[1]) ? 0 : (int)$ThisLine[1];
                $SigOffset = empty($ThisLine[2]) ? '' : $ThisLine[2];
                $SigHex = empty($ThisLine[3]) ? '' : $ThisLine[3];
                $StartStop = '';
                /** Sort offsets. */
                if (!empty($SigOffset) && $SigOffset !== '*') {
                    $Start = $SigOffset;
                    /**
                     * Signatures with entry point and sectional offsets disregarded for now,
                     * because phpMussel hasn't been coded to handle them yet anyway (we'll get
                     * around to sorting that out eventually).
                     */
                    if (substr($SigOffset, 0, 2) === 'EP' || substr($SigOffset, 0, 1) === 'S') {
                        continue;
                    }
                    if (substr($SigOffset, 0, 4) === 'EOF-') {
                        $StartStop = ':' . substr($SigOffset, 3);
                    } elseif (substr($SigOffset, 0, 4) === 'EOF-') {
                        $StartStop = ':' . substr($SigOffset, 3);
                    } elseif (($Comma = strpos($SigOffset, ',')) !== false) {
                        $Start = substr($SigOffset, 0, $Comma);
                        $Stop = substr($SigOffset, $Comma + 1);
                    } else {
                        $Stop = false;
                    }
                    if ($Start === 0) {
                        $Start = 'A';
                    } elseif ($Start === '*') {
                        $Start = 0;
                    }
                    if (!$StartStop) {
                        $StartStop = ':' . $Start;
                        if ($Stop !== false) {
                            $StartStop .= ':' . $Stop;
                        }
                    }
                }
                /** Newly formatted signature line. */
                $ThisLine = $SigName . ':' . $SigHex . $StartStop . "\n";
                /** Try to avoid dumping into general signatures whenever possible. */
                if ($SigType === 0) {
                    $TargetGuess = substr($SigName, 2, 1);
                    if ($TargetGuess === "\x11" || $TargetGuess === "\x12" || $TargetGuess === "\x13") {
                        $SigType = 1;
                    } elseif ($TargetGuess === "\x14") {
                        $SigType = 6;
                    } elseif ($TargetGuess === "\x15") {
                        $SigType = 9;
                    } elseif ($TargetGuess === "\x17") {
                        $SigType = 4;
                    } elseif ($TargetGuess === "\x19") {
                        $SigType = 12;
                    } elseif ($TargetGuess === "\x1B") {
                        $SigType = 5;
                    } elseif ($TargetGuess === "\x1C") {
                        $SigType = 2;
                    } elseif ($TargetGuess === "\x1D") {
                        $SigType = 3;
                    } elseif ($TargetGuess === "\x25") {
                        $SigType = 10;
                    } elseif ($TargetGuess === "\x26") {
                        $SigType = 11;
                    }
                }
                /** Assign to the appropriate signature file. */
                if (preg_match('/[^a-f0-9]/i', $SigHex)) {
                    // Handle PCRE syntactical conversion here.
                    if ($SigType === 0) {
                        $FileSets['clamav_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 1) {
                        $FileSets['clamav_exe_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 2) {
                        $FileSets['clamav_ole_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 3) {
                        $FileSets['clamav_regex.htdb'] .= $ThisLine;
                    } elseif ($SigType === 4) {
                        $FileSets['clamav_email_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 5) {
                        $FileSets['clamav_graphics_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 6) {
                        $FileSets['clamav_elf_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 7) {
                        $FileSets['clamav_regex.ndb'] .= $ThisLine;
                    } elseif ($SigType === 9) {
                        $FileSets['clamav_macho_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 10) {
                        $FileSets['clamav_pdf_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 11) {
                        $FileSets['clamav_swf_regex.db'] .= $ThisLine;
                    } elseif ($SigType === 12) {
                        $FileSets['clamav_java_regex.db'] .= $ThisLine;
                    }
                } else {
                    if ($SigType === 0) {
                        $FileSets['clamav.db'] .= $ThisLine;
                    } elseif ($SigType === 1) {
                        $FileSets['clamav_exe.db'] .= $ThisLine;
                    } elseif ($SigType === 2) {
                        $FileSets['clamav_ole.db'] .= $ThisLine;
                    } elseif ($SigType === 3) {
                        $FileSets['clamav.htdb'] .= $ThisLine;
                    } elseif ($SigType === 4) {
                        $FileSets['clamav_email.db'] .= $ThisLine;
                    } elseif ($SigType === 5) {
                        $FileSets['clamav_graphics.db'] .= $ThisLine;
                    } elseif ($SigType === 6) {
                        $FileSets['clamav_elf.db'] .= $ThisLine;
                    } elseif ($SigType === 7) {
                        $FileSets['clamav.ndb'] .= $ThisLine;
                    } elseif ($SigType === 9) {
                        $FileSets['clamav_macho.db'] .= $ThisLine;
                    } elseif ($SigType === 10) {
                        $FileSets['clamav_pdf.db'] .= $ThisLine;
                    } elseif ($SigType === 11) {
                        $FileSets['clamav_swf.db'] .= $ThisLine;
                    } elseif ($SigType === 12) {
                        $FileSets['clamav_java.db'] .= $ThisLine;
                    }
                }
            }
            echo "\r" . $L10N['Processing'] . $L10N['Done'];
            foreach ($FileSets as $FileSet => $FileData) {
                echo sprintf($L10N['Writing'], $FileSet);
                file_put_contents($FixPath(__DIR__ . '/' . $FileSet), $FileData);
                echo $L10N['Done'];
            }
        }
    }

    /** Update signatures.dat if necessary. */
    if (!empty($Meta)) {
        echo sprintf($L10N['Writing'], 'signatures.dat');
        $NewMeta = $YAML->reconstruct();
        $Handle = fopen($FixPath(__DIR__ . '/signatures.dat'), 'wb');
        fwrite($Handle, $NewMeta);
        fclose($Handle);
        echo $L10N['Done'];
    }

}

echo "\n";
