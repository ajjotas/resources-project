<template>
  <div>
    <template v-if="editMode">
      <b-alert variant="success" :show="successMessage ? true : false">{{successMessage}}</b-alert> 
      <b-alert variant="danger" :show="errorMessage ? true : false">{{errorMessage}}</b-alert>   

      <b-card title="Add a new HTML snippet">
        <b-form-group label="HTML snippet title" label-for="html-title">
          <b-form-input maxlength="50" id="html-title" v-model="htmlTitle" trim></b-form-input>
        </b-form-group>        
        <b-form-group class="mt-3" label="HTML snippet description" label-for="html-description">
          <b-form-textarea
            id="html-description"
            v-model="htmlDescription"
            rows="3"
            maxlength="200"            
          ></b-form-textarea>          
        </b-form-group>
        <b-form-group class="mt-3" label="HTML snippet" label-for="html-snippet">
          <prism-editor
            class="my-editor" 
            v-model="htmlSnippet" 
            :highlight="highlighter" 
            line-numbers 
            placeholder="Write your HTML snippet here">
          </prism-editor>  
        </b-form-group>
        <b-button class="mt-3 float-right" squared variant="success" @click="add">Add HTML snippet</b-button>
      </b-card>
    </template>

    <b-card class="mt-2" title="Added HTML snippets">
      <b-table class="mb-1" responsive="lg" striped hover :items="htmlSnippets" :fields="htmlTable.fields">
        <template #cell(id)="data">
          <b-icon-trash v-if="editMode" class="float-right clickable" variant="danger" title="Delete snippet" @click="remove(data.item.id)"></b-icon-trash>
          <b-icon-eye v-if="!editMode" class="float-right clickable" title="View snippet" @click="viewSnippet(data.item)"></b-icon-eye>
          <b-icon-pencil-square v-if="editMode" class="float-right clickable" variant="primary" title="Edit snippet" @click="viewSnippet(data.item)"></b-icon-pencil-square>
        </template>                 
      </b-table>                  
    </b-card>  

    <b-modal id="modal-snippet" hide-footer>
      <template #modal-title>
        {{modalTitle}}
      </template>      
      <div class="d-block">
        <b-alert variant="success" :show="modalSuccessMessage ? true : false">{{modalSuccessMessage}}</b-alert> 
        <b-alert variant="danger" :show="modalErrorMessage ? true : false">{{modalErrorMessage}}</b-alert>     
        <b-form-group label="HTML snippet title" label-for="html-title">
          <b-form-input maxlength="50" id="modal-html-title" v-model="modalHtmlSnippet.title" trim :disabled="disabledFields"></b-form-input>
        </b-form-group>      
        <b-form-group class="mt-3" label="HTML snippet description" label-for="html-description">
          <b-form-textarea
            id="modal-html-description"
            v-model="modalHtmlSnippet.description"
            rows="3"
            maxlength="200"
            max-rows="3"
            :disabled="disabledFields"
          ></b-form-textarea> 
        </b-form-group>
        <b-form-group class="mt-3" label="HTML snippet" label-for="html-snippet">
          <prism-editor
            class="my-editor" 
            v-model="modalHtmlSnippet.snippet" 
            :highlight="highlighter" 
            line-numbers 
            placeholder="Write your HTML snippet here"
            :readonly="disabledFields">
          </prism-editor>                    
        </b-form-group>
      </div>    
      <div class="mt-3 float-right">
        <b-button squared variant="secondary" @click="copySnippet(modalHtmlSnippet.snippet)">Copy snippet to clipboard</b-button>              
        <b-button v-if="editMode" squared variant="success" @click="update">Update</b-button>                
      </div>  
    </b-modal>  
      </div>
</template>

<script>
  import { PrismEditor } from 'vue-prism-editor';
  import { highlight, languages } from 'prismjs/components/prism-core';
  import 'vue-prism-editor/dist/prismeditor.min.css'; 
  import 'prismjs/components/prism-markup';
  import 'prismjs/themes/prism-tomorrow.css'; 

