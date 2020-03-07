### Installation:

Vor der Installation, bitte überprüfen Sie, was erforderlich ist. Wenn was erforderlich ist nicht erfüllt ist, SigTool wird nicht richtig funktionieren.

#### Erforderlich:
- PHP &gt;= `7.0.3` mit zlib + phar unterstützung.
- &gt;= 1GB freier Festplattenspeicher (wenn direkt von der Festplatte arbeiten) oder verfügbarer RAM (wenn Sie ein RAM-Laufwerk verwenden; empfohlen).
- Möglichkeit für PHP im CLI-Modus zu betreiben (Eingabeaufforderung, Terminal, Shell, u.s.w.).

Um SigTool zu installieren, laden Sie einfach `SigTool.php` und `YAML.php` herunter. :-)

SigTool kann auf normale Weise von einem Datenträger oder Speichermedium auf die gleiche Weise wie jedes andere PHP-Skript arbeiten. Aufgrund jedoch der großen Anzahl der ausgeführten Lese/Schreibvorgänge, es wird dringend empfohlen, es von einem RAM-Laufwerk aus zu betreiben, weil dies seine Geschwindigkeit etwas erhöhen und überschüssige Lese/Schreibvorgänge reduzieren. Die endgültige Ausgabe sollte ungefähr ~64MB nicht überschreiten, aber im normalen Betrieb sind ungefähr ~1GB freier Festplattenspeicher oder verfügbarer RAM erforderlich aufgrund temporärer Arbeitsdateien und um Lese/Schreibfehler zu vermeiden.

---


### Benutzung:

Beachten Sie dass SigTool ist KEINE PHP-basierte Web-App! SigTool ist eine PHP-basierte CLI-App, bestimmt zur Verwendung mit Terminal, Shell, u.s.w. Es kann aufgerufen werden, indem die PHP-Binärdatei mit der Datei `SigTool.php` als erstes Argument aufgerufen:

`$ php SigTool.php`

Hilfeinformationen werden angezeigt, wenn SigTool aufgerufen wird, der möglichen Flaggen auflistet (das zweite Argument), die beim Betrieb von SigTool verwendet werden können.

Möglichen Flaggen:
- Keine Argumente: Zeigt diese Hilfeinformationen.
- `x`: Extrahiert Signaturdateien aus "daily.cvd" und "main.cvd".
- `p`: Verarbeitet Signaturdateien zur Verwendung mit phpMussel.
- `m`: Holt `main.cvd` vor der Verarbeitung.
- `d`: Holt `daily.cvd` vor der Verarbeitung.
- `u`: Aktualisiert SigTool (lädt `SigTool.php` erneut herunter und die; es werden keine Prüfungen durchgeführt).

Die Ausgabe ist verschiedenen phpMussel-Signaturdateien, direkt aus der ClamAV-Signaturdatenbank in zwei Formen generiert:
- Signaturdateien, die direkt in das `/vault/signatures/`-Verzeichnis eingefügt werden können.
- GZ-komprimierte Kopien der Signaturdateien, mit denen das `phpMussel/Signatures`-Repository aktualisiert werden kann.

Die Ausgabe wird direkt in demselben Verzeichnis wie `SigTool.php` erzeugt. Quelldateien und alle temporären Arbeitsdateien werden im laufenden Betrieb gelöscht (so, wenn Sie Kopien von `daily.cvd` und `main.cvd` behalten möchten, Sie sollten Kopien erstellen, bevor Sie die Signaturdateien verarbeiten).

Wenn Sie mit SigTool neue Signaturdateien erstellen, kann es sein dass der Antiviren-Scanner Ihres Computers versucht, die neu erstellten Signaturdateien zu löschen oder in die Quarantäne zu stellen. Dies ist darauf zurückzuführen, dass die Signaturdateien manchmal Daten enthalten, die den Daten sehr ähnlich sind, nach denen Ihr Antivirus beim Scannen sucht. Die von SigTool erzeugten Signaturdateien enthalten jedoch keinen ausführbaren Code und sind völlig harmlos. Wenn dieses Problem auftritt, können Sie versuchen, Ihren Antiviren-Scanner vorübergehend zu deaktivieren, oder Ihren Antiviren-Scanner so konfigurieren, dass das Verzeichnis, in dem Sie neue Signaturdateien erstellen, in die Whitelist aufgenommen wird.

