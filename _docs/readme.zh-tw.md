### 如何安裝：

安裝前請檢查要求。​如果這些不符合，SigTool將無法正常運行。

#### 要求：
- PHP &gt;= `7.0.3` 用zlib + Phar支持。
- &gt;= 1GB可用磁盤空間（如果直接從磁盤工作）或可用RAM（如果使用RAM驅動器；推薦的）。
- 能夠在CLI模式下操作PHP（命令提示符，終端，shell，等等）。

要安裝SigTool，只需下載`SigTool.php`和`YAML.php`。 :-)

SigTool可以像任何其他PHP腳本從磁盤或存儲介質一樣運行。​但是，由於執行了大量的讀/寫操作，強烈建議您從RAM驅動器進行操作，因為這會稍微增加速度並減少多餘的磁盤讀/寫操作。​最終輸出不應超過約64MB，但在正常操作期間需要約1GB的可用磁盤空間或可用RAM由於臨時工作文件和為了避免讀/寫錯誤。

---


### 如何使用：

請注意，SigTool不是基於PHP的網絡應用程序（或『web-app』）！​SigTool是基於PHP的CLI應用程序（或『CLI-app』）旨在與終端，shell，等等一起使用。​可以通過使用`SigTool.php`文件作為第一個參數調用PHP二進制來調用它：

`$ php SigTool.php`

當調用SigTool時，將顯示幫助信息，列出了在運行SigTool時可以使用的參數。

可以使用的參數：
- 沒有參數：顯示此幫助信息。
- `x`：從`daily.cvd`和`main.cvd`中提取簽名文件。
- `p`：處理用於phpMussel的簽名文件。
- `m`：在處理之前下載`main.cvd`。
- `d`：在處理之前下載`daily.cvd`。
- `u`：更新SigTool（重新`SigTool.php`下載然後die；沒有檢查執行）.

產生的產出是各種phpMussel簽名文件直接從ClamAV簽名數據庫生成，有兩種形式：
- 可以直接插入在`/vault/signatures/`目錄的簽名文件。
- GZ壓縮的簽名文件副本，可用於更新`phpMussel/Signatures`存儲庫。

輸出直接生成到與`SigTool.php`相同的目錄中。​源文件和所有臨時工作文件將在操作過程中被刪除（所以，如果您想保留『daily.cvd』和『main.cvd』的副本，您應該在處理簽名文件之前進行複制）。

當使用SigTool生成新簽名文件時，您的電腦的防病毒掃描程序可能會嘗試刪除或隔離新生成的簽名文件。​這有時會發生因為，當您的防病毒執行掃描時，簽名文件可能包含看起來很惡毒的數據。​但是，SigTool生成的簽名文件不包含任何可執行代碼，並且完全是良性的。​如果遇到此問題，可以嘗試暫時禁用防病毒掃描程序，或配置您的防病毒掃描程序將在白名單中包括您的生成新簽名文件的目錄。

如果`signatures.dat` YAML文件在處理時包含在同一目錄中，版本信息和校驗和將相應更新（所以，當使用SigTool於更新`phpMussel/Signatures`存儲庫，這應該包括在內）。

*注意：如果您是phpMussel用戶，請記住，簽名文件必須是『活性』以便正常工作！​如果您使用SigTool於生成新的簽名文件，您可以通過在phpMussel配置『Active』指令中列出它們來『啟用』它們。​如果您正在使用前端更新頁面來安裝和更新簽名文件，您可以從前端更新頁面直接『啟用』他們。​但是，使用這兩種方法是不必要的。​此外，為了最佳的phpMussel性能，建議您僅使用安裝所需的簽名文件（例如，如果某些特定類型的文件被列入黑名單，您可能不需要與該類型的文件相對應的簽名文件；​分析將被阻止的文件是多餘的工作，可以顯著減慢掃描過程）。*