export default {
  name: 'HTML',
  props: {
    mode: String,
    htmlSnippets: Array
  },
  components: {
    PrismEditor,
  },

  data() {
    return {
      successMessage: null,
      errorMessage: null,    
      modalSuccessMessage: null,
      modalErrorMessage: null,         

      htmlTitle: null,
      htmlDescription: null,
      htmlSnippet: null,
      modalHtmlSnippet: {},

      htmlTable: {
        fields: [
          { key: "title", label: "Title" },          
          { key: "id", label: "" }
        ],
      },   
    }
  },

  computed: {
    editMode() {
      return this.mode == "edit";
    },

    modalTitle() {
      if (Object.keys.length) {
        return this.editMode ? 'Edit ' + this.modalHtmlSnippet.title : 'View ' + this.modalHtmlSnippet.title;
      }
      return '';
    },

    disabledFields() {
      return !(this.mode == 'edit');
    },    
  },  

  methods: {
    add() {
      this.clearMessages();

      let validateFields = this.validateFields(this.htmlTitle, this.htmlDescription, this.htmlSnippet);
      if (!validateFields.success) {
        this.errorMessage = validateFields.message;
        return
      }      

      this.$http.post('html/add', {title: this.htmlTitle, description: this.htmlDescription, snippet: this.htmlSnippet})
        .then((response) => {
          this.$emit('html-changed');  
          this.clearFields();     
          this.successMessage = response.data.success; 
        })
        .catch((response) => {
          this.errorMessage = this.$h.makeErrorMessage(response.response.data);          
        });
    },   

    update() {
      this.clearMessages();

      let validateFields = this.validateFields(this.modalHtmlSnippet.title, this.modalHtmlSnippet.description, this.modalHtmlSnippet.snippet);
      if (!validateFields.success) {
        this.modalErrorMessage = validateFields.message;
        return
      }          

      this.$http.put('html/update/'+this.modalHtmlSnippet.id, {title: this.modalHtmlSnippet.title, description: this.modalHtmlSnippet.description, snippet: this.modalHtmlSnippet.snippet})
        .then((response) => {
          this.$emit('html-changed');  
          this.clearFields();     
          this.modalSuccessMessage = response.data.success; 
        })
        .catch((response) => {
          this.modalErrorMessage = this.$h.makeErrorMessage(response.response.data);         
        });
    }, 
    
    remove(id) {
      this.clearMessages();   

      this.$bvModal.msgBoxConfirm('Are you sure you want to remove this HTML snippet?')
        .then(value => {
          if (value) {
            this.$http.delete('html/delete/'+id)
              .then((response) => {
                this.$emit('html-changed');      
                this.successMessage = response.data.success; 
              })
              .catch((response) => {
                this.errorMessage = this.$h.makeErrorMessage(response.response.data);           
              });
          }
      })
    },    

    viewSnippet(snippetObject) {
      this.clearMessages();        
      this.modalHtmlSnippet = Object.assign({}, snippetObject);
      this.$bvModal.show('modal-snippet');
    },

    copySnippet(text) {
      navigator.clipboard.writeText(text);     
    }, 

    validateFields(title, description, snippet) {
      if (!(title && description && snippet)) { 
        return {success: false, message: "Please fill in all the fields."};
      }
      return {success: true, message: ""};
    },    
    
    clearFields() {
      this.htmlTitle = null;      
      this.htmlDescription = null;
      this.htmlSnippet = null;
    },

    clearMessages() {
      this.successMessage = null;
      this.errorMessage = null;
      this.modalSuccessMessage = null;
      this.modalErrorMessage = null;      
    },     
    
    highlighter(code) {
      return highlight(code, languages.markup);
    },    
  }
}
</script>

<style>
#modal-html-description {
  resize: none;
}
#html-description {
  resize: none;
}

.my-editor {
  background: #2d2d2d;
  color: #ccc;
  font-family: Fira code, Fira Mono, Consolas, Menlo, Courier, monospace;
  font-size: 14px;
  line-height: 1.5;
  padding: 5px;
}

.prism-editor__textarea:focus {
  outline: none;
}
</style>