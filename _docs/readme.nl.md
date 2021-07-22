### Hoe te installeren:

Controleer alstublieft de vereisten voordat u installeert. Als deze niet worden voldaan, SigTool zal niet goed werken.

#### Vereisten:
- __Voor SigTool &lt;=1.0.2:__ PHP &gt;=7.0.3 met phar ondersteuning (PHP &gt;=7.2.0 aanbevolen).
- __Voor SigTool 1.0.3:__ PHP &gt;=7.0.3 (PHP &gt;=7.2.0 aanbevolen).
- __Voor SigTool v2:__ PHP &gt;=7.2.0.
- __Alle versies:__ *Ten minste* &gt;=681 MB beschikbaar RAM-geheugen (maar ten minste &gt;=1 GB wordt sterk aanbevolen).
- __Alle versies:__ Ongeveer ~300 MB beschikbare schijfruimte (dit aantal kan variëren tussen iteraties van de signatures database).
- __Alle versies:__ Mogelijkheid om PHP in CLI-modus te gebruiken (b.v., opdrachtprompt, terminal, shell, bash, enz).

The recommended way to install SigTool is through Composer.

`composer require phpmussel/sigtool`

Als alternatief kunt u de repository klonen of de ZIP downloaden rechtstreeks van GitHub.

---


### Hoe te gebruiken:

Maak een notitie op dat SigTool NIET een PHP-gebaseerde web-applicatie (of web-app) is! SigTool is een PHP-gebaseerde CLI-applicatie (of CLI-app) bedoeld om gebruikt te worden met terminal, shell, enz. Het kan worden ingeroepen door het binaire PHP te bellen met het `SigTool.php` bestand als eerste argument:

`$ php SigTool.php`

Hulpinformatie wordt weergegeven wanneer SigTool wordt ingeroepen, waarbij de mogelijke vlaggen (tweede argument) worden vermeld die kunnen worden gebruikt bij het gebruik van SigTool.

Mogelijke vlaggen:
- Geen argumenten: Laat deze hulpinformatie zien.
- `x`: Extract signatuurbestanden uit `daily.cvd` en `main.cvd`.
- `p`: Verwerk signatuurbestanden voor gebruik met phpMussel.
- `m`: Download `main.cvd` voor verwerking.
- `d`: Download `daily.cvd` voor verwerking.
- `u`: Update SigTool (downloadt `SigTool.php` opnieuw en dies; geen controles uitgevoerd).

Output geproduceerd is verschillende phpMussel signatuurbestanden die direct uit de ClamAV signatures database worden gegenereerd, in twee vormen:
- Signatuurbestanden die direct in de `/vault/signatures/` map kunnen worden ingevoegd.
- GZ-gecomprimeerde kopieën van de signatuurbestanden die kunnen worden gebruikt om de `phpMussel/Signatures` repository te updaten.

De output wordt direct in dezelfde map als `SigTool.php` geproduceerd. Bronbestanden en alle tijdelijke werkbestanden worden tijdens het gebruik verwijderd (dus, als u kopieën van `daily.cvd` en `main.cvd` wilt houden, u moet kopieën maken voordat u de signatuurbestanden verwerkt).

Wanneer u SigTool gebruikt om nieuwe signatuurbestanden te genereren, is het mogelijk dat de antivirus-scanner van uw computer de nieuw gegenereerde signatuurbestanden probeert te verwijderen of in quarantaine te plaatsen. Dit gebeurt omdat de signatuurbestanden soms gegevens bevatten die erg lijken op de gegevens waarnaar uw antivirus-scanner zoekt tijdens het scannen. De signatuurbestanden gegenereerd door SigTool bevatten echter geen uitvoerbare code en zijn volledig goedaardig. Als u dit probleem ondervindt, kunt u proberen uw antivirus-scanner tijdelijk uit te schakelen, of uw antivirus-scanner configureren om de bestandsmap op de witte lijst opnemen waar u nieuwe signatuurbestanden genereert.

Bij verwerking, als het `signatures.dat` YAML-bestand in dezelfde map is opgenomen, versie informatie en controlesummers worden dienovereenkomstig bijgewerkt (dus, wanneer u SigTool gebruikt om de `phpMussel/Signatures` repository te updaten, dit moet worden opgenomen).

*Opmerking: Als u een phpMussel-gebruiker bent, houd er rekening mee dat de signatuurbestanden ACTIVE moeten zijn om ervoor te zorgen dat ze correct werken! Als u SigTool gebruikt om nieuwe signatuurbestanden te genereren, u kunt ze "activeren" door ze te vermelden in de phpMussel "Active" configuratie richtlijn. Als u de frontend updates pagina gebruikt om signatuurbestanden te installeren en bij te werken, u kunt ze direct "activeren" vanaf de frontend updates pagina. Echter, het gebruik van beide methoden is niet nodig. Ook, voor optimale phpMussel prestaties, het wordt aanbevolen dat u alleen de signatuurbestanden gebruikt die u nodig heeft voor uw installatie (bijv., als een bepaald type bestand op zwarte lijst staat, u heeft waarschijnlijk geen signatuurbestanden nodig die overeenkomen met dat bestandstype; het analyseren van bestanden die in ieder geval geblokkeerd worden, is overbodig werk en kan het scanproces aanzienlijk vertragen).*

