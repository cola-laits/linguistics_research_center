The .json files in this folder contain translatable text for the site.  Each file is named after its language code
(e.g. 'en.json' contains English text.)  English is the default, and will be used if the user's chosen language does
not contain that text.

## Simple text
Each localizable text contains a key and a value.  The key is the name of the text, and the value is the text itself.

en.json:
  "organization_name": "Linguistics Research Center",
es.json:
  "organization_name": "Centro de Investigación Lingüística",

## Placeholder replacement
For many of the keys, the value contains one or more placeholders, which are indicated by a colon followed by the name
of the variable being replaced.

en.json:
  "dictionary_home_header": ":language_name Dictionary"
es.json:
  "dictionary_home_header": "Diccionario de :language_name"

## Placeholder replacement with pluralization
Placeholders involving numbers may vary depending on the number.  For example, the English text for "1 word" is
different from the English text for "2 words".  To handle this, the value may contain multiple versions of the text,
separated by a pipe character (|).  The first version is used for the singular form, the second for the plural form.

en.json:
  "items_found_count": "1 word|:count words"
es.json:
  "items_found_count": "1 palabra|:count palabras"

More complicated pluralization is possible, if you need to vary the text based on the number of items found.

en.json:
  "items_found_count": "{0} no words found|{1} 1 word found|[2,*] :count words found"
es.json:
  "items_found_count": "{0} no se encontraron palabras|{1} 1 palabra encontrada|[2,*] :count palabras encontradas"

## Organization of keys

The keys are organized into sections, which are indicated by dots in the key name.

'lexicon': This is part of the 'lexicon' portion of the site.

'lexicon.general': This is text that's used on multiple pages of the lexicon.

'lexicon.general.html_head_title': This is the text that appears in the <title> tag of the HTML page, which also
shows up in the browser tab.

'lexicon.pages.(page_name)': This is text that appears in a specific type of lexicon page.  In general, page names
and URLs should match; the text on the page at /lexicon/ielex/language should be under the key 'lexicon.pages.language'.

'lexicon.pages.(page_name).html_head_title': This is the text that appears in the <title> tag of the HTML page for
a specific type of lexicon page, which is not covered by the 'lexicon.general.html_head_title' key.

'lexicon.pages.(page_name).page_title': This is the text that appears in the <h1> tag of the HTML page for a specific
type of lexicon page.  This is the text that appears large and bold at the top of the page.

'lexicon.header', 'lexicon.menu', 'lexicon.sidebar': These are text that appear across the top left, top right,
and right-hand sidebar of many pages.

'lexicon.pages.data.datatables_translation_file': The table on the Data page is powered by a library called Datatables,
which provides its own translations for most text that it generates.  (http://cdn.datatables.net/plug-ins/1.13.6/i18n/)
This key specifies the name of the Datatables translation file you want to use.  The file must be downloaded and stored
in /public/assets/datatables/plugins/i18n.  This has already been done for (Mexican) Spanish, so for Spanish you should
use 'es-MX.json' as the value for this key.


