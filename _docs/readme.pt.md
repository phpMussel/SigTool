### Como instalar:

Antes da instalação, verifique os requisitos. Se estes não forem atendidos, o SigTool não funcionará corretamente.

#### Requisitos:
- __Para SigTool &lt;=1.0.2:__ PHP &gt;=7.0.3 com suporte phar (PHP &gt;=7.2.0 recomendado).
- __Para SigTool 1.0.3:__ PHP &gt;=7.0.3 (PHP &gt;=7.2.0 recomendado).
- __Para SigTool v2:__ PHP &gt;=7.2.0.
- __Todas versões:__ *Pelo menos* &gt;=681 MB de RAM disponível (mas, pelo menos &gt;=1 GB é fortemente recomendado).
- __Todas versões:__ Aproximadamente ~300 MBs de espaço em disco disponível (este número pode variar entre as iterações do banco de dados de assinaturas).
- __Todas versões:__ Capacidade de operar PHP no modo CLI (por exemplo, prompt de comando, terminal, shell, bash, etc).

A maneira recomendada de instalar o SigTool é através do Composer.

`composer require phpmussel/sigtool`

Como alternativa, você pode clonar o repositório ou baixar o ZIP diretamente do GitHub.

---


### Como usar:

Observe que o SigTool NÃO é um aplicativo da Web baseado em PHP (ou web-app)! SigTool é um aplicativo da CLI baseado em PHP (ou CLI-app) destinado a ser usado com terminal, shell, etc. Pode ser invocado chamando o binário PHP com o arquivo `SigTool.php` como seu primeiro argumento:

`$ php SigTool.php`

As informações de ajuda serão exibidas quando o SigTool for invocado, listando as bandeiras possíveis (segundo argumento) que podem ser usadas ao operar o SigTool.

Bandeiras possíveis:
- Sem argumentos: Exiba esta informação de ajuda.
- `x`: Extraia arquivos de assinatura a partir de `daily.cvd` e` main.cvd`.
- `p`: Processar arquivos de assinatura para uso com phpMussel.
- `m`: Baixar `main.cvd` antes do processamento.
- `d`: Baixar `daily.cvd` antes do processamento.
- `u`: Atualizar SigTool (baixe `SigTool.php` novamente e die; nenhuma verificação realizada).

Produção produzida são vários arquivos de assinatura para phpMussel gerados diretamente do banco de dados de assinaturas para ClamAV, em duas formas:
- Arquivos de assinatura que podem ser inseridos diretamente no diretório `/vault/signatures/`.
- Cópias dos arquivos de assinatura compactados com GZ que podem ser usados para atualizar o repositório `phpMussel/Signatures`.

A saída é produzida diretamente no mesmo diretório que `SigTool.php`. Os arquivos originais e todos os arquivos de trabalho temporários serão deletados durante o curso da operação (então, se quiser manter cópias dos arquivos `daily.cvd` e `main.cvd`, você deve fazer cópias antes de processá-las).

Quando usar o SigTool para gerar novos arquivos de assinatura, é possível que o antivírus do computador tente excluir ou colocar em quarentena os arquivos de assinatura novos gerados. Isso acontece porque, às vezes, os arquivos de assinatura podem conter dados muito semelhantes aos dados que o seu antivírus procura ao análise. No entanto, os arquivos de assinatura gerados pelo SigTool não contêm nenhum código executável e são completamente benignos. Se você encontrar esse problema, poderá tentar desativar temporariamente o antivírus, ou configurar o antivírus para colocar na lista branca o diretório em que está gerando novos arquivos de assinatura.

Se o arquivo YAML `signatures.dat` estiver incluído no mesmo diretório durante o processamento, informações de versão e checksums serão atualizados em conformidade (então, ao usar o SigTool para atualizar o repositório `phpMussel/Signatures`, isso deve ser incluído).

*Nota: Se você é um usuário do phpMussel, lembre-se de que os arquivos de assinatura devem ser ATIVOS para que eles funcionem corretamente! Se você estiver usando o SigTool para gerar novos arquivos de assinatura, você pode "ativá-los" listando-os na diretiva de configuração "Active" da configuração do phpMussel. Se você estiver usando a página de atualizações do front-end para instalar e atualizar arquivos de assinatura, você pode "ativá-los" diretamente da página de atualizações de front-end. No entanto, o uso de ambos os métodos não é necessário. Além disso, para o melhor desempenho do phpMussel, recomenda-se que você use apenas os arquivos de assinatura que você precisa para sua instalação (por exemplo, se algum tipo particular de arquivo estiver na lista negra, você provavelmente não precisará de arquivos de assinatura correspondentes a esse tipo de arquivo; analisando arquivos que serão bloqueados de qualquer maneira é um trabalho supérfluo e pode diminuir significativamente o processo).*

