### How to install:

Before installing, please check the requirements. If these aren't met, SigTool won't operate correctly.

#### Requirements:
- PHP &gt;= `7.0.3` with zlib + Phar support.
- &gt;= 1GB free disk space (if working directly from disk) or available RAM (if using a RAM drive; recommended).
- Ability to operate PHP in CLI-mode (command prompt, terminal, shell, etc).

SigTool exists as a stand-alone PHP file and doesn't have any external dependencies (other than the requirements listed above), and so, the only thing you need to do to "install" it, is download `sigtool.php`.

SigTool can operate normally from a disk or storage medium in the same manner as any other PHP script. However, due to the large number of read/write operations it performs, it is highly recommended to operate it from a RAM drive, as this will slightly increase its speed and decrease excess disk read/write operations. Final output should not exceed approximately ~64MBs, but approximately ~1GB of free disk space or available RAM is required during normal operation due to temporary working files and in order to avoid read/write errors.

---


### How to use:

Note that SigTool is NOT a PHP-based web application (or web-app)! SigTool is a PHP-based CLI application (or CLI-app) intended to be used with terminal, shell, etc. It can be invoked by calling the PHP binary with the `sigtool.php` file as its first argument:

`$ php sigtool.php`

Help information will be displayed when SigTool is invoked, listing the possible flags (second argument) that can be used when operating SigTool.

Possible flags:
- No arguments: Display this help information.
- `x`: Extract signature files from `daily.cvd` and `main.cvd`.
- `p`: Process signature files for use with phpMussel.
- `m`: Download `main.cvd` before processing.
- `d`: Download `daily.cvd` before processing.
- `u`: Update SigTool (redownloads `sigtool.php` and dies; no checks performed).

Output produced is various phpMussel signature files generated directly from the ClamAV signatures database, in two forms:
- Signature files that can be inserted directly into the `/vault/signatures/` directory.
- GZ-compressed copies of the signature files that can be used to update the `phpMussel/Signatures` repository.

Output is produced directly into the same directory as `sigtool.php`. Source files and all temporary working files will be deleted during the course of operation (so, if you want to keep copies of `daily.cvd` and `main.cvd`, you should make copies before processing the signature files).

If the `signatures.dat` YAML file is included in the same directory when processing, version information and checksums will be updated accordingly (so, when using SigTool to update the `phpMussel/Signatures` repository, this should be included).

