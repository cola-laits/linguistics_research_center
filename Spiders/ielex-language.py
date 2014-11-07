from bs4 import BeautifulSoup
import urllib, urllib2, cookielib
import re

families = []
ctr = 0
sub_family_ctr = 0

path = "http://www.utexas.edu/cola/centers/lrc/ielex/IELangTable.html"
html = urllib2.urlopen(path).read()
#print html

page = BeautifulSoup(html)
#print page

trs = page.find("table").find_all("tr")
#print trs

for tr in trs:
    tds = tr.find_all("td")

    #skip blanks
    if tds[0].text == '':
        continue
    #endif

    #families and subfamilies have only one column
    if len(tds) == 1:

        #families don't have leading spaces, so are equal to their stripped version
        if tds[0].text == tds[0].text.strip():
            ctr +=1
            
            #for each new family, add previous one to array
            if ctr > 1:
                families.append(family)
                sub_family_ctr = 0
            #endif
            family = {}
            family['name'] = tds[0].text.strip()
            family['order'] = ctr
            family['subfamilies'] = []
        
        else: #subfamily
            sub_family_ctr +=1
            sub_family = {}
            sub_family['name'] = tds[0].text.strip().replace(u'\xa0', u' ')
            sub_family['order'] = sub_family_ctr
            sub_family['languages'] = []
            family['subfamilies'].append(sub_family)
        #endif
    else:
        language = {}
        language['abbr'] = tds[0].text.strip().replace('.','')
        language['name'] = tds[2].text
        language['aka'] = tds[4].text.strip()
        sub_family['languages'].append(language)
    #endif
        

#    category = {}
#    category['text'] = li.text
#    category['number'] = ctr
#    category['abbr'] = ''
#    category['fields'] = []
#    
#    fields_html = urllib2.urlopen(li.find("a")['href']).read()
#    fields_page = BeautifulSoup(fields_html)
#    fields_uls=fields_page.find_all("ul")
#    fields_lis = fields_uls[1].find_all("li")
#    for field_li in fields_lis:
#        field = {}
#        parts = field_li.text.split(".")
#        field['number'] = parts[0] + '.' + parts[1]
#        field['text'] = parts[2].strip()
#        field['abbr'] = field_li['id']
#        category['fields'].append(field)
#
#        #we get the category abbreviation from the front of the field abbr
#        if category['abbr'] == '':
#            category['abbr'] = field_li['id'].split("_")[0]
#        #endif
#    #endfor
#    
#
#    if ctr >= 1:
#        break
#    #endif

#endfor tr

#get last one
families.append(family) 
print families


