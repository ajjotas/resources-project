<template>
  <div>
    <template v-if="editMode">    
      <b-alert variant="success" :show="successMessage ? true : false">{{successMessage}}</b-alert> 
      <b-alert variant="danger" :show="errorMessage ? true : false">{{errorMessage}}</b-alert>      
      
      <b-card title="Add a new PDF file">
        <b-form-group label="File description" label-for="file-description">
          <b-form-input maxlength="50" id="file-description" v-model="fileDescription" trim></b-form-input>
        </b-form-group>
        <div>
          <b-form-file v-model="file" class="mt-3" plain></b-form-file>
        </div>
        <b-button class="mt-3 float-right" squared variant="success" @click="add">Add PDF file</b-button>
      </b-card>
    </template>

    <b-card class="mt-2" title="Added PDF files">
      <b-table class="mb-1" responsive="lg" striped hover :items="pdfFiles" :fields="pdfTable.fields">
        <template #cell(id)="data">
          <b-icon-trash v-if="editMode" class="float-right clickable" variant="danger" title="Delete file" @click="remove(data.item.id)"></b-icon-trash>
          <b-icon-download class="float-right clickable" title="Download file" @click="download(data.item.id)"></b-icon-download>
        </template>   
      </b-table>                  
    </b-card>   
  </div>
</template>

<script>
export default {
  name: 'PDF',
  props: {
    mode: String,
    pdfFiles: Array
  },

  data() {
    return {
      successMessage: null,
      errorMessage: null,

      fileDescription: null,
      file: null,

      pdfTable: {
        fields: [
          { key: "description", label: "Description" },
          { key: "id", label: "" }
        ],
      },  
    }
  },

  computed: {
    editMode() {
      return this.mode == "edit";
    }
  },

  methods: {
    add() {
      this.clearMessages();
      
      let validateFields = this.validateFields(this.file, this.fileDescription);
      if (!validateFields.success) {
        this.errorMessage = validateFields.message;
        return
      }         

      let config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      };

      let data = new FormData();
      data.append('file', this.file);
      data.append('description', this.fileDescription);

      this.$http.post('pdf/add', data, config)
        .then((response) => {
          this.$emit('pdf-changed');  
          this.clearFields();     
          this.successMessage = response.data.success; 
        })
        .catch((response) => {
          this.errorMessage = this.$h.makeErrorMessage(response.response.data);          
        });
    },

    remove(id) {
      this.clearMessages();   

      this.$bvModal.msgBoxConfirm('Are you sure you want to remove this PDF file?')
        .then(value => {
          if (value) {
            this.$http.delete('pdf/delete/'+id)
              .then((response) => {
                this.$emit('pdf-changed');      
                this.successMessage = response.data.success; 
              })
              .catch((response) => {
                this.errorMessage = this.$h.makeErrorMessage(response.response.data);         
              });
          }
      })
    },

    download(id) {
      this.clearMessages();

      this.$http.get('pdf/download/'+id, {responseType: 'blob'})
        .then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement('a');
   
          fileLink.href = fileURL;
          fileLink.setAttribute('download', 'file.pdf');
          document.body.appendChild(fileLink);
   
          fileLink.click();
        })
    },  

    validateFields(file, fileDescription) {
      if (!(file && fileDescription)) { 
        return {success: false, message: "Please fill in all the fields."};
      }
      return {success: true, message: ""};
    },   

    clearFields() {
      this.file = null;
      this.fileDescription = null;
    },

    clearMessages() {
      this.successMessage = null;
      this.errorMessage = null;
    },    
  }
}
</script>
