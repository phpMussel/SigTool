<?php
/** SigTool v0.0.1-ALPHA (last modified: 2017.07.28). */

/** Fetch arguments. */
$RunMode = (isset($argv[1]) && !empty($argv[1])) ? strtolower($argv[1]) : '';

/** Help. */
if ($RunMode === '') {
    echo " SigTool v0.0.1-ALPHA (last modified: 2017.07.28).
 Generates signatures for phpMussel using main.cvd and daily.cvd from ClamAV.

 Syntax:
  $ php sigtool.php [arguments]
 Example: php sigtool.php xpmd
 Arguments (all are OFF by default; include to turn ON):
 - No arguments: Display this help information.
 - x Extract signature files from daily.cvd and main.cvd.
 - p Process signature files for use with phpMussel. --todo--
 - m Download main.cvd before processing. --todo--
 - d Download daily.cvd before processing. --todo--
 - u Update SigTool. --todo--

";
    die();
}

/** L10N. */
$L10N = [
    'Err0' => ' Can\'t continue (problem on line %s)!',
    'Done' => " Done!\n",
    'Phase3Step1' => ' Stripping ClamAV package header from %s ...',
    'Phase3Step2' => ' Decompressing %s (GZ) ...',
    'Phase3Step3' => ' Extracting contents from %s (TAR) to ' . __DIR__ . ' ...',
    'Phase3Step4' => ' Deleting %s ...',
];

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
            $FileData = fread($Handle[0], 131072);
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
            $FileData = gzread($Handle[0], 131072);
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
                    $FileData = fread($Handle[0], 131072);
                    fwrite($Handle[1], $FileData);
                }
                fclose($Handle[1]);
                fclose($Handle[0]);
            }
        }
        echo $L10N['Done'] . sprintf($L10N['Phase3Step4'], $Set);
        unlink($File);
        echo $L10N['Done'];
    }
    /** Cleanup. */
    unset($ThisFile, $Files, $Pad, $Set);
}

/** Phase 4: Process signature files for use with phpMussel. --todo-- */
if (strpos($RunMode, 'p') !== false) {
}

echo "\n";
