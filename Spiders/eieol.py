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
        print 'starting ' + series_name
        toc_path = series_parms['toc_path']
        anchor_name = series_parms['anchor_name']
        lessons = []
        
        #go to the table of contents page
        toc_html = urllib2.urlopen(toc_path).read()
 
        #get just the part of the page with toc
        lessons_toc_html = toc_html.split('<h5>Lessons</h5>')[1]
        lessons_toc_html = lessons_toc_html.split('<h5>Grammar Points</h5>')[0]
        lessons_toc = BeautifulSoup(lessons_toc_html)
 
        #each item in toc is a lesson
        for lesson_tag in lessons_toc.find_all('a'):
            lesson = {}
 
            #convert tag to unicode and remove a tags
            temp_text = unicode(lesson_tag)
            temp_pos = temp_text.find('>') + 1
            temp_text = temp_text[temp_pos:]
            temp_text = temp_text.replace('</a>','')

            #first part is the order number
            lesson['order'] = int(temp_text.split('.')[0])
             
            #next part is title
            period_pos = temp_text.find('.')
            lesson['title'] = temp_text[period_pos + 2:]
 
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
                intro_text = intro_text
                
                #get intro text
                #first find where the analysis starts
                h4_pos = intro_text.find('<h4>Reading and Textual Analysis</h4>')
                #then get first ul, which would be the list of glosses
                ul_pos = intro_text.find('<ul>', h4_pos)
                #the first gloss should be the <p tag right before the gloss
                gloss_pos = intro_text.rfind('<p',h4_pos,ul_pos)
                
                lesson['intro_text'] = intro_text[0:gloss_pos]
                lesson['intro_text'] = lesson['intro_text'].replace('\r',' ')
                lesson['intro_text'] = lesson['intro_text'].replace('\n',' ')
                lesson['intro_text'] = lesson['intro_text'].replace('\t',' ')
                lesson['intro_text'] = lesson['intro_text'].replace('  ',' ')

                #everything between translation and the next h4 is the translation
                temp_trans = splits[1].split('<h4>Translation</h4>')[1]
                temp_trans = temp_trans.split('<h4>')[0]
                
                #take out \r and \n
                temp_trans = temp_trans.replace('\r',' ')
                temp_trans = temp_trans.replace('\n',' ')
                temp_trans = temp_trans.replace('\t',' ')
                temp_trans = temp_trans.replace('  ',' ')
                lesson['lesson_translation'] = temp_trans

                #get grammar lessons
                #start with translation
                temp_grammar = splits[1].split('<h4>Translation</h4>')[1]
                #the next section might be called grammar or references.  Just jump to after header
                temp_grammar = temp_grammar.split('</h4>')[1]

                lesson['grammars'] = []
                more = True
                next_tag = 'h5'
                grammar_ctr = 0
                while more:
                    grammar_ctr += 1
                    grammar = {}
                    grammar['order'] = grammar_ctr
                    temp_grammar_page=BeautifulSoup(temp_grammar)
                    temp_title = temp_grammar_page.find(next_tag).text
                    temp_title = temp_title
                    
                    #fix bad data
                    if temp_title == "41. Questions":
                        temp_title = "41b. Questions"
                    if temp_title == "41.1. Yes/No and Rhetorical Questions":
                        temp_title = "41b.1. Yes/No and Rhetorical Questions"
                        
                    
                    grammar['section_number'] = temp_title.split(' ',1)[0]
                    grammar['section_number'] = grammar['section_number'][:-1] #strip last period
                    grammar['title'] = temp_title.split(' ',1)[1]
                    #print '      ', grammar['title']
                    #print '      ', grammar['section_number']
 
                    #the grammar text start after the h5 or h6 tag and goes to the next anchor that
                    #contains the lesson name
                    temp_grammar_lesson = temp_grammar.split('</' + next_tag + '>')[1]
                    temp_grammar_lesson = temp_grammar_lesson.split(str("<a name='" + anchor_name))[0]
                    
                    try:
                        grammar['grammar_text'] = unicode(temp_grammar_lesson.strip()) 
                    except UnicodeDecodeError:
                        print 'treating ' + grammar['title'] + ' as Latin 1'
                        grammar['grammar_text'] = temp_grammar_lesson.strip().decode('Latin-1')
                    #endif
                    
                    lesson['grammars'].append(grammar)
                    #print grammar

                    #trim off this lesson
                    pos = temp_grammar.find('</' + next_tag)
                    temp_grammar = temp_grammar[pos+5:]

                    pos = temp_grammar.find(str("<a name='" + anchor_name))
                    if pos == -1:
                        more = False
                    else:
                        temp_grammar = temp_grammar[pos:]
                        h5_pos  = temp_grammar.find('<h5>')
                        h6_pos  = temp_grammar.find('<h6>')
                        if h5_pos == -1:
                            next_tag = 'h6'
                        elif h6_pos == -1:
                            next_tag = 'h5'
                        elif h5_pos < h6_pos:
                            next_tag = 'h5'
                        else:
                            next_tag = 'h6'
                        #endif
                    #endif
                #endwhile more grammar
                 
                lessons.append(lesson)
            else:
                #if the page is the intro or appendix, everything will be in split 0 and go in the intro text
                intro_text = splits[0]
                #strip off series title
                intro_text = intro_text.split('</h1>')[1].strip()
                intor_text = intro_text

                #remove links to lessons and options
                if 'Lessons</h5>' in intro_text:
                    lesson['intro_text'] = intro_text.split('Lessons</h5>')[0]
                    lesson['intro_text'] = lesson['intro_text'].rsplit('<h5>',1)[0]
                elif 'Lessons</h6>' in intro_text:
                    lesson['intro_text'] = intro_text.split('Lessons</h6>')[0]
                    lesson['intro_text'] = lesson['intro_text'].rsplit('<h6>',1)[0]
                else:
                    lesson['intro_text'] = intro_text
                #endif
                
                
                
                #get bottom of page, only on intros, not appendix
                if '<h6>Brief Bibliography</h6>' in intro_text:
                    intro_text_2 = intro_text.split('<h6>Brief Bibliography</h6>') [1]
                    intro_text_2 = '<h6>Brief Bibliography</h6>' + intro_text_2
                    lesson['intro_text'] += ' ' + intro_text_2
                elif '<h4>Related Language Courses at UT</h4>' in intro_text:
                    intro_text_2 = intro_text.split('<h4>Related Language Courses at UT</h4>') [1]
                    intro_text_2 = '<h4>Related Language Courses at UT</h4>' + intro_text_2
                    lesson['intro_text'] += ' ' + intro_text_2
                #endif
                
                lesson['intro_text'] = lesson['intro_text'].replace('\r',' ')
                lesson['intro_text'] = lesson['intro_text'].replace('\n',' ')
                lesson['intro_text'] = lesson['intro_text'].replace('\t',' ')
                lesson['intro_text'] = lesson['intro_text'].replace('  ',' ')
                lesson['lesson_translation'] = ''
                
                lessons.append(lesson)
                
                #some series have a bibliography.  Load it as a lesson
                if "Series Introduction" in lesson_html and "<li><a title='Annotated Bibliography for" in lesson_html:
                    biblio_url = lesson_html.split("<li><a title='Annotated Bibliography for")[1]
                    biblio_url = biblio_url.split("href='")[1]
                    biblio_url = biblio_url.split("'>")[0]
                    print biblio_url
                    biblio_html = urllib2.urlopen('http://www.utexas.edu/cola/centers/lrc/eieol/' + biblio_url).read()
                    biblio_content = biblio_html.split('<!-- open div for main content section -->')[1]
                    biblio_content = biblio_content.split('</h1>')[1].strip()
                    biblio_content = biblio_content.split('<!-- begin Standard Footer for new CoLA-style design with provision for NavBar -->')[0]
                    lesson = {}
                    lesson['order'] = 20
                    lesson['title'] = 'Selected Annotated Bibliography'
                    #print biblio_content
                    try:
                        lesson['intro_text'] = unicode(biblio_content)
                    except UnicodeDecodeError:
                        print 'treating Biblio as Latin 1'
                        lesson['intro_text'] = biblio_content.decode('Latin-1')
                    #endif
                    lesson['intro_text'] = lesson['intro_text'].replace('\r',' ')
                    lesson['intro_text'] = lesson['intro_text'].replace('\n',' ')
                    lesson['intro_text'] = lesson['intro_text'].replace('\t',' ')
                    lesson['intro_text'] = lesson['intro_text'].replace('  ',' ')
                    lesson['lesson_translation'] = ''
                    lessons.append(lesson)
                #endif
            #endif
 
#             for k,v in lesson.iteritems():
#                 print str(k) + ': ' + str(v)
#             #endfor
#             print '--------------------'
            
 
        #endfor
        
        #write to file
        print 'writing ' + series_name
        with open('../server/app/storage/data_load/'+series_name+'.json','w') as outfile:
            json.dump(lessons,outfile)
        print '---------------------------'
    #end for language

#end for ul

print 'done'