Wenn sich die `signatures.dat` YAML-Datei bei der Verarbeitung in demselben Verzeichnis befindet, Versionsinformationen und Prüfsummen werden entsprechend aktualisiert (so, wenn Sie SigTool verwenden um das `phpMussel/Signatures`-Repository zu aktualisieren, dies sollte enthalten sein).

*Beachten: Wenn Sie phpMussel-Benutzer sind, bitte bedenke, dass Signaturdateien AKTIV sein müssen, damit sie korrekt funktionieren! Wenn Sie SigTool verwenden um neue Signaturdateien zu erstellen, Sie können sie "aktivieren", indem Sie sie in der "Active"-Direktive der phpMussel-Konfiguration auflisten. Wenn Sie die Frontend-Aktualisierungsseite verwenden, um Signaturdateien zu installieren und zu aktualisieren, Sie können sie direkt von der Frontend-Aktualisierungsseite aus aktivieren. Beide Methoden verwenden sind jedoch nicht erforderlich. Auch, für optimale Leistung, es wird empfohlen nur verwenden Sie die Signaturdateien, die Sie für Ihre Installation benötigen (z.B., wenn ein bestimmter Dateityp auf der schwarzen Liste steht, Sie werden wahrscheinlich keine Signaturdateien benötigen, die diesem Dateityp entsprechen; das Analysieren von Dateien, die trotzdem blockiert werden, ist überflüssige Arbeit und kann den Scanvorgang erheblich verlangsamen).*

Eine Videodemo zur Verwendung von SigTool ist auf YouTube verfügbar: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### Liste der von SigTool erzeugten Signaturdateien:
Signaturdatei | Beschreibung
---|---
clamav.hdb | Es handelt sich an alle Arten von Dateien; Funktioniert mit Datei-Prüfsummen.
clamav.htdb | Es handelt sich um HTML-Dateien; Funktioniert mit HTML-normalisierten Daten.
clamav_regex.htdb | Es handelt sich um HTML-Dateien; Funktioniert mit HTML-normalisierten Daten; Signaturen können Reguläre Ausdrücke enthalten.
clamav.mdb | Es handelt sich um PE-Dateien; Funktioniert mit PE-Sektional-Metadaten.
clamav.ndb | Es richtet sich an alle Arten von Dateien; Funktioniert mit ANSI-normalisierten Daten.
clamav_regex.ndb | Es richtet sich an alle Arten von Dateien; Funktioniert mit ANSI-normalisierten Daten; Signaturen können Reguläre Ausdrücke enthalten.
clamav.db | Es richtet sich an alle Arten von Dateien; Funktioniert mit Rohdaten.
clamav_regex.db | Es richtet sich an alle Arten von Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_elf.db | Es handelt sich um ELF-Dateien; Funktioniert mit Rohdaten.
clamav_elf_regex.db | Es handelt sich um ELF-Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_email.db | Es handelt sich um EML-Dateien; Funktioniert mit Rohdaten.
clamav_email_regex.db | Es handelt sich um EML-Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_exe.db | Es handelt sich um PE-Dateien; Funktioniert mit Rohdaten.
clamav_exe_regex.db | Es handelt sich um PE-Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_graphics.db | Es handelt sich um Bilddateien; Funktioniert mit Rohdaten.
clamav_graphics_regex.db | Es handelt sich um Bilddateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_java.db | Es handelt sich um Java-Dateien; Funktioniert mit Rohdaten.
clamav_java_regex.db | Es handelt sich um Java-Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_macho.db | Es handelt sich um Mach-O-Dateien; Funktioniert mit Rohdaten.
clamav_macho_regex.db | Es handelt sich um Mach-O-Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_ole.db | Es handelt sich um OLE-Objekte; Funktioniert mit Rohdaten.
clamav_ole_regex.db | Es handelt sich um OLE-Objekte; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_pdf.db | Es handelt sich um PDF-Dateien; Funktioniert mit Rohdaten.
clamav_pdf_regex.db | Es handelt sich um PDF-Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.
clamav_swf.db | Es handelt sich um SWF-Dateien; Funktioniert mit Rohdaten.
clamav_swf_regex.db | Es handelt sich um SWF-Dateien; Funktioniert mit Rohdaten; Signaturen können Reguläre Ausdrücke enthalten.

