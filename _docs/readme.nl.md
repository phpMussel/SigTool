### Hoe te installeren:

Controleer alstublieft de vereisten voordat u installeert. Als deze niet worden voldaan, SigTool zal niet goed werken.

#### Vereisten:
- PHP &gt;= `7.0.3` met zlib + Phar ondersteuning.
- &gt;= 1GB vrije schijfruimte (als u rechtstreeks vanaf de schijf werkt) of beschikbare RAM (als u een RAM-station gebruikt; aanbevolen).
- Vermogen om PHP te bedienen in de CLI-modus (command prompt, terminal, shell, enz).

SigTool bestaat als een zelfstandig PHP bestand en heeft geen externe afhankelijkheden (afgezien van de bovenstaande eisen), en dus, het enige wat u moet doen om het te "installeren" is download `sigtool.php`.

SigTool kan normaal gesproken vanuit een disk of opslagmedium op dezelfde manier werken als een ander PHP-script. Echter, door het grote aantal lees/schrijf operaties uitgevoerd, het wordt ten zeerste aanbevolen om het van een RAM-station te bedienen, aangezien dit de snelheid zal verhogen en de overmaat lees/schrijf operaties verminderen. De uiteindelijke uitvoer mag niet hoger zijn dan ongeveer ~64MB, maar ongeveer ~1GB vrije schijfruimte of beschikbaar RAM is vereist tijdens normale werking wegens tijdelijke werkbestanden en om lees/schrijf fouten te voorkomen.

---


### Hoe te gebruiken:

Maak een notitie op dat SigTool NIET een PHP-gebaseerde web-applicatie (of web-app) is! SigTool is een PHP-gebaseerde CLI-applicatie (of CLI-app) bedoeld om gebruikt te worden met terminal, shell, enz. Het kan worden ingeroepen door het binaire PHP te bellen met het `sigtool.php` bestand als eerste argument:

`$ php sigtool.php`

Hulpinformatie wordt weergegeven wanneer SigTool wordt ingeroepen, waarbij de mogelijke vlaggen (tweede argument) worden vermeld die kunnen worden gebruikt bij het gebruik van SigTool.

Mogelijke vlaggen:
- Geen argumenten: Laat deze hulpinformatie zien.
- `x`: Extract signature bestanden uit `daily.cvd` en `main.cvd`.
- `p`: Verwerk signature bestanden voor gebruik met phpMussel.
- `m`: Download `main.cvd` voor verwerking.
- `d`: Download `daily.cvd` voor verwerking.
- `u`: Update SigTool (downloadt `sigtool.php` opnieuw en dies; geen controles uitgevoerd).

Output geproduceerd is verschillende phpMussel signature bestanden die direct uit de ClamAV signatures database worden gegenereerd, in twee vormen:
- Signature bestanden die direct in de `/vault/signatures/` map kunnen worden ingevoegd.
- GZ-gecomprimeerde kopieën van de signature bestanden die kunnen worden gebruikt om de `phpMussel/Signatures` repository te updaten.

De output wordt direct in dezelfde map als `sigtool.php` geproduceerd. Bronbestanden en alle tijdelijke werkbestanden worden tijdens het gebruik verwijderd (dus, als u kopieën van `daily.cvd` en `main.cvd` wilt houden, u moet kopieën maken voordat u de signature bestanden verwerkt).

Bij verwerking, als het `signatures.dat` YAML-bestand in dezelfde map is opgenomen, versie informatie en controlesummers worden dienovereenkomstig bijgewerkt (dus, wanneer u SigTool gebruikt om de `phpMussel/Signatures` repository te updaten, dit moet worden opgenomen).

