window.onload = function () {

Vue.component('basic-select', VueSearchSelect.BasicSelect);

var vooo = new Vue({

delimiters: ['{#', '#}'],

el: '#related_languages',

data: {
        
    id: 1,
    languages:[],
    language_options: [],
    language_selected: {value:'',text:''},
    
},

created() {
    
  this.fetchlanguageOptions();

},

methods: {
    
    fetchlanguageOptions() {
        
        const self = this;
        axios.get('/admin2/all_languages').then(function(response){
            
            self.language_options = response.data

        }).catch(function(error){console.log(error);});
 
    },
    
    onSelectLanguage(item) {

        this.language_selected = item;

    },
    
    addLanguage(l) {
        /*
        language = p.value;
        
        const self = this;
        axios.post('/api/order/' + self.id + '/attach/' + language.id).then(function(response){
            
            self.languages.push(language);
            self.language_selected = {value:'',text:''};
            self.fetchlanguageOptions();
        
        }).catch(function(error){console.log(error);});
        */
        
        this.languages.push(l);
        this.language_selected = {value:'',text:''};
        
    },
    
    removeLanguage(l) {
        
        /*
        const self = this;
        axios.post('/api/order/' + self.id + '/detach/' + p.id).then(function(response){
            
            removeByAttr(self.languages, 'id', p.id);
            self.fetchlanguageOptions();
        
        }).catch(function(error){console.log(error);});
        */
        
        removeByAttr(this.languages, 'value', l.value);
    
    },
    
}


});


}
