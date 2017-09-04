### Come installare:

Prima di installare, controllare i requisiti. Se questi non sono soddisfatti, SigTool non funzionerà correttamente.

#### Requisiti:
- PHP &gt;= `7.0.3` con supporto zlib + Phar.
- &gt;= 1GB di spazio libero su disco (se lavora direttamente dal disco) o RAM disponibile (se si utilizza un'unità RAM; consigliato).
- Capacità di utilizzare PHP in modalità CLI (prompt dei comandi, terminal, shell, ecc).

SigTool esiste come un file PHP autonomo e non dispone di dipendenze esterne (diversi dai requisiti elencati in precedenza), e così, l'unica cosa che devi fare per "installarlo", è scaricare `sigtool.php`.

SigTool può funzionare normalmente da un disco o da un supporto di memorizzazione nello stesso modo di qualsiasi altro script PHP. Tuttavia, a causa del numero elevato di operazioni di lettura/scrittura che esegue, è altamente consigliato per azionarlo da un'unità RAM, poichè ciò aumenterà leggermente la sua velocità e diminuirà le operazioni di lettura/scrittura di disco eccessivo. L'output finale non dovrebbe superare approssimativamente ~64MB, ma approssimativamente ~1GB di spazio libero su disco o RAM disponibile è necessario durante il normale funzionamento a causa di file temporanei di lavoro e per evitare errori di lettura/scrittura.

---


### Come usare:

Si noti che SigTool NON è un'applicazione web basata su PHP (o web-app)! SigTool è un'applicazione CLI basata su PHP (o CLI-app) destinato ad essere utilizzato con terminali, shell, ecc. Può essere invocato chiamando il binario PHP con il file `sigtool.php` come primo argomento:

`$ php sigtool.php`

Informazioni di aiuto verranno visualizzate quando viene invocato SigTool, elencando le flag possibile (secondo argomento) che possono essere utilizzate quando si utilizza SigTool.

Flag possibile:
- Nessun argomento: Visualizza le informazioni di aiuto.
- `x`: Estrarre i file di firme da `daily.cvd` e `main.cvd`.
- `p`: Processare le file di firma per l'utilizzo con phpMussel.
- `m`: Scaricare `main.cvd` prima di processare.
- `d`: Scaricare `daily.cvd` prima di processare.
- `u`: Aggiornare SigTool (scarica nuovamente `sigtool.php` e die; nessun controllo effettuato).

L'output prodotto è vari file di firme per phpMussel generati direttamente dal database di firme per ClamAV, in due forme:
- File di firme che possono essere inseriti direttamente nella cartella `/vault/signatures/`.
- Copie dei file di firme compressi con GZ che possono essere utilizzati per aggiornare il repository `phpMussel/Signatures`.

L'output viene prodotto direttamente nella stessa directory come `sigtool.php`. I file di origine e tutti i file di lavoro temporanei verranno eliminati durante il funzionamento (quindi, se vuoi mantenere copie di file `daily.cvd` e `main.cvd`, è necessario eseguire copie prima di elaborare i file di firme).

Se il file YAML `signatures.dat` è incluso nella stessa directory durante il processo, le informazioni sulla versione e le checksum verranno aggiornate di conseguenza (quindi, quando si utilizza SigTool per aggiornare il repository `phpMussel/Signatures`, questo dovrebbe essere incluso).

*Nota: Se sei un utente phpMussel, ricorda che i file di firme devono essere ACTIVE per poter funzionare correttamente! Se stai utilizzando SigTool per generare nuovi file di firme, è possibile "attivarli" inserendoli nella direttiva di configurazione "Active" di phpMussel. Se stai utilizzando la pagina degli aggiornamenti del front-end per installare e aggiornare i file delle firme, è possibile "attivarli" direttamente da lì. Tuttavia, non è necessario utilizzare entrambi i metodi. Inoltre, per prestazioni ottimali di phpMussel, è consigliabile utilizzare solo i file di firme necessari per l'installazione (per esempio, se un determinato tipo di file è nella lista nera, probabilmente non avrai bisogno del file di firme corrispondenti a quel tipo di file; l'analisi dei file che sarà bloccata comunque è un lavoro superfluo e può significativamente rallentare il processo di scansione).*

