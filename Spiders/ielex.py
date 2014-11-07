from bs4 import BeautifulSoup
import urllib, urllib2, cookielib
import re

entries = []
parts_of_speech = {}
sources = {}
ctr = 0

path = "http://www.utexas.edu/cola/centers/lrc/ielex/PokornyMaster-X.html"
html = urllib2.urlopen(path).read()
#print html

page = BeautifulSoup(html)
#print page
trs = page.find_all("tr")
for tr in trs:
    entry = {}

    tds = tr.find_all("td")

    #skip rows without tds (th)
    if len(tds) == 0:
        continue
    #endif

    ctr += 1
    entry['id'] = tds[0].find("span")['id'][1:]
    entry['page'] = tds[0].text
    
    temp_entry = unicode(tds[1])
    temp_entry = temp_entry.replace('<td>','')
    temp_entry = temp_entry.replace('</td>','')
    entry['entry'] = temp_entry
    
    #entry['html_language_code'] = tds[1].find("span")['lang']
    entry['gloss'] = tds[3].text
    entry['cross'] = []
    entry['semantics'] = []
    entry['reflexes'] = []

    tags = tds[2].find_all("a")
    for tag in tags:
        #print tag['href']
        #cross references our links to anchors and start with #
        if tag['href'][0] == '#':
            entry['cross'].append(tag['href'][2:])
        #endif

        #links to the reflex start with X    
        if tag['href'][0] == 'X':
            lex_path = 'http://www.utexas.edu/cola/centers/lrc/ielex/' + tag['href']
            lex_html = urllib2.urlopen(lex_path).read()
            lex_page = BeautifulSoup(lex_html)

            #get semantic links
            a_tags = lex_page.find('b', text = re.compile("Semantic Field")).parent.find_all('a')
            for a_tag in a_tags:
                url = a_tag['href']
                pre = url.split('/')[-2]
                post = url.split('/')[-1].split('.')[0]
                entry['semantics'].append(pre + '/' + post)
            #endfor

            #get reflexes
            reflex_table = lex_page.find('table', summary = re.compile("Indo-European reflexes"))
            r_trs = reflex_table.find_all("tr")

            hold_lang_code = ''
            for r_tr in r_trs:
                r_tds = r_tr.find_all("td")

                #only want rows with 9 columns, that's the reflex data 
                if len(r_tds) != 9:
                    continue
                #endif

                reflex = {}

                if r_tds[0].has_attr('id'):
                    reflex['language'] = r_tds[0]['id']
                    hold_lang_code = r_tds[0]['id']
                else:
                    reflex['language'] = hold_lang_code
                #endif

                temp_reflex = unicode(r_tds[2])
                temp_reflex = temp_reflex.replace('<td>','')
                temp_reflex = temp_reflex.replace('</td>','')
                reflex['reflex'] = temp_reflex
                
                #reflex['html_language_code'] = r_tds[2].find('span')['lang']
                reflex['part_of_speech'] = r_tds[4].text
                reflex['gloss'] = r_tds[6].text
                reflex['source'] = r_tds[8].text
                entry['reflexes'].append(reflex)
            #endfor

            #get parts of speech
            pos_table = lex_page.find('table', summary = re.compile("part-of-speech and grammatical feature abbreviations"))
            pos_trs = pos_table.find_all("tr")
            for pos_tr in pos_trs:
                pos_tds = pos_tr.find_all("td")
                if len(pos_tds) != 3:
                    continue
                #endif
                abbr = pos_tds[0].text
                if abbr not in parts_of_speech:
                    parts_of_speech[abbr] = pos_tds[2].text
                #endif
            #endfor


            #get sources
            source_table = lex_page.find('table', summary = re.compile("information source codes"))
            source_trs = source_table.find_all("tr")
            for source_tr in source_trs:
                source_tds = source_tr.find_all("td")
                if len(source_tds) != 3:
                    continue
                #endif
                abbr = source_tds[0].text
                if abbr not in sources:
                    sources[abbr] = source_tds[2].text
                #endif
            #endfor
                    
        #endif
    #endfor
    print '-------------'

    entries.append(entry)

    if ctr >= 10:
        break
    #endif
    
#endfor trs

#print entries
print '==================================================='
#print parts_of_speech
print '==================================================='
#print sources
