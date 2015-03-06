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

uls = page.find_all('ul')
for ul in uls:
    #we want the menu in the middle of the page
    if ul.find('li').text != 'Latin Online (precomposed Roman letters with diacritics)':
        continue
    #endif

    to_load = {}
    for li in ul.find_all('li'):
        
        text = li.text.split('(')
        series_name = text[0].strip() 

        #get toc page
        page_name = li.find('a')['href'].split('-')[0]
        anchor_name = page_name[0:3].capitalize()
        toc_path = 'http://www.utexas.edu/cola/centers/lrc/eieol/' + page_name + '-TC-X.html'
        
        to_load[series_name] = {}
        to_load[series_name]['toc_path'] = toc_path
        to_load[series_name]['anchor_name'] = anchor_name
    #endfor
    
    to_load['Classical Armenian Online - Romanized'] = {}
    to_load['Classical Armenian Online - Romanized']['toc_path'] = 'http://www.utexas.edu/cola/centers/lrc/eieol/armol-TC.html'
    to_load['Classical Armenian Online - Romanized']['anchor_name'] = 'Arm'
    
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
        toc_path = series_parms['toc_path']
        anchor_name = series_parms['anchor_name']
        lessons = []
        
        #go to the table of contents page
        toc_html = urllib2.urlopen(toc_path).read()
 
        #get just the part of the page with toc
        lessons_toc_html = unicode(toc_html).split('<h5>Lessons</h5>')[1]
        lessons_toc_html = lessons_toc_html.split('<h5>Grammar Points</h5>')[0]
        lessons_toc = BeautifulSoup(lessons_toc_html)
 
        #each item in toc is a lesson
        for lesson_tag in lessons_toc.find_all('a'):
            lesson = {}
 
            #open lesson page
            lesson_path = 'http://www.utexas.edu/cola/centers/lrc/eieol/' + lesson_tag['href']
            print lesson_path
            lesson_html = urllib2.urlopen(lesson_path).read()
            lesson_content = lesson_html.split('<!-- open div for main content section -->')[1]
            lesson_content = lesson_content.split('<!-- begin Standard Footer for new CoLA-style design with provision for NavBar -->')[0]

            #split the page at lesson text
            splits = lesson_content.split('<h4>Lesson Text</h4>')
            if len(splits) > 1:
                
                intro_text = splits[0]
                
                #strip off series title
                intro_text = intro_text.split('</h1>')[1].strip()
                
                #get intro text
                #first find where the analysis starts
                intro_text = intro_text.replace('<h4>Reading and Textual Analyis</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 1 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 2 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 3 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 4 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 5 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 6 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 7 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 8 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 9 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                intro_text = intro_text.replace('<h4>Lesson 10 Text</h4>','<h4>Reading and Textual Analysis</h4>')
                h4_pos = intro_text.find('<h4>Reading and Textual Analysis</h4>')
                #the first list of glosses should be a combination of ul, li and a tags
                ul_pos = intro_text.find('<ul>\r<li><a', h4_pos)
                if ul_pos == -1:
                    ul_pos = intro_text.find('<ul>\r\n<li><a', h4_pos)
                
                #the first gloss should be the <p tag right before the gloss
                gloss_pos = intro_text.rfind('<p',h4_pos,ul_pos)
                #print intro_text
                #print h4_pos, ul_pos, gloss_pos
                

                glossy = BeautifulSoup(intro_text[gloss_pos:])
                #print glossy
                glossed_text_ctr = 0
                for p in glossy.find_all('p'):
                    glossed_text_ctr += 1
                    #take out span classes, we'll add them when we display
                    for match in p.findAll('span'):
                        match.replaceWithChildren()
                    #print p.renderContents()

                    gloss_ctr = 0
                    ul = p.findNext('ul')
                    for li in ul.find_all('li'):
                        gloss_ctr += 1
                        #print li
                        if li.find("tt"):
                            surface_form = li.find("tt").renderContents()
                        else:
                            surface_form = li.find("span").renderContents()
                        #endif
                        element_ctr = 0
                        middle = li.renderContents().split('<nobr>--</nobr>')[1]
                        elements = middle.split(' +')
                        for element in elements:
                            element_ctr += 1
                            first_part = element.split('<nobr>')[0]
                            if first_part.find(';') == -1:
                                print surface_form, first_part
                        #endfor element
                        
                    #endfor li
                
                #end for p

            #endif

 
        #endfor
        
        print '---------------------------'
    #end for language

#end for ul

print 'done'