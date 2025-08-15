<?php

namespace App\Filament\Resources\EieolLanguages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class EieolLanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('language')
                    ->default(null),
                TextInput::make('lang_attribute')
                    ->default(null)
                    ->helperText("This can be added to span tags in lessons that use this language - <span lang=\"xxx\">"),
                Textarea::make('custom_keyboard_layout')
                    ->default(null)
                    ->helperText(new HtmlString("This should be a list of characters (either in unicode code points or pasted in) <br> Example: 'Â','Ä','Å','\u042f', '\u03da', '\u03db', '\u03c0' "))
                    ->columnSpanFull(),
                Textarea::make('substitutions')
                    ->default(null)
                    ->helperText(new HtmlString("This is used for sorting. If there are characters that should be treated differently when sorting, enter them here.<br>Separate substituions by commas. Use x>y notation.<br>Example: If you enter Ѽ>Отъ, the every occurrence of Ѽ will be replaced with Отъ before sorting.<br>Do not use unicode code points, just paste in unicode characters."))
                    ->columnSpanFull(),
                Textarea::make('custom_sort')
                    ->default(null)
                    ->helperText(new HtmlString('This should be a comma separated list of characters in the order the Gloss and Dictionary should be sorted.<br>'.
                        'Do not use unicode code points, just paste in unicode characters.<br>'.
                        'Example: A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,π,q,r,s,t,u,v,w,x,y,z<br>'.
                        'If two characters should be considered equal, place an equal sign between them. <br>'.
                        'In the next example, "a" and "A" are considered the same, "b" and "B" are considered the same, etc. Also "p","P" and "π" are considered the same. Further, "L", "l" and "ll" are the same. In other words, "ll" is treated as a single character.<br>'.
                        'Example: a=A,b=B,c=C,d=D,e=E,f=F,g=G,h=H,i=I,J=j,K=k,L=l=ll,M=m,N=n,O=o,P=p=π,Q=q,R=r,S=s,T=t,U=u,V=v,W=w,X=x,Y=y,Z=z <br>'))
                    ->columnSpanFull(),
            ]);
    }
}