Een video demonstratie voor het gebruik van SigTool is beschikbaar op YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### SigTool gegenereerde signatuurbestanden lijst:
Signatuurbestand | Beschrijving
---|---
clamav.hdb | Bedoeld voor alle soorten bestanden; Werkt met bestand hashes.
clamav.htdb | Bedoeld voor HTML-bestanden; Werkt met HTML-genormaliseerde gegevens.
clamav_regex.htdb | Bedoeld voor HTML-bestanden; Werkt met HTML-genormaliseerde gegevens; Signatures kunnen reguliere expressies bevatten.
clamav.mdb | Bedoeld voor PE-bestanden; Werkt met PE sectionele metadata.
clamav.ndb | Bedoeld voor alle soorten bestanden; Werkt met ANSI-genormaliseerde gegevens.
clamav_regex.ndb | Bedoeld voor alle soorten bestanden; Werkt met ANSI-genormaliseerde gegevens; Signatures kunnen reguliere expressies bevatten.
clamav.db | Bedoeld voor alle soorten bestanden; Werkt met rauwe data.
clamav_regex.db | Bedoeld voor alle soorten bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_elf.db | Bedoeld voor ELF-bestanden; Werkt met rauwe data.
clamav_elf_regex.db | Bedoeld voor ELF-bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_email.db | Bedoeld voor EML-bestanden; Werkt met rauwe data.
clamav_email_regex.db | Bedoeld voor EML-bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_exe.db | Bedoeld voor PE-bestanden; Werkt met rauwe data.
clamav_exe_regex.db | Bedoeld voor PE-bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_graphics.db | Bedoeld voor beeldbestanden; Werkt met rauwe data.
clamav_graphics_regex.db | Bedoeld voor beeldbestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_java.db | Bedoeld voor Java-bestanden; Werkt met rauwe data.
clamav_java_regex.db | Bedoeld voor Java-bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_macho.db | Bedoeld voor Mach-O-bestanden; Werkt met rauwe data.
clamav_macho_regex.db | Bedoeld voor Mach-O-bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_ole.db | Bedoeld voor OLE-objecten; Werkt met rauwe data.
clamav_ole_regex.db | Bedoeld voor OLE-objecten; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_pdf.db | Bedoeld voor PDF-bestanden; Werkt met rauwe data.
clamav_pdf_regex.db | Bedoeld voor PDF-bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.
clamav_swf.db | Bedoeld voor SWF-bestanden; Werkt met rauwe data.
clamav_swf_regex.db | Bedoeld voor SWF-bestanden; Werkt met rauwe data; Signatures kunnen reguliere expressies bevatten.

---


### Opmerking over de extensie van de signatuurbestanden:
*Deze informatie wordt in de toekomst uitgebreid.*

- __cedb__: Complexe uitgebreide signatuurbestanden (dit is een formaat gemaakt voor phpMussel, en heeft niets te maken met de ClamAV signatures database; SigTool genereert geen signatuurbestanden met deze extensie; deze worden handmatig voor de `phpMussel/Signatures` repository geschreven; `clamav.cedb` bevat aanpassingen van een aantal verouderde signatures van eerdere versies van de ClamAV signatures database die beschouwd worden als nog steeds bruikbaar voor phpMussel). Signatuurbestanden die werken met verschillende regels gebaseerd op uitgebreide metadata die door phpMussel worden gegenereerd, gebruiken deze extensie.
- __db__: Standaard signatuurbestanden (deze worden geëxtraheerd uit de `.ndb` signatuurbestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signatuurbestanden die direct werken met bestandsinhoud gebruiken deze extensie.
- __fdb__: Bestandsnaam signatuurbestanden (de ClamAV signatures database heeft voorheen de bestandsnaam signatures ondersteund, maar niet meer; SigTool genereert geen signatuurbestanden met deze extensie; gehandhaafd door voortdurende bruikbaarheid voor phpMussel). Signatuurbestanden die werken met bestandsnamen gebruiken deze extensie.
- __hdb__: Hash signatuurbestanden (deze worden geëxtraheerd uit de `.hdb` signatuurbestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signatuurbestanden die werken met file hashes gebruiken deze extensie.
- __htdb__: HTML signatuurbestanden (deze worden geëxtraheerd uit de `.ndb` signatuurbestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signatuurbestanden die werken met HTML-genormaliseerde bestandsinhoud gebruiken deze extensie.
- __mdb__: PE sectionale signatuurbestanden (deze worden geëxtraheerd uit de `.mdb` signatuurbestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signatuurbestanden die werken met PE sectionele metadata gebruiken deze extensie.
- __medb__: PE uitgebreide signatuurbestanden (dit is een formaat gemaakt voor phpMussel, en heeft niets te maken met de ClamAV signatures database; SigTool genereert geen signatuurbestanden met deze extensie; deze worden handmatig voor de `phpMussel/Signatures` repository geschreven). Signatuurbestanden die werken met PE-metadata (andere dan PE-sectiemetadata) gebruiken deze extensie.
- __ndb__: Normaliseerde signatuurbestanden (deze worden geëxtraheerd uit de `.ndb` signatuurbestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signatuurbestanden die werken met ANSI-genormaliseerde bestandsinhoud gebruiken deze extensie.
- __udb__: URL signatuurbestanden (dit is een formaat gemaakt voor phpMussel, en heeft niets te maken met de ClamAV signatures database; SigTool genereert *momenteel* geen signatuurbestanden met deze extensie, hoewel dit in de toekomst kan veranderen; momenteel worden deze handmatig voor de `phpMussel/Signatures` repository geschreven). Signatuurbestanden die werken met URL's gebruiken deze extensie.
- __ldb__: Logische signatuurbestanden (deze zullen *uiteindelijk*, voor een toekomstige SigTool-versie, uit de `.ldb` signatuurbestanden die zijn opgenomen in `daily.cvd` en `main.cvd` worden geëxtraheerd, maar zijn nog niet ondersteund door SigTool of phpMussel). Signatuurbestanden die werken met verschillende logische regels gebruiken deze extensie.


---


Laatste Bijgewerkt: 22 Juli 2021 (2021.07.22).
