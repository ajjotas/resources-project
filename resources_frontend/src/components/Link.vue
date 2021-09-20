<template>
  <div>
    <template v-if="editMode">
      <b-alert variant="success" :show="successMessage ? true : false">{{successMessage}}</b-alert> 
      <b-alert variant="danger" :show="errorMessage ? true : false">{{errorMessage}}</b-alert>   

      <b-card title="Add a new link">
        <b-form-group label="Link description">
          <b-form-input maxlength="50" v-model="linkDescription" trim></b-form-input>
        </b-form-group>
        <b-form-group label="URL" class="mt-2">
          <b-form-input maxlength="50" v-model="linkUrl" trim></b-form-input>
        </b-form-group>
        <b-form-checkbox
          v-model="linkNewTab"  
          class="mt-3"  
          value="1"
          unchecked-value="0"                            
          >
          Open in new tab
        </b-form-checkbox>                
        <b-button class="mt-3 float-right" squared variant="success" @click="add">Add link</b-button>
      </b-card>
    </template>

    <b-card class="mt-2" title="Added links">
      <b-table class="mb-1" responsive="lg" striped hover :items="links" :fields="linkTable.fields">
        <template #cell(id)="data">
          <b-icon-trash v-if="mode == 'edit'" class="float-right clickable" variant="danger" title="Delete link" @click="remove(data.item.id)"></b-icon-trash>
          <b-icon-pencil-square v-if="mode == 'edit'" class="float-right clickable" variant="primary" title="Edit link" @click="view(data.item)"></b-icon-pencil-square>
          <b-link :href="data.item.link" :target="parseInt(data.item.newTab) ? '_blank' : '_self'">
            <b-icon-link class="float-right clickable" variant="success" title="Follow link"></b-icon-link>
          </b-link>                    
        </template>                 
      </b-table>                  
    </b-card>  

    <b-modal id="modal-link" hide-footer>
      <template #modal-title>
        {{'Edit ' + modalLink.description}}
      </template>      
      <div class="d-block">
        <b-alert variant="success" :show="modalSuccessMessage ? true : false">{{modalSuccessMessage}}</b-alert> 
        <b-alert variant="danger" :show="modalErrorMessage ? true : false">{{modalErrorMessage}}</b-alert>  

        <b-form-group label="Link description">
          <b-form-input maxlength="50" v-model="modalLink.description" trim></b-form-input>
        </b-form-group>
        <b-form-group label="URL" class="mt-2">
          <b-form-input maxlength="50" v-model="modalLink.link" trim></b-form-input>
        </b-form-group>
        <b-form-checkbox
          v-model="modalLink.newTab"
          class="mt-3"       
          value="1"
          unchecked-value="0"                       
          >
          Open in new tab
        </b-form-checkbox> 
      </div>    
        <b-button class="mt-3 float-right" squared variant="success" @click="update">Update</b-button>                     
    </b-modal> 
  </div>
</template>

<script>
export default {
  name: 'Link',
  props: {
    mode: String,
    links: Array
  },

  data() {
    return {
      successMessage: null,
      errorMessage: null,    
      modalSuccessMessage: null,
      modalErrorMessage: null, 

      linkDescription: null,
      linkUrl: null,  
      linkNewTab: false,    
      modalLink: {},

      linkTable: {
        fields: [
          { key: "description", label: "Description" },
          { key: "link", label: "URL" },          
          { key: "id", label: "" }
        ],
      },   
    }
  },

  computed: {
    editMode() {
      return this.mode == "edit";
    },   
  },  

  methods: {
    add() {
      this.clearMessages();

      let validateFields = this.validateFields(this.linkDescription, this.linkUrl);
      if (!validateFields.success) {
        this.errorMessage = validateFields.message;
        return
      }

      this.$http.post('link/add', {description: this.linkDescription, link: this.linkUrl, newTab: this.linkNewTab})
        .then((response) => {
          this.$emit('link-changed');  
          this.clearFields();     
          this.successMessage = response.data.success; 
        })
        .catch((response) => {
          this.errorMessage = this.$h.makeErrorMessage(response.response.data);        
        });
    },

    update() {
      this.clearMessages();

      let validateFields = this.validateFields(this.modalLink.description, this.modalLink.link);
      if (!validateFields.success) {
        this.modalErrorMessage = validateFields.message;
        return
      }      

      this.$http.put('link/update/'+this.modalLink.id, {description: this.modalLink.description, link: this.modalLink.link, newTab: this.modalLink.newTab})
        .then((response) => {
          this.$emit('link-changed');  
          this.clearFields();     
          this.modalSuccessMessage = response.data.success; 
        })
        .catch((response) => {
          this.modalErrorMessage = this.$h.makeErrorMessage(response.response.data);          
        });
    }, 

    remove(id) {
      this.clearMessages();   

      this.$bvModal.msgBoxConfirm('Are you sure you want to remove this Link?')
        .then(value => {
          if (value) {
            this.$http.delete('link/delete/'+id)
              .then((response) => {
                this.$emit('link-changed');      
                this.successMessage = response.data.success; 
              })
              .catch((response) => {
                this.errorMessage = this.$h.makeErrorMessage(response.response.data);         
              });
          }
      })
    },   

    view(linkObject) {
      this.clearMessages();        
      this.modalLink = Object.assign({}, linkObject);
      this.$bvModal.show('modal-link');      
    },    

    validateFields(description, url) {
      if (!(description && url)) { 
        return {success: false, message: "Please fill in all the fields."};
      }
      return {success: true, message: ""};
    },
    
    clearFields() {
      this.linkDescription = null;
      this.linkUrl = null;
      this.linkNewTab = false;
    },

    clearMessages() {
      this.successMessage = null;
      this.errorMessage = null;
      this.modalSuccessMessage = null;
      this.modalErrorMessage = null;      
    },    
  }
}
</script>