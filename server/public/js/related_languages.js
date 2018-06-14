window.onload = function () {

  Vue.component('basic-select', VueSearchSelect.BasicSelect);

  var related_languages = new Vue({
    
    el: '#related_languages',
    
    data: {
        
        id: seriesId,
        languages:[],
        dropdown_options: [],
        dropdown_selected: {value:'',text:''},
    
    },
    
    created() {
  
      this.fetchlanguages();
      this.fetchdropdownOptions();
      
    },
    
    methods: {
        
        fetchdropdownOptions() {
        
            const self = this;
            axios.get('/admin2/related_languages/all_languages').then(function(response){
            
                self.dropdown_options = response.data

            }).catch(function(error){console.log(error);});
 
        },
    
        selectLanguage(item) {

            this.dropdown_selected = item;

        },
      
        addLanguage() {
        
            if (this.dropdown_selected.value != '') {
        
              var postData = {
                'id':this.id,
                'lang':this.dropdown_selected.value,
                'display':this.dropdown_selected.text
              };
        
              const self = this;
              axios.post('/admin2/related_languages/attach_language', postData).then(function(response){
                          
                  self.dropdown_selected = {value:'',text:''};
                  self.fetchlanguages();
        
              }).catch(function(error){console.log(error);});
        
            }
        
        },
    
        removeLanguage(l) {

            const self = this;
            axios.post('/admin2/related_languages/' + self.id + '/detach_language/' + l.value).then(function(response){
                        
                self.fetchlanguages();
        
            }).catch(function(error){console.log(error);});

        },
        
        fetchlanguages() {
        
            const self = this;
            axios.get('/admin2/related_languages/attached_languages/' + self.id).then(function(response){
            
                self.languages = response.data

            }).catch(function(error){console.log(error);});
 
        },
    
    },
    
    delimiters: ['{#', '#}'],

  });

}
