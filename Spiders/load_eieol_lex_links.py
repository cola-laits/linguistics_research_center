from  bs4 import BeautifulSoup
import urllib, urllib2, cookielib
import re
import json

import sys 
 
reload(sys)
sys.setdefaultencoding('UTF8')

to_load = {}
to_load[1] = 'http://www.utexas.edu/cola/centers/lrc/eieol/latol-BF-X.html'
to_load[2] = 'http://www.utexas.edu/cola/centers/lrc/eieol/grkol-BF-X.html'
to_load[3] = 'http://www.utexas.edu/cola/centers/lrc/eieol/tokol-BF-X.html'
to_load[4] = 'http://www.utexas.edu/cola/centers/lrc/eieol/txbol-BF-X.html'
to_load[6] = 'http://www.utexas.edu/cola/centers/lrc/eieol/albol-BF-X.html'
to_load[7] = 'http://www.utexas.edu/cola/centers/lrc/eieol/vedol-BF-X.html'
to_load[8] = 'http://www.utexas.edu/cola/centers/lrc/eieol/litol-BF-X.html'
to_load[9] = 'http://www.utexas.edu/cola/centers/lrc/eieol/armol-BF-X.html'
to_load[10] = 'http://www.utexas.edu/cola/centers/lrc/eieol/gotol-BF-X.html'
to_load[11] = 'http://www.utexas.edu/cola/centers/lrc/eieol/hitol-BF-X.html'
to_load[12] = 'http://www.utexas.edu/cola/centers/lrc/eieol/ntgol-BF-X.html'
to_load[13] = 'http://www.utexas.edu/cola/centers/lrc/eieol/ocsol-BF-X.html'
to_load[14] = 'http://www.utexas.edu/cola/centers/lrc/eieol/engol-BF-X.html'
to_load[15] = 'http://www.utexas.edu/cola/centers/lrc/eieol/ofrol-BF-X.html'
to_load[16] = 'http://www.utexas.edu/cola/centers/lrc/eieol/aveol-BF-X.html'
to_load[17] = 'http://www.utexas.edu/cola/centers/lrc/eieol/iriol-BF-X.html'
to_load[18] = 'http://www.utexas.edu/cola/centers/lrc/eieol/norol-BF-X.html'
to_load[19] = 'http://www.utexas.edu/cola/centers/lrc/eieol/oruol-BF-X.html'
to_load[20] = 'http://www.utexas.edu/cola/centers/lrc/eieol/lavol-BF-X.html'
to_load[21] = 'http://www.utexas.edu/cola/centers/lrc/eieol/gegol-BF-X.html'
to_load[22] = 'http://www.utexas.edu/cola/centers/lrc/eieol/opeol-BF-X.html'
to_load[24] = 'http://www.utexas.edu/cola/centers/lrc/eieol/armol-BF.html'

recs = []

for language_id, url in to_load.iteritems():
    print url
    html = urllib2.urlopen(url).read()
    html = html.split('<!-- open div for main content section -->')[1]
    html = html.split('<!-- begin Standard Footer for new CoLA-style design with provision for NavBar -->')[0]
    content = BeautifulSoup(html)
    
    for dt in content.find_all('dt'):
        if 'Pokorny' not in dt.text:
            continue
        #endif
        #print dt
        newrec = {}
        newrec['language_id'] = language_id
        newrec['word'] =  dt.find('span').renderContents()
        newrec['definition'] = dt.text.split(u'\xa0')[1].strip() #definition is surrounded by nbsp, which are \xa0
        newrec['old_etyma_id'] = dt.find('a')['href'].split('#P')[1]
        recs.append(newrec)
    #end for dt
    
    #break #for testing
    
#endfor language

with open('../server/app/storage/data_load/eieol_lex_links.json','w') as outfile:
            json.dump(recs,outfile)
            
print 'done'