Una dimostrazione video per l'utilizzo di SigTool è disponibile su YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### Elenco dei file di firme generate da SigTool:
File di firme | Descrizione
---|---
clamav.hdb | Destinato a tutti i tipi di file; Funziona con gli hash di file.
clamav.htdb | Destinato ai file HTML; Funziona con dati HTML normalizzati.
clamav_regex.htdb | Destinato ai file HTML; Funziona con dati HTML normalizzati; Le firme possono contenere espressioni regolari.
clamav.mdb | Destinato ai file PE; Funziona con i metadati di sezione PE.
clamav.ndb | Destinato a tutti i tipi di file; Funziona con dati ANSI normalizzati.
clamav_regex.ndb | Destinato a tutti i tipi di file; Funziona con dati ANSI normalizzati; Le firme possono contenere espressioni regolari.
clamav.db | Destinato a tutti i tipi di file; Funziona con dati non processati.
clamav_regex.db | Destinato a tutti i tipi di file; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_elf.db | Destinato ai file ELF; Funziona con dati non processati.
clamav_elf_regex.db | Destinato ai file ELF; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_email.db | Destinato ai file EML; Funziona con dati non processati.
clamav_email_regex.db | Destinato ai file EML; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_exe.db | Destinato ai file PE; Funziona con dati non processati.
clamav_exe_regex.db | Destinato ai file PE; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_graphics.db | Destinato ai file immagine; Funziona con dati non processati.
clamav_graphics_regex.db | Destinato ai file immagine; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_java.db | Destinato ai file Java; Funziona con dati non processati.
clamav_java_regex.db | Destinato ai file Java; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_macho.db | Destinato ai file Mach-O; Funziona con dati non processati.
clamav_macho_regex.db | Destinato ai file Mach-O; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_ole.db | Destinato agli oggetti OLE; Funziona con dati non processati.
clamav_ole_regex.db | Destinato agli oggetti OLE; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_pdf.db | Destinato ai file PDF; Funziona con dati non processati.
clamav_pdf_regex.db | Destinato ai file PDF; Funziona con dati non processati; Le firme possono contenere espressioni regolari.
clamav_swf.db | Destinato ai file SWF; Funziona con dati non processati.
clamav_swf_regex.db | Destinato ai file SWF; Funziona con dati non processati; Le firme possono contenere espressioni regolari.

---


### Nota per le estensioni dei file di firme:
*Queste informazioni verranno ampliate in futuro.*

- __cedb__: File di firme complessi estesi (questo è un formato creato per phpMussel, e non ha nulla a che vedere con il database delle firme di ClamAV; SigTool non genera alcun file di firme utilizzando questa estensione; questi sono scritti manualmente per il repository `phpMussel/Signatures`; `clamav.cedb` contiene adattamenti di alcune firme deprecate/obsolete delle versioni precedenti del database della firme di ClamAV che sono considerate ancora utili per phpMussel). File di firme che funzionano con diverse regole basate su metadati estesi generati da phpMussel utilizzano questa estensione.
- __db__: File di firme standard (questi vengono estratti dai file di firme `.ndb` contenuti in `daily.cvd` e `main.cvd`). File di firme che lavorano direttamente con il contenuto del file utilizzano questa estensione.
- __fdb__: File di firme dei nomi dei file (il database delle firme di ClamAV precedentemente li ha supportati, ma non più; SigTool non genera alcun file di firme utilizzando questa estensione; mantenuto a causa della continua utilità per phpMussel). File di firme che utilizzano i nomi di file utilizzano questa estensione.
- __hdb__: File di firme hash (questi vengono estratti dai file di firme `.hdb` contenuti in `daily.cvd` e `main.cvd`). File di firme che utilizzano hash di file utilizzano questa estensione.
- __htdb__: File di firme HTML (questi vengono estratti dai file di firme `.ndb` contenuti in `daily.cvd` e `main.cvd`). File di firme che funzionano con contenuti normalizzati come HTML usano questa estensione.
- __mdb__: File di firme PE sezionale (questi vengono estratti dai file di firme `.mdb` contenuti in `daily.cvd` e `main.cvd`). File di firme che funzionano con metadati PE sezionale utilizzano questa estensione.
- __medb__: File di firme PE esteso (questo è un formato creato per phpMussel, e non ha nulla a che vedere con il database delle firme di ClamAV; SigTool non genera alcun file di firme utilizzando questa estensione; questi sono scritti manualmente per il repository `phpMussel/Signatures`). File di firme che funzionano con metadati PE (tranne i metadati PE sezionale) utilizzano questa estensione.
- __ndb__: File di firme normalizzati (questi vengono estratti dai file di firme `.ndb` contenuti in `daily.cvd` e `main.cvd`). File di firme che funzionano con contenuti normalizzati come ANSI usano questa estensione.
- __udb__: File di firme dell'URL (questo è un formato creato per phpMussel, e non ha nulla a che vedere con il database delle firme di ClamAV; SigTool *attualmente* non genera alcun file di firme utilizzando questa estensione, anche se questo può cambiare in futuro; attualmente, questi sono scritti manualmente per il repository `phpMussel/Signatures`). File di firme che funzionano con gli URL utilizzano questa estensione.
- __ldb__: File di firme logica (questi saranno ad un certo punto, per una futura versione SigTool, estratti dai file di firme `.ldb` contenuti in `daily.cvd` e `main.cvd`, ma non sono ancora supportati da SigTool o phpMussel). File di firme che funzionano con diverse regole logiche utilizzano questa estensione.

---


*Ultima modifica: 4 Settembre 2017 (2017.09.04).*
