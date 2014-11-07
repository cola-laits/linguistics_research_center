from bs4 import BeautifulSoup
import urllib, urllib2, cookielib
import re

categories = []
ctr = 0

path = "http://www.utexas.edu/cola/centers/lrc/iedocctr/ie-ling/ie-sem/index.html"
html = urllib2.urlopen(path).read()
#print html

page = BeautifulSoup(html)
#print page
ol = page.find("ol")
#print ol

lis = ol.find_all("li")
for li in lis:
    ctr +=1
    category = {}
    category['text'] = li.text
    category['number'] = ctr
    category['abbr'] = ''
    category['fields'] = []
    
    fields_html = urllib2.urlopen(li.find("a")['href']).read()
    fields_page = BeautifulSoup(fields_html)
    fields_uls=fields_page.find_all("ul")
    fields_lis = fields_uls[1].find_all("li")
    for field_li in fields_lis:
        field = {}
        parts = field_li.text.split(".")
        field['number'] = parts[0] + '.' + parts[1]
        field['text'] = parts[2].strip()
        field['abbr'] = field_li['id']
        category['fields'].append(field)

        #we get the category abbreviation from the front of the field abbr
        if category['abbr'] == '':
            category['abbr'] = field_li['id'].split("_")[0]
        #endif
    #endfor
    
    categories.append(category)

    if ctr >= 1:
        break
    #endif
    
#endfor ul
print categories


