from  bs4 import BeautifulSoup
import urllib, urllib2, cookielib
import re
import json

import sys 
 
reload(sys)
sys.setdefaultencoding('UTF8')

path = "http://www.utexas.edu/cola/centers/lrc/eieol/"
html = urllib2.urlopen(path).read()
#print html

page = BeautifulSoup(html)
#print page

uls = page.find_all('ul');
for ul in uls:
    #we want the menu in the middle of the page
    if ul.find('li').text != 'Latin Online (precomposed Roman letters with diacritics)':
        continue
    #endif

    to_load = {}
    for li in ul.find_all('li'):
        
        text = li.text.split('(')
        series_name = text[0].strip() 
        
        to_load[series_name] = {}
        to_load[series_name]['intro_path'] = 'http://www.utexas.edu/cola/centers/lrc/eieol/' + li.find('a')['href']
    #endfor
    
    to_load['Classical Armenian Online - Romanized'] = {}
    to_load['Classical Armenian Online - Romanized']['intro_path'] = 'http://www.utexas.edu/cola/centers/lrc/eieol/armol-0.html'

    
    for series_name, series_parms in to_load.iteritems():
        #
        #
        #
        #
        #if series_name != 'Latin Online':
        #if series_name != 'Ancient Sanskrit Online':
        #if series_name != 'Old Norse Online':
        #if series_name != 'Old English Online':
        #if series_name != 'Old Church Slavonic Online':
        #if series_name != 'Albanian Online':
        #    continue
        #endif
        #
        #
        #
        #
        
        print 'starting ' + series_name
        
        #go to the intro page
        intro_soup = BeautifulSoup(urllib2.urlopen(series_parms['intro_path']).read())
        
        #most langs have a meanings link
        meaning_link = ''
        menus = intro_soup.findAll('ul', class_='menu')
        for menu in menus:
            if menu.li.text == 'LessonResources':
                for link in menu.findAll('a', href=True, target='EI'):
                    index_name = str(link['href']).split('.')[0]
                    print index_name
                    
                    keywords = []
                    
                    #go to the index page
                    index = BeautifulSoup(urllib2.urlopen('http://www.utexas.edu/cola/centers/lrc/eieol/' + link['href']).read())
        
                    for dt in index.dl.findAll('dt'):
                        keyword = {}
                        print dt
                        definition = unicode(dt).split('&gt;')[1]
                        definition = definition.split('--')[0].strip()

                        keyword['keyword'] = dt.b.text.split(':')[0].strip()
                        keyword['head_word'] = '<' + dt.span.renderContents() + '>'
                        keyword['definition'] = definition
                        keywords.append(keyword)
                    #endfor                    
        
                    #write to file
                    print 'writing ' + series_name
                    with open('../server/app/storage/data_load/indexes/'+index_name+'.json','w') as outfile:
                        json.dump(keywords,outfile)
                    print '---------------------------'
                
                #end for link     
        
            #endif
    #end for series

#end for ul

print 'done'