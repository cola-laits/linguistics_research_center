window.onload = function () {

Vue.component('basic-select', VueSearchSelect.BasicSelect);

var vooo = new Vue({

delimiters: ['{#', '#}'],

el: '#related_languages',

data: {
        
    id: seriesId,
    languages:[],
    language_options: [],
    language_selected: {value:'',text:''},
    
},

created() {
  
  this.fetchlanguages();
  this.fetchlanguageOptions();

},

methods: {
    
    fetchlanguageOptions() {
        
        const self = this;
        axios.get('/admin2/eieol_series/all_languages').then(function(response){
            
            self.language_options = response.data

        }).catch(function(error){console.log(error);});
 
    },
    
    
    fetchlanguages() {
        
        const self = this;
        axios.get('/admin2/eieol_series/attached_languages/' + self.id).then(function(response){
            
            self.languages = response.data

        }).catch(function(error){console.log(error);});
 
    },
    
    
    onSelectLanguage(item) {

        this.language_selected = item;

    },
    
    addLanguage() {
        
        if (this.language_selected.value != '') {
        
          var postData = {
            'id':this.id,
            'lang':this.language_selected.value,
            'display':this.language_selected.text
          };
        
          const self = this;
          axios.post('/admin2/eieol_series/attach_language', postData).then(function(response){
                          
              self.language_selected = {value:'',text:''};
              self.fetchlanguages();
        
          }).catch(function(error){console.log(error);});
        
        }
        
    },
    
    removeLanguage(l) {

        const self = this;
        axios.post('/admin2/eieol_series/' + self.id + '/detach_language/' + l.value).then(function(response){
                        
            self.fetchlanguages();
        
        }).catch(function(error){console.log(error);});

    },
    
}


});


}
