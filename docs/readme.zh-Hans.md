### 如何安装：

安装前请检查要求。​如果这些不符合，SigTool将无法正常运行。

#### 要求：
- __是给 SigTool &lt;=1.0.2:__ PHP &gt;=7.0.3 用phar支持（PHP &gt;=7.2.0 建议）。
- __是给 SigTool 1.0.3:__ PHP &gt;=7.0.3 （PHP &gt;=7.2.0 建议）。
- __是给 SigTool v2:__ PHP &gt;=7.2.0.
- __所有版本:__ *至少* &gt;=681 MB 的可用RAM（但是，强烈建议至少 &gt;=1 GB）。
- __所有版本:__ 大约 300 MB 的可用磁盘空间（此数字可能因签名数据库的迭代而异）。
- __所有版本:__ 能够在 CLI 模式下操作 PHP （例如，命令提示符、终端、shell、bash、等等）。

安装SigTool的推荐方法是通过Composer。

`composer require phpmussel/sigtool`

或者，您可以直接从GitHub克隆存储库或下载ZIP。

---


### 如何使用：

请注意，SigTool不是基于PHP的网络应用程序（或“web-app”）！​SigTool是基于PHP的CLI应用程序（或“CLI-app”）旨在与终端，shell，等等一起使用。​可以通过使用`SigTool.php`文件作为第一个参数调用PHP二进制来调用它：

`$ php SigTool.php`

当调用SigTool时，将显示帮助信息，列出了在运行SigTool时可以使用的参数。

可以使用的参数：
- 没有参数：显示此帮助信息。
- `x`：从`daily.cvd`和`main.cvd`中提取签名文件。
- `p`：处理用于phpMussel的签名文件。

产生的产出是各种phpMussel签名文件直接从ClamAV签名数据库生成，有两种形式：
- 可以直接插入在`/vault/signatures/`目录的签名文件。
- GZ压缩的签名文件副本，可用于更新`phpMussel/Signatures`存储库。

输出直接生成到与`SigTool.php`相同的目录中。​源文件和所有临时工作文件将在操作过程中被删除（所以，如果您想保留“daily.cvd”和“main.cvd”的副本，您应该在处理签名文件之前进行复制）。

当使用SigTool生成新签名文件时，您的电脑的防病毒扫描程序可能会尝试删除或隔离新生成的签名文件。​这有时会发生因为，当您的防病毒执行扫描时，签名文件可能包含看起来很恶毒的数据。​但是，SigTool生成的签名文件不包含任何可执行代码，并且完全是良性的。​如果遇到此问题，可以尝试暂时禁用防病毒扫描程序，或配置您的防病毒扫描程序将在白名单中包括您的生成新签名文件的目录。

如果`signatures.dat` YAML文件在处理时包含在同一目录中，版本信息和校验和将相应更新（所以，当使用SigTool于更新`phpMussel/Signatures`存储库，这应该包括在内）。

*注意：如果您是phpMussel用户，请记住，签名文件必须是“活性”以便正常工作！​如果您使用SigTool于生成新的签名文件，您可以通过在phpMussel配置“Active”指令中列出它们来“启用”它们。​如果您正在使用前端更新页面来安装和更新签名文件，您可以从前端更新页面直接“启用”他们。​但是，使用这两种方法是不必要的。​此外，为了最佳的phpMussel性能，建议您仅使用安装所需的签名文件（例如，如果某些特定类型的文件被列入黑名单，您可能不需要与该类型的文件相对应的签名文件；​分析将被阻止的文件是多余的工作，可以显着减慢扫描过程）。*

