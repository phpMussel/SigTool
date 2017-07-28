<?php
/** SigTool v0.0.1-ALPHA (last modified: 2017.07.28). */

/** L10N. */
$L10N = [
    'Help' =>
        " SigTool v0.0.1-ALPHA (last modified: 2017.07.28).\n" .
        " Generates signatures for phpMussel using main.cvd and daily.cvd from ClamAV.\n\n" .
        " Syntax:" .
        "  \$ php sigtool.php [arguments]\n" .
        " Example: php sigtool.php xpmd\n" .
        " Arguments (all are OFF by default; include to turn ON):\n" .
        " - No arguments: Display this help information.\n" .
        " - x Extract signature files from daily.cvd and main.cvd.\n" .
        " - p Process signature files for use with phpMussel. --todo--\n" .
        " - m Download main.cvd before processing. --todo--\n" .
        " - d Download daily.cvd before processing. --todo--\n" .
        " - u Update SigTool. --todo--\n\n",
    'Err0' => ' Can\'t continue (problem on line %s)!',
    'Accessing' => ' Accessing %s ...',
    'Deleting' => ' Deleting %s ...',
    'Done' => " Done!\n",
    'Failed' => " Failed!\n",
    'Writing' => ' Writing %s ...',
    'Phase3Step1' => ' Stripping ClamAV package header from %s ...',
    'Phase3Step2' => ' Decompressing %s (GZ) ...',
    'Phase3Step3' => ' Extracting contents from %s (TAR) to ' . __DIR__ . ' ...',
];

/** Common integer values used by the script. */
$SafeReadSize = 131072;
$SafeFileSize = 20971520;

/** Fetch arguments. */
$RunMode = !empty($argv[1]) ? strtolower($argv[1]) : '';

/** Help. */
if ($RunMode === '') {
    die($L10N['Help']);
}

/** Just used for debugging. */
$VarInfo = function (&$Var) {
    $Out = '';
    if (is_string($Var)) {
        $Out .= 'String (' . strlen($Var) . ')';
    } elseif (is_int($Var)) {
        $Out .= 'Integer (' . $Var . ')';
    } elseif (is_bool($Var)) {
        $Out .= 'Boolean (' . ($Var ? 'True' : 'False') . ')';
    } elseif (is_array($Var)) {
        $Out .= 'Array (' . count($Var) . ' Elements)';
    }
    return $Out . "\n";
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
            ["\x17", 'E?mail'],
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
        $Confirm = md5($Data) . ':' . strlen($Data);
        if ($Confirm === $Check) {
            break;
        }
    }
};

/** Phase 1: Download main.cvd. --todo-- */
if (strpos($RunMode, 'm') !== false) {
}

/** Phase 2: Download daily.cvd. --todo-- */
if (strpos($RunMode, 'd') !== false) {
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
    unset($ThisFile, $Files, $Pad, $Set);
}

/** Phase 4: Process signature files for use with phpMussel. --todo-- */
if (strpos($RunMode, 'p') !== false) {

    /** Hash signatures. */
    if (file_exists(__DIR__ . '/daily.hdb') && file_exists(__DIR__ . '/main.hdb')) {
        $UseMains = false;
        $FileData = '';
        $Size = 0;
        echo sprintf($L10N['Accessing'], 'daily.hdb');
        $Handle = fopen(__DIR__ . '/daily.hdb', 'rb');
        if (!is_resource($Handle)) {
            echo $L10N['Failed'];
        } else {
            $Size += filesize(__DIR__ . '/daily.hdb');
            if ($Size > $SafeFileSize) {
                fseek($Handle, $Size - $SafeFileSize);
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
            echo sprintf($L10N['Accessing'], 'main.hdb');
            $Handle = fopen(__DIR__ . '/main.hdb', 'rb');
            if (!is_resource($Handle)) {
                echo $L10N['Failed'];
            } else {
                $Size += filesize(__DIR__ . '/main.hdb');
                if ($Size > $SafeFileSize) {
                    fseek($Handle, $Size - $SafeFileSize);
                } else {
                    $UseMains = true;
                }
                while (!feof($Handle)) {
                    $FileData = fread($Handle, $SafeReadSize) . $FileData;
                }
                fclose($Handle);
                echo $L10N['Done'];
            }
        }
        echo sprintf($L10N['Writing'], 'clamav.hdb');
        $Handle = fopen(__DIR__ . '/clamav.hdb', 'wb');
        if (!is_resource($Handle)) {
            $Terminate();
        }
        if (($EoL = strpos($FileData, "\n")) !== false) {
            $FileData = substr($FileData, $EoL + 1);
        }
        $FileData = "phpMussel \n" . $FileData;
        $FileData = preg_replace('~([0-9a-f]{32}\:[0-9]+\:)([^\n]+)\n~', "\\1\x1a\x20\x10\x10\\2\n", $FileData);
        $Shorthand($FileData);
        fwrite($Handle, $FileData);
        fclose($Handle);
        echo $L10N['Done'];
        $FileData = '';
    }

    /** Don't need these. */
    //foreach (['daily.hdu', 'main.hdu', 'daily.hdb', 'main.hdb'] as $File) {
    //    echo sprintf($L10N['Deleting'], $File);
    //    echo unlink(__DIR__ . '/' . $File) ? $L10N['Done'] : $L10N['Failed'];
    //}

}

echo "\n";
