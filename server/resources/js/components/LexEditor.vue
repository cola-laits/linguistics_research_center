<template>
    <div>
        <form>
            <div class="container">
                <input type="hidden" name="id" :value="item.id">
                <div v-for="field in fields">
                    <div class="row" v-if="field.notes">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">{{field.notes}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2" style="display:flex;justify-content:flex-end;"><h4>{{field.label}}</h4></div>
                        <div class="col-md-10">
                            <div v-if="field.type=='text'">
                                <input type="text" style="width:75%" :name="field.name" v-model="item[field.name]">
                            </div>
                            <div v-if="field.type=='relation'">
                                <div v-if="typeof(lookups[field.relation])=='undefined' || lookups[field.relation].length==0">
                                    Please wait...
                                </div>
                                <div v-else>
                                    <select :name="field.name" v-model="item[field.name]">
                                        <option v-for="lookup in lookups[field.relation]" :value="lookup.id">{{field.view_fn(lookup)}}</option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="field.type=='info'">
                                {{field.view_fn(item)}}
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                <button type="button" @click="cancel_change()" class="btn btn-default">Cancel</button>
                <button type="button" @click="edit_change()" class="btn btn-primary">Save</button>
                <button type="button" @click="delete_change()" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['id','route_name','fields'],
        data: function() { return {
            lookups: {},
            item: {}
        }},
        methods: {
            cancel_change: function() {
                this.$router.push('/'+this.route_name);
            },
            edit_change: function() {
                var comp = this;
                axios.post('/admin2/lexicon/api/action/edit?type='+this.route_name+'&id='+this.id,{
                    item:comp.item
                }).then(function (response) {
                    alert("Edits successful!");
                    comp.$router.push('/'+comp.route_name);
                }).catch(function(response) {
                    alert("ERROR: Unable to save.  Please try again or contact a developer.");
                });
            },
            delete_change: function() {
                if (!confirm("Are you sure you want to delete this?")) {
                    return;
                }
                var comp = this;
                axios.post('/admin2/lexicon/api/action/delete?type='+this.route_name+'&id='+this.id,{
                    item:comp.item
                }).then(function (response) {
                    alert("Delete successful!");
                    comp.$router.push('/'+comp.route_name);
                }).catch(function(response) {
                    alert("ERROR: Unable to delete.  Please try again or contact a developer.");
                });
            }
        },
        mounted: function() {
            var comp = this;
            axios.get('/admin2/lexicon/api/action/get?type='+this.route_name+'&id='+this.id)
                .then(function(response) {
                    comp.item = response.data.item;
                }).catch(function(response) {
                alert("ERROR: Unable to load.  Please try again or contact a developer.");
            });
            this.fields.forEach(function (f) {
                if (f.type==='relation') {
                    axios.get('/admin2/lexicon/api/'+f.relation+"?skip_pagination=true")
                        .then(function(response) {
                            Vue.set(comp.lookups, f.relation, response.data.data);
                        }).catch(function(response) {
                        alert("ERROR: Unable to load.  Please try again or contact a developer.");
                    });
                }
            });
        }
    }
</script>
