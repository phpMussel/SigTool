### Comment installer :

Avant l'installation, vérifiez les conditions requises. Si ceux-ci ne sont pas satisfaits, SigTool ne fonctionnera pas correctement.

#### Les conditions requises :
- PHP &gt;= `7.0.3` avec support de zlib + Phar.
- &gt;= 1Go d'espace disque libre (si vous travaillez directement à partir du disque) ou RAM disponible (si vous utilisez un disque RAM ; recommandé).
- Capacité d'opérer PHP en mode CLI (invite de commande, le terminal, shell, etc).

SigTool existe en tant que fichier PHP autonome et n'a pas de dépendances externes (autres que les conditions énumérées ci-dessus), et donc, la seule chose que vous devez faire pour « l'installer », c'est télécharger `sigtool.php`.

SigTool peut fonctionner normalement à partir d'un disque ou d'un support de stockage de la même manière que tout autre script PHP. Cependant, en raison du grand nombre d'opérations de lecture/écriture qu'il effectue, il est fortement recommandé de l'utiliser à partir d'un disque RAM, pour la raison qu'il augmentera légèrement sa vitesse et diminuera les opérations excédentaires de lecture/écriture du disque. La sortie finale ne doit pas dépasser environ ~64Mo, mais environ ~1Go d'espace disque libre ou RAM disponible est requis pendant le fonctionnement normal en raison de fichiers de travail temporaires et afin d'éviter les erreurs de lecture/écriture.

---


### Comment utiliser :

Notez que SigTool n'est PAS une application Web basée sur PHP (ou web-app) ! SigTool est une application CLI basée sur PHP (ou CLI-app), destiné à être utilisé avec le terminal, shell, etc. Il peut être invoqué en appelant le binaire PHP avec le fichier `sigtool.php` comme son premier argument :

`$ php sigtool.php`

Les informations d'aide seront affichées lorsque SigTool est invoqué, en listant les drapeaux possibles (argument secondaire) qui peuvent être utilisés lors de l'utilisation de SigTool.

Drapeaux possibles :
- Aucun argument : Affichez ces informations d'aide.
- `x` : Extrayez les fichiers de signature de `daily.cvd` et` main.cvd`.
- `p` : Procurez-vous des fichiers de signature à utiliser avec phpMussel.
- `m` : Télécharger `main.cvd` avant le traitement.
- `d` : Télécharger `daily.cvd` avant le traitement.
- `u` : Mettre à jour SigTool (télécharge `sigtool.php` à nouveau et die ; aucun contrôle effectué).

La sortie produite est divers fichiers de signature pour phpMussel, généré directement à partir de la base de données de signature pour ClamAV, sous deux formes :
- Fichiers de signatures qui peuvent être insérés directement dans le répertoire `/vault/signatures/`.
- Copies des fichiers de signatures compressés avec GZ qui peuvent être utilisés pour mettre à jour le référentiel `phpMussel/Signatures`.

La sortie est produite directement dans le même répertoire que `sigtool.php`. Les fichiers source et tous les fichiers de travail temporaire seront supprimés au cours de l'opération (donc, si vous souhaitez conserver des copies des fichiers `daily.cvd` et `main.cvd`, vous devriez faire des copies avant de les traiter).

Quand vous utilisez SigTool pour générer de nouveaux fichiers de signatures, il est possible que l'analyseur antivirus de votre ordinateur tente de supprimer ou de mettre en quarantaine les fichiers de signatures nouvellement générés. Cela est dû au fait que les fichiers de signatures peuvent parfois contenir des données très similaires à celles que votre anti-virus recherche lors de l'analyse. Cependant, les fichiers de signatures générés par SigTool ne contiennent aucun code exécutable, et sont totalement inoffensifs. Si vous rencontrez ce problème, vous pouvez essayer de désactiver temporairement votre analyseur antivirus, ou configurer de manière à ce que le répertoire dans lequel vous générez de nouveaux fichiers de signatures soit ajouté à la liste blanche.