使用SigTool的视频演示在YouTube上可用： __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### 由SigTool生成的签名文件列表：
签名文件 | 说明
---|---
clamav.hdb | 用于所有文件类型；它使用文件哈希。
clamav.htdb | 用于HTML文件；它使用HTML规范化数据。
clamav_regex.htdb | 用于HTML文件；它使用HTML规范化数据；签名可以包含正则表达式。
clamav.mdb | 用于PE文件；它使用PE部分元数据。
clamav.ndb | 用于所有文件类型；它使用ANSI规范化数据。
clamav_regex.ndb | 用于所有文件类型；它使用ANSI规范化数据；签名可以包含正则表达式。
clamav.db | 用于所有文件类型；它使用原始数据。
clamav_regex.db | 用于所有文件类型；它使用原始数据；签名可以包含正则表达式。
clamav_elf.db | 用于ELF文件；它使用原始数据。
clamav_elf_regex.db | 用于ELF文件；它使用原始数据；签名可以包含正则表达式。
clamav_email.db | 用于EML文件；它使用原始数据。
clamav_email_regex.db | 用于EML文件；它使用原始数据；签名可以包含正则表达式。
clamav_exe.db | 用于PE文件；它使用原始数据。
clamav_exe_regex.db | 用于PE文件；它使用原始数据；签名可以包含正则表达式。
clamav_graphics.db | 用于图像文件；它使用原始数据。
clamav_graphics_regex.db | 用于图像文件；它使用原始数据；签名可以包含正则表达式。
clamav_java.db | 用于Java文件；它使用原始数据。
clamav_java_regex.db | 用于Java文件；它使用原始数据；签名可以包含正则表达式。
clamav_macho.db | 用于Mach-O文件；它使用原始数据。
clamav_macho_regex.db | 用于Mach-O文件；它使用原始数据；签名可以包含正则表达式。
clamav_ole.db | 用于OLE对象；它使用原始数据。
clamav_ole_regex.db | 用于OLE对象；它使用原始数据；签名可以包含正则表达式。
clamav_pdf.db | 用于PDF文件；它使用原始数据。
clamav_pdf_regex.db | 用于PDF文件；它使用原始数据；签名可以包含正则表达式。
clamav_swf.db | 用于SWF文件；它使用原始数据。
clamav_swf_regex.db | 用于SWF文件；它使用原始数据；签名可以包含正则表达式。

---


### 关于签名文件扩展名：
*这些信息将在未来扩大。*

- __cedb__: 复杂扩展签名文件（这是为phpMussel创建的格式，并且与ClamAV签名数据库无关；​SigTool不使用此扩展名生成任何签名文件；​这些是为`phpMussel/Signatures`存储库手动编写的；​`clamav.cedb`包含了以前版本的ClamAV签名数据库中某些已弃用/过时修改的签名，​被认为对phpMussel具有持续的用处）。​基于由phpMussel生成的扩展元数据的各种规则的签名文件使用此扩展。
- __db__: 标准签名文件（这些是从`daily.cvd`和`main.cvd`包含的`.ndb`签名文件中提取的）。​这些签名文件直接与文件内容一起工作。
- __fdb__: 文件名签名文件（ClamAV签名数据库以前支持的文件名签名，但不再是；​SigTool不会使用此扩展名生成任何签名文件；​因为它被认为对phpMussel是有用）。​这些签名文件使用文件名。
- __hdb__: 哈希签名文件（这些是从`daily.cvd`和`main.cvd`包含的`.hdb`签名文件中提取的）。​这些签名文件使用哈希。
- __htdb__: HTML签名文件（这些是从`daily.cvd`和`main.cvd`包含的`.ndb`签名文件中提取的）。​这些签名文件使用HTML标准化内容。
- __mdb__: PE部分签名文件（这些是从`daily.cvd`和`main.cvd`包含的`.mdb`签名文件中提取的）。​这些签名文件使用PE部分元数据。
- __medb__: PE扩展签名文件（这是为phpMussel创建的格式，并且与ClamAV签名数据库无关；​SigTool不会使用此扩展名生成任何签名文件；​这些是为`phpMussel/Signatures`存储库手动编写的）。​这些签名文件使用PE元数据（除了PE部分元数据）。
- __ndb__: 标准化签名文件（这些是从`daily.cvd`和`main.cvd`包含的`.ndb`签名文件中提取的）。​这些签名文件使用ANSI标准化内容。
- __udb__: URL签名文件（这是为phpMussel创建的格式，并且与ClamAV签名数据库无关；​SigTool目前不使用此扩展名生成任何签名文件，但这可能会在将来发生变化；目前，这些是为`phpMussel/Signatures`存储库手动编写的）。​这些签名文件使用URL。
- __ldb__: 逻辑签名文件（最终，为未来的SigTool版本，这些将从`.ldb`签名文件中提取的，但SigTool或phpMussel尚不支持）。​这些签名文件使用各种逻辑规则。


---


最后更新：2021年7月22日。