*Note: If you're a phpMussel user, please remember that signature files must be ACTIVE in order for them to work correctly! If you're using SigTool to generate new signature files, you can "activate" them by listing them in the phpMussel configuration "Active" directive. If you're using the front-end updates page to install and update signature files, you can "activate" them directly from the front-end updates page. However, using both methods is not necessary. Also, for optimum phpMussel performance, it's recommended that you only use the signature files that you need for your installation (e.g., if some particular type of file is blacklisted, you probably won't need signature files corresponding to that type of file; analysing files that will be blocked anyway is superfluous work and can significantly slow the scan process).*

A video demonstration for using SigTool is available on YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### SigTool generated signature files list:
Signature file | Description
---|---
clamav.hdb | Targets all types of files; Works with file hashes.
clamav.htdb | Targets HTML files; Works with HTML-normalised data.
clamav_regex.htdb | Targets HTML files; Works with HTML-normalised data; Signatures can contain regular expressions.
clamav.mdb | Targets PE files; Works with PE sectional metadata.
clamav.ndb | Targets all types of files; Works with ANSI-normalised data.
clamav_regex.ndb | Targets all types of files; Works with ANSI-normalised data; Signatures can contain regular expressions.
clamav.db | Targets all types of files; Works with raw data.
clamav_regex.db | Targets all types of files; Works with raw data; Signatures can contain regular expressions.
clamav_elf.db | Targets ELF files; Works with raw data.
clamav_elf_regex.db | Targets ELF files; Works with raw data; Signatures can contain regular expressions.
clamav_email.db | Targets EML files; Works with raw data.
clamav_email_regex.db | Targets EML files; Works with raw data; Signatures can contain regular expressions.
clamav_exe.db | Targets PE files; Works with raw data.
clamav_exe_regex.db | Targets PE files; Works with raw data; Signatures can contain regular expressions.
clamav_graphics.db | Targets image files; Works with raw data.
clamav_graphics_regex.db | Targets image files; Works with raw data; Signatures can contain regular expressions.
clamav_java.db | Targets Java files; Works with raw data.
clamav_java_regex.db | Targets Java files; Works with raw data; Signatures can contain regular expressions.
clamav_macho.db | Targets Mach-O files; Works with raw data.
clamav_macho_regex.db | Targets Mach-O files; Works with raw data; Signatures can contain regular expressions.
clamav_ole.db | Targets OLE objects; Works with raw data.
clamav_ole_regex.db | Targets OLE objects; Works with raw data; Signatures can contain regular expressions.
clamav_pdf.db | Targets PDF files; Works with raw data.
clamav_pdf_regex.db | Targets PDF files; Works with raw data; Signatures can contain regular expressions.
clamav_swf.db | Targets SWF files; Works with raw data.
clamav_swf_regex.db | Targets SWF files; Works with raw data; Signatures can contain regular expressions.

---


### Note regarding signature file extensions:
*This information will be expanded in the future.*

- __cedb__: Complex extended signature files (this is a homebrew format created for phpMussel, and has nothing to do with the ClamAV signatures database; SigTool doesn't generate any signature files using this extension; these are written manually for the `phpMussel/Signatures` repository; `clamav.cedb` contains adaptions of some deprecated/obsolete signatures from previous versions of the ClamAV signatures database that are considered to still have continued usefulness for phpMussel). Signature files that work with various rules based on extended metadata generated by phpMussel use this extension.
- __db__: Standard signature files (these are extracted from the `.ndb` signature files contained by `daily.cvd` and `main.cvd`). Signature files that work directly with file content use this extension.
- __fdb__: Filename signature files (the ClamAV signatures database formerly supported filename signatures, but doesn't anymore; SigTool doesn't generate any signature files using this extension; maintained due to continued usefulness for phpMussel). Signature files that work with filenames use this extension.
- __hdb__: Hash signature files (these are extracted from the `.hdb` signature files contained by `daily.cvd` and `main.cvd`). Signature files that work with file hashes use this extension.
- __htdb__: HTML signature files (these are extracted from the `.ndb` signature files contained by `daily.cvd` and `main.cvd`). Signature files that work with HTML-normalised content use this extension.
- __mdb__: PE sectional signature files (these are extracted from the `.mdb` signature files contained by `daily.cvd` and `main.cvd`). Signature files that work with PE sectional metadata use this extension.
- __medb__: PE extended signature files (this is a homebrew format created for phpMussel, and has nothing to do with the ClamAV signatures database; SigTool doesn't generate any signature files using this extension; these are written manually for the `phpMussel/Signatures` repository). Signature files that work with PE metadata (other than PE sectional metadata) use this extension.
- __ndb__: Normalised signature files (these are extracted from the `.ndb` signature files contained by `daily.cvd` and `main.cvd`). Signature files that work with ANSI-normalised file content use this extension.
- __udb__: URL signature files (this is a homebrew format created for phpMussel, and has nothing to do with the ClamAV signatures database; SigTool doesn't *currently* generate any signature files using this extension, although this may change in the future; currently, these are written manually for the `phpMussel/Signatures` repository). Signature files that work with URLs use this extension.
- __ldb__: Logical signature files (these will *eventually*, for a future SigTool version, be extracted from the `.ldb` signature files contained by `daily.cvd` and `main.cvd`, but aren't yet supported by SigTool or phpMussel). Signature files that work with various logical rules use this extension.


---


*Last modified: 4 September 2017 (2017.09.04).*