Si le fichier YAML `signatures.dat` est inclus dans le même répertoire lors du traitement, les informations de version et les sommes de contrôle seront mises à jour en conséquence (donc, lorsque vous utilisez SigTool pour mettre à jour le référentiel `phpMussel/Signatures`, cela devrait être inclus).

*Note : Si vous êtes un utilisateur phpMussel, n'oubliez pas que les fichiers de signatures doivent être ACTIF pour qu'ils fonctionnent correctement ! Si vous utilisez SigTool pour générer de nouveaux fichiers de signatures, vous pouvez les « activer » en les répertoriant dans la directive de configuration de phpMussel, « Active ». Si vous utilisez la page des mises à jour avant pour installer et mettre à jour les fichiers de signatures, vous pouvez les « activer » directement à partir de la page de mise à jour de l'accès frontal. Cependant, l'utilisation des deux méthodes n'est pas nécessaire. Aussi, pour une performance de phpMussel optimale, il est recommandé d'utiliser uniquement les fichiers de signature dont vous avez besoin pour votre installation (par exemple, si un type particulier de fichier est en liste noire, vous n'aurez probablement pas besoin de fichiers de signatures correspondant à ce type de fichier ; l'analyse des fichiers qui seront bloqués de toute façon est un travail superflu et peut considérablement ralentir le processus d'analyse).*

Une démonstration vidéo pour l'utilisation de SigTool est disponible sur YouTube : __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### Liste des fichiers de signatures générés par SigTool :
Fichier de signatures | Description
---|---
clamav.hdb | Cibles tous les types de fichiers ; Fonctionne avec des hachages de fichiers.
clamav.htdb | Cible les fichiers HTML ; Fonctionne avec des données HTML normalisées.
clamav_regex.htdb | Cible les fichiers HTML ; Fonctionne avec des données HTML normalisées ; Les signatures peuvent contenir des expressions régulières.
clamav.mdb | Cible les fichiers PE ; Fonctionne avec des métadonnées PE sectional.
clamav.ndb | Cibles tous les types de fichiers ; Fonctionne avec des données ANSI normalisées.
clamav_regex.ndb | Cibles tous les types de fichiers ; Fonctionne avec des données ANSI normalisées ; Les signatures peuvent contenir des expressions régulières.
clamav.db | Cibles tous les types de fichiers ; Fonctionne avec des données brutes.
clamav_regex.db | Cibles tous les types de fichiers ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_elf.db | Cible les fichiers ELF ; Fonctionne avec des données brutes.
clamav_elf_regex.db | Cible les fichiers ELF ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_email.db | Cible les fichiers EML ; Fonctionne avec des données brutes.
clamav_email_regex.db | Cible les fichiers EML ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_exe.db | Cible les fichiers PE ; Fonctionne avec des données brutes.
clamav_exe_regex.db | Cible les fichiers PE ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_graphics.db | Cible les fichiers image ; Fonctionne avec des données brutes.
clamav_graphics_regex.db | Cible les fichiers image ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_java.db | Cible les fichiers Java ; Fonctionne avec des données brutes.
clamav_java_regex.db | Cible les fichiers Java ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_macho.db | Cible les fichiers Mach-O ; Fonctionne avec des données brutes.
clamav_macho_regex.db | Cible les fichiers Mach-O ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_ole.db | Cible les objets OLE ; Fonctionne avec des données brutes.
clamav_ole_regex.db | Cible les objets OLE ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_pdf.db | Cible les fichiers PDF ; Fonctionne avec des données brutes.
clamav_pdf_regex.db | Cible les fichiers PDF ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.
clamav_swf.db | Cible les fichiers SWF ; Fonctionne avec des données brutes.
clamav_swf_regex.db | Cible les fichiers SWF ; Fonctionne avec des données brutes ; Les signatures peuvent contenir des expressions régulières.

---


### Remarque concernant les extensions de fichiers de signature :
*Cette information sera élargie à l'avenir.*

- __cedb__ : Fichiers de signatures étendus complexes (c'est un format créé pour phpMussel, et n'a rien à voir avec la base de données de signatures de ClamAV ; SigTool ne génère aucun fichier de signatures à l'aide de cette extension ; ceux-ci sont écrits manuellement pour le référentiel `phpMussel/Signatures` ; `clamav.cedb` contient des adaptations de certaines signatures obsolètes des versions antérieures de la base de données de signatures de ClamAV qui sont considéré comme ayant encore utilité pour phpMussel). Les fichiers de signatures qui fonctionnent avec diverses règles basées sur les métadonnées étendues générées par phpMussel utilisent cette extension.
- __db__ : Fichiers de signatures standards (ceux-ci sont extraits des fichiers de signatures `.ndb` contenu dans les `daily.cvd` et `main.cvd`). Les fichiers de signatures qui fonctionnent directement avec le contenu du fichiers utilisent cette extension.
- __fdb__ : Fichiers de signatures pour les noms de fichiers (la base de données des signatures de ClamAV précédemment supporté des signatures de noms de fichiers, mais plus maintenant ; SigTool ne génère aucun fichier de signatures à l'aide de cette extension ; maintenu en raison de l'utilité continue pour phpMussel). Les fichiers de signatures qui fonctionnent avec les noms de fichiers utilisent cette extension.
- __hdb__ : Fichiers de signatures hachage (ceux-ci sont extraits des fichiers de signatures `.hdb` contenu dans les `daily.cvd` et `main.cvd`). Les fichiers de signatures qui fonctionnent avec des hachages de fichiers utilisent cette extension.
- __htdb__ : Fichiers de signatures HTML (ceux-ci sont extraits des fichiers de signatures `.ndb` contenu dans les `daily.cvd` et `main.cvd`). Les fichiers de signatures qui fonctionnent avec du contenu HTML normalisé utilisent cette extension.
- __mdb__ : Fichiers de signatures PE sectionnelle (ceux-ci sont extraits des fichiers de signatures `.mdb` contenu dans les `daily.cvd` et `main.cvd`). Les fichiers de signatures qui fonctionnent avec des métadonnées PE sectionnelle utilisent cette extension.
- __medb__ : Fichiers de signatures étendue PE (c'est un format créé pour phpMussel, et n'a rien à voir avec la base de données de signatures de ClamAV ; SigTool ne génère aucun fichier de signatures à l'aide de cette extension ; ceux-ci sont écrits manuellement pour le référentiel `phpMussel/Signatures`). Les fichiers de signatures qui fonctionnent avec des métadonnées PE (autres que les métadonnées PE sectionnelle) utilisent cette extension.
- __ndb__ : Fichiers de signatures normalisés (ceux-ci sont extraits des fichiers de signatures `.ndb` contenu dans les `daily.cvd` et `main.cvd`). Les fichiers de signatures qui fonctionnent avec du contenu ANSI normalisé utilisent cette extension.
- __udb__ : Fichiers de signatures URL (c'est un format créé pour phpMussel, et n'a rien à voir avec la base de données de signatures de ClamAV ; SigTool ne génère *actuellement* aucun fichiers de signature à l'aide de cette extension, bien que cela puisse changer à l'avenir ; actuellement, ceux-ci sont écrits manuellement pour le référentiel `phpMussel/Signatures`). Les fichiers de signatures qui fonctionnent avec les URL utilisent cette extension.
- __ldb__ : Fichiers de signatures logique (ceux-ci sera *finalement*, pour une version SigTool à l'avenir, être extraite des fichiers de signatures `.ldb` contenu dans les `daily.cvd` et `main.cvd`, mais ne sont pas encore supporté par SigTool ou phpMussel). Les fichiers de signatures qui fonctionnent avec diverses règles logiques utilisent cette extension.


---


Dernière mise à jour : 26 Décembre 2018 (2018.12.26).