---


### Hinweis zu Signaturdateierweiterungen:
*Diese Informationen werden in Zukunft erweitert.*

- __cedb__: Komplexe erweiterte Signaturdateien (dies ist ein für phpMussel erstelltes Format, und hat keinen Bezug zur ClamAV-Signaturdatenbank; SigTool generiert keine Signaturdateien mit dieser Erweiterung; diese werden manuell für das `phpMussel/Signatures`-Repository geschrieben; `clamav.cedb` enthält Anpassungen einiger obsolet/veralteter Signaturen aus früheren Versionen der ClamAV-Signaturen-Datenbank, die weiterhin als nützlich für phpMussel gelten). Signaturdateien, die mit verschiedenen Regeln arbeiten, die auf erweiterten Metadaten basieren, die von phpMussel generiert werden, verwenden diese Erweiterung.
- __db__: Standard-Signaturdateien (diese werden aus den in `daily.cvd` und `main.cvd` enthaltenen `.ndb`-Signaturdateien extrahiert). Signaturdateien, die direkt mit dem Dateiinhalt arbeiten, verwenden diese Erweiterung.
- __fdb__: Dateiname-Signaturdateien (Die Datenbank der ClamAV-Signaturen früher unterstützte Dateinamensignaturen, aber nicht länger; SigTool generiert keine Signaturdateien mit dieser Erweiterung; beibehalten wegen der fortgesetzten Nützlichkeit für phpMussel). Signaturdateien, die mit Dateinamen arbeiten, verwenden diese Erweiterung.
- __hdb__: Prüfsummen-Signaturdateien (diese werden aus den in `daily.cvd` und `main.cvd` enthaltenen `.hdb`-Signaturdateien extrahiert). Signaturdateien, die mit Dateiprüfsummen arbeiten, verwenden diese Erweiterung.
- __htdb__: HTML-Signaturdateien (diese werden aus den in `daily.cvd` und `main.cvd` enthaltenen `.ndb`-Signaturdateien extrahiert). Signaturdateien, die mit HTML-normalisierten Dateiinhalten arbeiten, verwenden diese Erweiterung.
- __mdb__: PE-Sektional-Signaturdateien (diese werden aus den in `daily.cvd` und `main.cvd` enthaltenen `.mdb`-Signaturdateien extrahiert). Signaturdateien, die mit PE-Sektional-Metadaten arbeiten, verwenden diese Erweiterung.
- __medb__: PE-Erweiterte-Signaturdateien (dies ist ein für phpMussel erstelltes Format, und hat keinen Bezug zur ClamAV-Signaturdatenbank; SigTool generiert keine Signaturdateien mit dieser Erweiterung; diese werden manuell für das `phpMussel/Signatures`-Repository geschrieben). Signaturdateien, die mit PE-Metadaten (außer PE-Sektional-Metadaten) arbeiten, verwenden diese Erweiterung.
- __ndb__: Normalisierte Signaturdateien (diese werden aus den in `daily.cvd` und `main.cvd` enthaltenen `.ndb`-Signaturdateien extrahiert). Signaturdateien, die mit ANSI-normalisierten Dateiinhalten arbeiten, verwenden diese Erweiterung.
- __udb__: URL-Signaturdateien (dies ist ein für phpMussel erstelltes Format, und hat keinen Bezug zur ClamAV-Signaturdatenbank; SigTool generiert *derzeit* keine Signaturdateien mit dieser Erweiterung, dies kann sich jedoch in Zukunft ändern; derzeit werden diese manuell für das `phpMussel/Signatures`-Repository geschrieben). Signaturdateien, die mit URLs arbeiten, verwenden diese Erweiterung.
- __ldb__: Logische Signaturdateien (diese werden *eventuell*, für eine zukünftige SigTool-Version, aus den in `daily.cvd` und `main.cvd` enthaltenen `.ldb`-Signaturdateien extrahiert, aber noch nicht von SigTool oder phpMussel unterstützt). Signaturdateien, die mit verschiedenen logischen Regeln arbeiten, verwenden diese Erweiterung.


---


Zuletzt aktualisiert: 7 März 2020 (2020.03.07).