使用SigTool的視頻演示在YouTube上可用： __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### 由SigTool生成的簽名文件列表：
簽名文件 | 說明
---|---
clamav.hdb | 用於所有文件類型；它使用文件哈希。
clamav.htdb | 用於HTML文件；它使用HTML規範化數據。
clamav_regex.htdb | 用於HTML文件；它使用HTML規範化數據；簽名可以包含正則表達式。
clamav.mdb | 用於PE文件；它使用PE部分元數據。
clamav.ndb | 用於所有文件類型；它使用ANSI規範化數據。
clamav_regex.ndb | 用於所有文件類型；它使用ANSI規範化數據；簽名可以包含正則表達式。
clamav.db | 用於所有文件類型；它使用原始數據。
clamav_regex.db | 用於所有文件類型；它使用原始數據；簽名可以包含正則表達式。
clamav_elf.db | 用於ELF文件；它使用原始數據。
clamav_elf_regex.db | 用於ELF文件；它使用原始數據；簽名可以包含正則表達式。
clamav_email.db | 用於EML文件；它使用原始數據。
clamav_email_regex.db | 用於EML文件；它使用原始數據；簽名可以包含正則表達式。
clamav_exe.db | 用於PE文件；它使用原始數據。
clamav_exe_regex.db | 用於PE文件；它使用原始數據；簽名可以包含正則表達式。
clamav_graphics.db | 用於圖像文件；它使用原始數據。
clamav_graphics_regex.db | 用於圖像文件；它使用原始數據；簽名可以包含正則表達式。
clamav_java.db | 用於Java文件；它使用原始數據。
clamav_java_regex.db | 用於Java文件；它使用原始數據；簽名可以包含正則表達式。
clamav_macho.db | 用於Mach-O文件；它使用原始數據。
clamav_macho_regex.db | 用於Mach-O文件；它使用原始數據；簽名可以包含正則表達式。
clamav_ole.db | 用於OLE對象；它使用原始數據。
clamav_ole_regex.db | 用於OLE對象；它使用原始數據；簽名可以包含正則表達式。
clamav_pdf.db | 用於PDF文件；它使用原始數據。
clamav_pdf_regex.db | 用於PDF文件；它使用原始數據；簽名可以包含正則表達式。
clamav_swf.db | 用於SWF文件；它使用原始數據。
clamav_swf_regex.db | 用於SWF文件；它使用原始數據；簽名可以包含正則表達式。

---


### 關於簽名文件擴展名：
*這些信息將在未來擴大。*

- __cedb__: 複雜擴展簽名文件（這是為phpMussel創建的格式，並且與ClamAV簽名數據庫無關；​SigTool不使用此擴展名生成任何簽名文件；​這些是為`phpMussel/Signatures`存儲庫手動編寫的；​`clamav.cedb`包含了以前版本的ClamAV签名数据库中某些已弃用/过时修改的签名，​被认为对phpMussel具有持续的用处）。​基于由phpMussel生成的扩展元数据的各种规则的签名文件使用此扩展。
- __db__: 標準簽名文件（這些是從`daily.cvd`和`main.cvd`包含的`.ndb`簽名文件中提取的）。​這些簽名文件直接與文件內容一起工作。
- __fdb__: 文件名簽名文件（ClamAV签名数据库以前支持的文件名签名，但不再是；​SigTool不會使用此擴展名生成任何簽名文件；​因為它被認為對phpMussel是有用）。​這些簽名文件使用文件名。
- __hdb__: 哈希簽名文件（這些是從`daily.cvd`和`main.cvd`包含的`.hdb`簽名文件中提取的）。​這些簽名文件使用哈希。
- __htdb__: HTML簽名文件（這些是從`daily.cvd`和`main.cvd`包含的`.ndb`簽名文件中提取的）。​這些簽名文件使用HTML標準化內容。
- __mdb__: PE部分簽名文件（這些是從`daily.cvd`和`main.cvd`包含的`.mdb`簽名文件中提取的）。​這些簽名文件使用PE部分元數據。
- __medb__: PE擴展簽名文件（這是為phpMussel創建的格式，並且與ClamAV簽名數據庫無關；​SigTool不會使用此擴展名生成任何簽名文件；​這些是為`phpMussel/Signatures`存儲庫手動編寫的）。​這些簽名文件使用PE元數據（除了PE部分元數據）。
- __ndb__: 標準化簽名文件（這些是從`daily.cvd`和`main.cvd`包含的`.ndb`簽名文件中提取的）。​這些簽名文件使用ANSI標準化內容。
- __udb__: URL簽名文件（這是為phpMussel創建的格式，並且與ClamAV簽名數據庫無關；​SigTool目前不使用此擴展名生成任何簽名文件，但這可能會在將來發生變化；目前，這些是為`phpMussel/Signatures`存儲庫手動編寫的）。​這些簽名文件使用URL。
- __ldb__: 邏輯簽名文件（最終，為未來的SigTool版本，這些將從`.ldb`簽名文件中提取的，但SigTool或phpMussel尚不支持）。​這些簽名文件使用各種邏輯規則。


---


最後更新：2020年3月7日。
