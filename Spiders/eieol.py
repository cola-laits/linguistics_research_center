from  bs4 import BeautifulSoup
import urllib, urllib2, cookielib
import re
import json

ctr = 0

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

    for li in ul.find_all('li'):
        lessons = []
        ctr+= 1
        text = li.text.split('(')
        
        series_name = text[0].strip() 
        print 'starting ' + series_name

        #go to the table of contents page
        page_name = li.find('a')['href'].split('-')[0]
        anchor_name = page_name[0:3].capitalize()
        toc_path = 'http://www.utexas.edu/cola/centers/lrc/eieol/' + page_name + '-TC-X.html'
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
            lesson['title'] = unicode(temp_text[period_pos + 2:])
 
            #open lesson page
            lesson_path = 'http://www.utexas.edu/cola/centers/lrc/eieol/' + lesson_tag['href']
            print lesson_path
            lesson_html = urllib2.urlopen(lesson_path).read()
            lesson_html = lesson_html.split('<!-- open div for main content section -->')[1]
            lesson_html = lesson_html.split('<!-- begin Standard Footer for new CoLA-style design with provision for NavBar -->')[0]

            #split the page at lesson text
            splits = lesson_html.split('<h4>Lesson Text</h4>')
            if len(splits) > 1:
                
                lesson_intro = splits[0]
                
                #strip off series title
                lesson_intro = lesson_intro.split('</h1>')[1].strip()
                
                #get intro text
                #first find where the analysis starts
                h4_pos = lesson_intro.find('<h4>Reading and Textual Analysis</h4>')
                #then get first ul, which would be the list of glosses
                ul_pos = lesson_intro.find('<ul>', h4_pos)
                #the first gloss should be the <p tag right before the gloss
                gloss_pos = lesson_intro.rfind('<p',h4_pos,ul_pos)
                
                lesson['lesson_intro'] = lesson_intro[0:gloss_pos]
                lesson['lesson_intro'] = lesson['lesson_intro'].replace('\r',' ')
                lesson['lesson_intro'] = lesson['lesson_intro'].replace('\n',' ')
                lesson['lesson_intro'] = lesson['lesson_intro'].replace('  ',' ')

                #everything between translation and the next h4 is the translation
                temp_trans = splits[1].split('<h4>Translation</h4>')[1]
                temp_trans = temp_trans.split('<h4>')[0]
                
                #take out \r and \n
                temp_trans = temp_trans.replace('\r',' ')
                temp_trans = temp_trans.replace('\n',' ')
                temp_trans = temp_trans.replace('  ',' ')
                lesson['lesson_translation'] = temp_trans
# 
# 
#                 #get grammar lessons
#                 #start with translation
#                 temp_grammar = splits[1].split('<h4>Translation</h4>')[1]
#                 #the next section might be called grammar or references.  Just jump to after header
#                 temp_grammar = temp_grammar.split('</h4>')[1]
# 
#                 lesson['grammars'] = []
#                 more = True
#                 next_tag = 'h5'
#                 grammar_ctr = 0
#                 while more:
#                     grammar_ctr += 1
#                     grammar = {}
#                     grammar['order'] = grammar_ctr
#                     temp_grammar_page=BeautifulSoup(temp_grammar)
#                     grammar['title'] = unicode(temp_grammar_page.find(next_tag).text)
#                     print '      ', grammar['title']
# 
#                     #the grammar text start after the h5 or h6 tag and goes to the next anchor that
#                     #contains the lesson name
#                     temp_grammar_lesson = temp_grammar.split('</' + next_tag + '>')[1]
#                     temp_grammar_lesson = temp_grammar_lesson.split(str("<a name='" + anchor_name))[0]
#                     grammar['text'] = temp_grammar_lesson.strip()
#                     lesson['grammars'].append(grammar)
#                     #print grammar
# 
#                     #trim off this lesson
#                     pos = temp_grammar.find('</' + next_tag)
#                     temp_grammar = temp_grammar[pos+5:]
# 
#                     pos = temp_grammar.find(str("<a name='" + anchor_name))
#                     if pos == -1:
#                         more = False
#                     else:
#                         temp_grammar = temp_grammar[pos:]
#                         h5_pos  = temp_grammar.find('<h5>')
#                         h6_pos  = temp_grammar.find('<h6>')
#                         if h5_pos == -1:
#                             next_tag = 'h6'
#                         elif h6_pos == -1:
#                             next_tag = 'h5'
#                         elif h5_pos < h6_pos:
#                             next_tag = 'h5'
#                         else:
#                             next_tag = 'h6'
#                         #endif
#                     #endif
#                 #endwhile more grammar
#                 
            else:
                #if the page is the intro or appendix, everything will be in split 0 and go in the intro text
                lesson_intro = splits[0]
                #strip off series title
                lesson_intro = lesson_intro.split('</h1>')[1].strip()

                #remove links to lessons and options
                if 'Lessons</h5>' in lesson_intro:
                    lesson['lesson_intro'] = lesson_intro.split('Lessons</h5>')[0]
                    lesson['lesson_intro'] = lesson['lesson_intro'].rsplit('<h5>',1)[0]
                else:
                    lesson['lesson_intro'] = lesson_intro
                #endif
                
                #remove links to lessons and options
                if 'Lessons</h6>' in lesson_intro:
                    lesson['lesson_intro'] = lesson_intro.split('Lessons</h6>')[0]
                    lesson['lesson_intro'] = lesson['lesson_intro'].rsplit('<h6>',1)[0]
                else:
                    lesson['lesson_intro'] = lesson_intro
                #endif
                
                #get bottom of page, only on intros, not appendix
                if '<h4>Related Language Courses at UT</h4>' in lesson_intro:
                    lesson_intro_2 = lesson_intro.split('<h4>Related Language Courses at UT</h4>') [1]
                    lesson_intro_2 = '<h4>Related Language Courses at UT</h4>' + lesson_intro_2
                    lesson['lesson_intro'] += ' ' + lesson_intro_2
                #endif
                
                lesson['lesson_intro'] = lesson['lesson_intro'].replace('\r',' ')
                lesson['lesson_intro'] = lesson['lesson_intro'].replace('\n',' ')
                lesson['lesson_intro'] = lesson['lesson_intro'].replace('  ',' ')
                lesson['lesson_translation'] = ''
            #endif
 
#             for k,v in lesson.iteritems():
#                 print str(k) + ': ' + str(v)
#             #endfor
#             print '--------------------'
            lessons.append(lesson)
 
        #endfor
        
        #write to file
        print 'writing ' + series_name
        with open('output/'+series_name+'.json','w') as outfile:
            json.dump(lessons,outfile)
        print '---------------------------'
    #end for language li

#end for ul

print 'done'