*Opmerking: Als u een phpMussel-gebruiker bent, houd er rekening mee dat de signature bestanden ACTIVE moeten zijn om ervoor te zorgen dat ze correct werken! Als u SigTool gebruikt om nieuwe signature bestanden te genereren, u kunt ze "activeren" door ze te vermelden in de phpMussel "Active" configuratie richtlijn. Als u de frontend updates pagina gebruikt om signature bestanden te installeren en bij te werken, u kunt ze direct "activeren" vanaf de frontend updates pagina. Echter, het gebruik van beide methoden is niet nodig. Ook, voor optimale phpMussel prestaties, het wordt aanbevolen dat u alleen de signature bestanden gebruikt die u nodig heeft voor uw installatie (bijv., als een bepaald type bestand op zwarte lijst staat, u heeft waarschijnlijk geen signature bestanden nodig die overeenkomen met dat bestandstype; het analyseren van bestanden die in ieder geval geblokkeerd worden, is overbodig werk en kan het scanproces aanzienlijk vertragen).*

Een video demonstratie voor het gebruik van SigTool is beschikbaar op YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### SigTool gegenereerde signature bestanden lijst:
Signature bestand | Beschrijving
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


### Opmerking over de extensie van de signature bestanden:
*Deze informatie wordt in de toekomst uitgebreid.*

- __cedb__: Complexe uitgebreide signature bestanden (dit is een formaat gemaakt voor phpMussel, en heeft niets te maken met de ClamAV signatures database; SigTool genereert geen signature bestanden met deze extensie; deze worden handmatig voor de `phpMussel/Signatures` repository geschreven; `clamav.cedb` bevat aanpassingen van een aantal verouderde signatures van eerdere versies van de ClamAV signatures database die beschouwd worden als nog steeds bruikbaar voor phpMussel). Signature bestanden die werken met verschillende regels gebaseerd op uitgebreide metadata die door phpMussel worden gegenereerd, gebruiken deze extensie.
- __db__: Standaard signature bestanden (deze worden geëxtraheerd uit de `.ndb` signature bestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signature bestanden die direct werken met bestandsinhoud gebruiken deze extensie.
- __fdb__: Bestandsnaam signature bestanden (de ClamAV signatures database heeft voorheen de bestandsnaam signatures ondersteund, maar niet meer; SigTool genereert geen signature bestanden met deze extensie; gehandhaafd door voortdurende bruikbaarheid voor phpMussel). Signature bestanden die werken met bestandsnamen gebruiken deze extensie.
- __hdb__: Hash signature bestanden (deze worden geëxtraheerd uit de `.hdb` signature bestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signature bestanden die werken met file hashes gebruiken deze extensie.
- __htdb__: HTML signature bestanden (deze worden geëxtraheerd uit de `.ndb` signature bestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signature bestanden die werken met HTML-genormaliseerde bestandsinhoud gebruiken deze extensie.
- __mdb__: PE sectionale signature bestanden (deze worden geëxtraheerd uit de `.mdb` signature bestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signature bestanden die werken met PE sectionele metadata gebruiken deze extensie.
- __medb__: PE uitgebreide signature bestanden (dit is een formaat gemaakt voor phpMussel, en heeft niets te maken met de ClamAV signatures database; SigTool genereert geen signature bestanden met deze extensie; deze worden handmatig voor de `phpMussel/Signatures` repository geschreven). Signature bestanden die werken met PE-metadata (andere dan PE-sectiemetadata) gebruiken deze extensie.
- __ndb__: Normaliseerde signature bestanden (deze worden geëxtraheerd uit de `.ndb` signature bestanden die zijn opgenomen in `daily.cvd` en `main.cvd`). Signature bestanden die werken met ANSI-genormaliseerde bestandsinhoud gebruiken deze extensie.
- __udb__: URL signature bestanden (dit is een formaat gemaakt voor phpMussel, en heeft niets te maken met de ClamAV signatures database; SigTool genereert *momenteel* geen signature bestanden met deze extensie, hoewel dit in de toekomst kan veranderen; momenteel worden deze handmatig voor de `phpMussel/Signatures` repository geschreven). Signature bestanden die werken met URL's gebruiken deze extensie.
- __ldb__: Logische signature bestanden (deze zullen *uiteindelijk*, voor een toekomstige SigTool-versie, uit de `.ldb` signature bestanden die zijn opgenomen in `daily.cvd` en `main.cvd` worden geëxtraheerd, maar zijn nog niet ondersteund door SigTool of phpMussel). Signature bestanden die werken met verschillende logische regels gebruiken deze extensie.


---


*Laatst gewijzigd: 5 September 2017 (2017.09.05).*
