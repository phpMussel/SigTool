### Cómo instalar:

Antes de la instalación, compruebe los requisitos. Si no se cumplen, SigTool no funcionará correctamente.

#### Requisitos:
- PHP &gt;= `7.0.3` con soporte zlib + Phar.
- &gt;= 1GB de espacio libre en disco (si trabaja directamente desde el disco) o RAM disponible (si utiliza una unidad RAM; recomendado).
- Capacidad para operar PHP en modo CLI (comando, terminal, shell, etc).

SigTool existe como un archivo PHP autónomo y no tiene dependencias externas (distintos de los requisitos enumerados anteriormente), y así, lo único que tienes que hacer para "instalarlo", es descargar `sigtool.php`.

SigTool puede funcionar normalmente desde un disco o medio de almacenamiento de la misma manera que cualquier otro script PHP. Aunque, debido al gran número de operaciones de lectura/escritura que realiza, es altamente recomendable operarlo desde una unidad RAM, ya que esto aumentará ligeramente su velocidad y disminuirá el exceso de operaciones de lectura/escritura de disco. La salida final no debe exceder aproximadamente ~64MBs, pero aproximadamente ~1GB de espacio libre en disco o RAM disponible se requiere durante el funcionamiento normal debido a los archivos temporales de trabajo ya fin de evitar errores de lectura/escritura.

---

### Cómo utilizar:

Tenga en cuenta que SigTool NO es una aplicación web basada en PHP (o web-app)! SigTool es una aplicación CLI basada en PHP (o CLI-app) destinado a ser utilizado con terminal, shell, etc. Se puede invocar llamando al binario PHP con el archivo `sigtool.php` como su primer argumento:

`$ php sigtool.php`

La información de ayuda se mostrará cuando se invoque SigTool, enumerando las posibles banderas (segundo argumento) que se pueden utilizar cuando se utiliza SigTool.

Las posibles banderas:
- Sin argumentos: Muestra esta información de ayuda.
- `x`: Extraiga archivos de firmas de `daily.cvd` y `main.cvd`.
- `p`: Procese archivos de firmas para uso con phpMussel.
- `m`: Descargue `main.cvd` antes de procesar.
- `d`: Descargue `daily.cvd` antes de procesar.
- `u`: Actualizar SigTool (descarga `sigtool.php` de nuevo y die; no se realizan cualquier comprobaciones).

La salida producida es varios archivos de firmas para phpMussel generados directamente de la base de datos de firmas para ClamAV, de dos formas:
- Archivos de firmas que se pueden insertar directamente en el directorio `/vault/signatures/`.
- Copias de los archivos de firmas comprimidos con GZ que se pueden utilizar para actualizar el repositorio `phpMussel/Signatures`.

La salida se produce directamente en el mismo directorio que `sigtool.php`. Los archivos fuente y todos los archivos de trabajo temporales se borrarán durante el curso de la operación (por lo tanto, si desea guardar copias de `daily.cvd` y `main.cvd`, debe hacer copias antes de procesar los archivos de firmas).

Si el archivo de YAML `signatures.dat` se incluye en el mismo directorio al procesar, la información de la versión y las sumas de comprobación se actualizarán en consecuencia (por lo tanto, al usar SigTool para actualizar el repositorio `phpMussel/Signatures`, esto debe ser incluido).

*Nota: Si eres un usuario de phpMussel, por favor recuerda que los archivos de firma deben estar ACTIVOS para que funcionen correctamente. Si utiliza SigTool para generar nuevos archivos de firmas, puedes "activarlos" enumerándolos en la directiva de configuración "Active" de phpMussel. Si está utilizando la página de actualizaciones del front-end para instalar y actualizar archivos de firmas, usted puede "activar" ellos directamente de allí. Aunque, no es necesario usar ambos métodos. Además, para un rendimiento óptimo de phpMussel, se recomienda que sólo utilice los archivos de firmas que necesita para su instalación (p.ej., si algún tipo particular de archivo está en la lista negra, probablemente no necesitará archivos de firmas correspondientes a ese tipo de archivo; analizar los archivos que se bloquearán de todos modos es un trabajo superfluo y puede ralentizar significativamente el proceso de escaneo).*

Una demostración de vídeo para usar SigTool está disponible en YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---