Uma demonstração vídeo para usar o SigTool está disponível no YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### SigTool gerou lista de arquivos de assinatura:
Arquivo de assinatura | Descrição
---|---
clamav.hdb | Destinado a todos os tipos de arquivos; Funciona com hashes de arquivo.
clamav.htdb | Destinado a arquivos HTML; Funciona com dados normalizados em HTML.
clamav_regex.htdb | Destinado a arquivos HTML; Funciona com dados normalizados em HTML; As assinaturas podem conter expressões regulares.
clamav.mdb | Destinado a arquivos PE; Funciona com metadados PE seccionais.
clamav.ndb | Destinado a todos os tipos de arquivos; Funciona com dados normalizados em ANSI.
clamav_regex.ndb | Destinado a todos os tipos de arquivos; Funciona com dados normalizados em ANSI; As assinaturas podem conter expressões regulares.
clamav.db | Destinado a todos os tipos de arquivos; Funciona com dados brutos.
clamav_regex.db | Destinado a todos os tipos de arquivos; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_elf.db | Destinado a arquivos ELF; Funciona com dados brutos.
clamav_elf_regex.db | Destinado a arquivos ELF; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_email.db | Destinado a arquivos EML; Funciona com dados brutos.
clamav_email_regex.db | Destinado a arquivos EML; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_exe.db | Destinado a arquivos PE; Funciona com dados brutos.
clamav_exe_regex.db | Destinado a arquivos PE; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_graphics.db | Destinado a arquivos de imagem; Funciona com dados brutos.
clamav_graphics_regex.db | Destinado a arquivos de imagem; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_java.db | Destinado a arquivos Java; Funciona com dados brutos.
clamav_java_regex.db | Destinado a arquivos Java; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_macho.db | Destinado a arquivos Mach-O; Funciona com dados brutos.
clamav_macho_regex.db | Destinado a arquivos Mach-O; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_ole.db | Destinado a objetos OLE; Funciona com dados brutos.
clamav_ole_regex.db | Destinado a objetos OLE; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_pdf.db | Destinado a arquivos PDF; Funciona com dados brutos.
clamav_pdf_regex.db | Destinado a arquivos PDF; Funciona com dados brutos; As assinaturas podem conter expressões regulares.
clamav_swf.db | Destinado a arquivos SWF; Funciona com dados brutos.
clamav_swf_regex.db | Destinado a arquivos SWF; Funciona com dados brutos; As assinaturas podem conter expressões regulares.

---


### Nota sobre extensões de arquivo de assinatura:
*Esta informação será expandida no futuro.*

- __cedb__: Arquivos de assinatura complexo estendida (este é um formato criado para o phpMussel, e não tem nada a ver com o banco de dados de assinaturas para ClamAV; o SigTool não gera nenhum arquivo de assinatura usando esta extensão; estes são escritos manualmente para o repositório `phpMussel/Signatures`; `clamav.cedb` contém adaptações de algumas assinaturas obsoletas de versões anteriores do banco de dados de assinaturas para ClamAV que são consideradas como tendo continuado utilidade para phpMussel). Arquivos de assinatura que funcionam com várias regras baseadas em metadados estendidos gerados pelo phpMussel usam essa extensão.
- __db__: Arquivos de assinatura padrão (estes são extraídos dos arquivos de assinatura `.ndb` contido por `daily.cvd` e `main.cvd`). Arquivos de assinatura que funcionam diretamente com o conteúdo do arquivo usam essa extensão.
- __fdb__: Arquivos de assinatura do nome do arquivo (o banco de dados de assinaturas para ClamAV anteriormente era compatível com assinaturas de nomes de arquivos, mas não mais; o SigTool não gera nenhum arquivo de assinatura usando esta extensão; mantido devido à continuidade da utilidade para phpMussel). Arquivos de assinatura que funcionam com nomes de arquivos usam essa extensão.
- __hdb__: Arquivos de assinatura hash (estes são extraídos dos arquivos de assinatura `.hdb` contido por `daily.cvd` e `main.cvd`). Arquivos de assinatura que funcionam com hashes de arquivos usam essa extensão.
- __htdb__: Arquivos de assinatura HTML (estes são extraídos dos arquivos de assinatura `.ndb` contido por `daily.cvd` e `main.cvd`). Arquivos de assinatura que funcionam com conteúdo normalizado em HTML usam essa extensão.
- __mdb__: Arquivos de assinatura PE seccionais (estes são extraídos dos arquivos de assinatura `.mdb` contido por `daily.cvd` e `main.cvd`). Arquivos de assinatura que funcionam com metadados PE seccionais usam essa extensão.
- __medb__: Arquivos de assinatura PE estendida (este é um formato criado para o phpMussel, e não tem nada a ver com o banco de dados de assinaturas para ClamAV; o SigTool não gera nenhum arquivo de assinatura usando esta extensão; estes são escritos manualmente para o repositório `phpMussel/Signatures`). Arquivos de assinatura que funcionam com metadados PE (além de metadados PE seccionais) usam essa extensão.
- __ndb__: Arquivos de assinatura normalizados (estes são extraídos dos arquivos de assinatura `.ndb` contido por `daily.cvd` e `main.cvd`). Arquivos de assinatura que funcionam com conteúdo normalizado em ANSI usam essa extensão.
- __udb__: Arquivos de assinatura URL (este é um formato criado para o phpMussel, e não tem nada a ver com o banco de dados de assinaturas para ClamAV; SigTool não *atualmente* gera nenhum arquivo de assinatura usando esta extensão, embora isso possa mudar no futuro; atualmente, estes são escritos manualmente para o repositório `phpMussel/Signatures`). Arquivos de assinatura que funcionam com URLs usam essa extensão.
- __ldb__: Arquivos de assinatura lógica (estes *eventualmente*, para uma versão do SigTool no futuro, será extraídos dos arquivos de assinatura `.ldb` contido por `daily.cvd` e `main.cvd`, mas ainda não são suportados pelo SigTool ou phpMussel). Arquivos de assinatura que funcionam com várias regras lógicas usam essa extensão.


---


Última Atualização: 22 de Julho de 2021 (2021.07.22).
