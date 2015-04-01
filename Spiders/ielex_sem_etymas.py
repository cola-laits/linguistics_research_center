from bs4 import BeautifulSoup
import urllib, urllib2, cookielib
import re
import json

sem_etymas = []
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
   
    fields_html = urllib2.urlopen(li.find("a")['href']).read()
    fields_page = BeautifulSoup(fields_html)
    fields_uls=fields_page.find_all("ul")
    fields_lis = fields_uls[1].find_all("li")
    for field_li in fields_lis:
        links = field_li.find_all("a")
        for link in links:
            sem = link['href'].split('/')[-1].split('.')[0]
            print sem
            field_html = urllib2.urlopen(link['href']).read()
            field_page = BeautifulSoup(field_html)
            etyma_uls=field_page.find_all("ul")
            etyma_lis = etyma_uls[1].find_all("li")
            for etyma_li in etyma_lis:
                elinks = etyma_li.find_all("a")
                skip = False
                for elink in elinks:
                    if 'reflex' in elink['title']:
                        skip = True
                        break
                    #endif reflex link
                    if 'Unicode 3' not in elink['title']:
                        continue
                    #endif reflex link
                    etyma = elink['href'].split('#P')[1]
                #endfor etyma link
                if skip == False:
                    print '   ' + etyma
                    sem_etyma = {}
                    sem_etyma['etyma'] = etyma
                    sem_etyma['sem'] = sem
                    sem_etymas.append(sem_etyma)
                #end if not skipping
            #endfor etyma lis
        #endfor links
        
#         ctr +=1
#         if ctr >= 10:
#             break
#         #endif

    #endfor field lis
    
#endfor ul
#print sem_etymas

with open('../server/app/storage/data_load/lex_sem_etymas.json','w') as outfile:
            json.dump(sem_etymas,outfile)
            
print 'done'