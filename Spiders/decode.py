dict = {}
dict['1'] = '\xf6'
dict['2'] = '\u0101\u0306bel-, \u0101\u0306b\u014d\u0306l-, abel-'
dict['3'] = '\u0101'
print dict
print dict['1'].decode('unicode-escape')
dict['4'] = dict['2'].decode('unicode-escape')
print dict
print dict['3'].decode('unicode-escape')

