[![PHP >= 7.0.0](https://img.shields.io/badge/PHP-%3E%3D%207.0.0-8892bf.svg)](https://maikuolan.github.io/Compatibility-Charts/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

## SigTool.
Generates signatures for [phpMussel](https://github.com/phpMussel/phpMussel) using main.cvd and daily.cvd from [ClamAV](http://www.clamav.net/).

---


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

Note that SigTool is NOT a web-based PHP application (or web-app)! SigTool is a CLI-based PHP application (or CLI-app) intended to be used with terminal, shell, etc. It can be invoked by calling the PHP binary with the `sigtool.php` file as its first argument:

`$ php sigtool.php`

Help information will be displayed when SigTool is invoked, listing the possible flags (second argument) that can be used when operating SigTool.

More information can be found in the documentation herein.

---


### Documentation:
- **[English](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.en.md)**
- **[Español](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.es.md)**
- **[Français](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.fr.md)**
- **[Bahasa Indonesia](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.id.md)**
- **[Italiano](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.it.md)**
- **[Nederlandse](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.nl.md)**
- **[Português](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.pt.md)**
- **[Tiếng Việt](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.vi.md)**
- **[中文（简体）](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.zh.md)**
- **[中文（傳統）](https://github.com/phpMussel/SigTool/blob/master/_docs/readme.zh-tw.md)**

---


*Last modified: 16 September 2017 (2017.09.16).*