### Lista de archivos de firmas generados por SigTool:
Archivo de firmas | Descripción
---|---
clamav.hdb | Destinado a todo tipo de archivos; Funciona con hash de archivos.
clamav.htdb | Destinado a archivos HTML; Funciona con datos normalizados en HTML.
clamav_regex.htdb | Destinado a archivos HTML; Funciona con datos normalizados en HTML; Las firmas pueden contener expresiones regulares.
clamav.mdb | Destinado a archivos PE; Funciona con metadatos seccionales PE.
clamav.ndb | Destinado a todo tipo de archivos; Funciona con datos normalizados en ANSI.
clamav_regex.ndb | Destinado a todo tipo de archivos; Funciona con datos normalizados en ANSI; Las firmas pueden contener expresiones regulares.
clamav.db | Destinado a todo tipo de archivos; Funciona con datos sin procesar.
clamav_regex.db | Destinado a todo tipo de archivos; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_elf.db | Destinado a archivos ELF; Funciona con datos sin procesar.
clamav_elf_regex.db | Destinado a archivos ELF; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_email.db | Destinado a archivos EML; Funciona con datos sin procesar.
clamav_email_regex.db | Destinado a archivos EML; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_exe.db | Destinado a archivos PE; Funciona con datos sin procesar.
clamav_exe_regex.db | Destinado a archivos PE; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_graphics.db | Destinado a archivos de imagen; Funciona con datos sin procesar.
clamav_graphics_regex.db | Destinado a archivos de imagen; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_java.db | Destinado a archivos Java; Funciona con datos sin procesar.
clamav_java_regex.db | Destinado a archivos Java; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_macho.db | Destinado a archivos Mach-O; Funciona con datos sin procesar.
clamav_macho_regex.db | Destinado a archivos Mach-O; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_ole.db | Destinado a objetos OLE; Funciona con datos sin procesar.
clamav_ole_regex.db | Destinado a objetos OLE; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_pdf.db | Destinado a archivos PDF; Funciona con datos sin procesar.
clamav_pdf_regex.db | Destinado a archivos PDF; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.
clamav_swf.db | Destinado a archivos SWF; Funciona con datos sin procesar.
clamav_swf_regex.db | Destinado a archivos SWF; Funciona con datos sin procesar; Las firmas pueden contener expresiones regulares.

---

### Nota relativa a las extensiones de archivos de firmas:
*Esta información se ampliará en el futuro.*

- __cedb__: Archivos de firmas complejos extendidas (este es un formato creado para phpMussel, y no tiene nada que ver con la base de datos de firmas para ClamAV; SigTool no genera ningún archivo de firmas usando esta extensión; estos se escriben manualmente para el repositorio `phpMussel/Signatures`; `clamav.cedb` contiene adaptaciones de algunas firmas obsoletas de versiones anteriores de la base de datos de firmas para ClamAV que se considera que todavía tienen utilidad continua para phpMussel). Archivos de firmas que funcionan con varias reglas basadas en metadatos extendidos generados por phpMussel utilizan esta extensión.
- __db__: Archivos de firmas estándar (estos se extraen de los archivos de firmas `.ndb` contenidos por `daily.cvd` y `main.cvd`). Archivos de firmas que trabajan directamente con el contenido del archivo utilizan esta extensión.
- __fdb__: Archivos de firmas de nombres de archivos (la base de datos de firmas para ClamAV antes apoyaba firmas de nombres de archivos, pero ya no; SigTool no genera ningún archivo de firmas usando esta extensión; mantenido debido a la utilidad continua para phpMussel). Archivos de firmas que funcionan con nombres de archivos utilizan esta extensión.
- __hdb__: Archivos de firmas de hash (estos se extraen de los archivos de firmas `.hdb` contenidos por `daily.cvd` y `main.cvd`). Archivos de firmas que trabajan con hashes de archivos utilizan esta extensión.
- __htdb__: Archivos de firmas HTML (estos se extraen de los archivos de firmas `.ndb` contenidos por `daily.cvd` y `main.cvd`). Archivos de firmas que trabajan con contenido normalizado en HTML utilizan esta extensión.
- __mdb__: Archivos de firmas seccionales PE (estos se extraen de los archivos de firmas `.mdb` contenidos por `daily.cvd` y `main.cvd`). Archivos de firmas que trabajan con metadatos seccionales PE utilizan esta extensión.
- __medb__: Archivos de firmas PE extendida (este es un formato creado para phpMussel, y no tiene nada que ver con la base de datos de firmas para ClamAV; SigTool no genera ningún archivo de firmas usando esta extensión; estos se escriben manualmente para el repositorio `phpMussel/Signatures`). Archivos de firmas que funcionan con metadatos PE (distintos de los metadatos seccionales PE) utilizan esta extensión.
- __ndb__: Archivos de firmas normalizados (estos se extraen de los archivos de firmas `.ndb` contenidos por `daily.cvd` y `main.cvd`). Archivos de firmas que trabajan con contenido normalizado en ANSI utilizan esta extensión.
- __udb__: Archivos de firmas de URL (este es un formato creado para phpMussel, y no tiene nada que ver con la base de datos de firmas para ClamAV; SigTool no genera *actualmente* ningún archivo de firmas usando esta extensión, aunque esto puede cambiar en el futuro; actualmente, estos se escriben manualmente para el repositorio `phpMussel/Signatures`). Archivos de firmas que funcionan con URL usan esta extensión.
- __ldb__: Archivos de firmas lógicas (estos *eventualmente*, para una futura versión de SigTool, serán extraídos del archivos de firmas `.ldb` contenidos por `daily.cvd` y `main.cvd`, pero aún no son soportados con SigTool o phpMussel). Archivos de firmas que trabajan con varias reglas lógicas usan esta extensión.


---

*Última modificación: 4 Septiembre 2017 (2017.09.04).*
