<template>
  <b-container>

    <b-button 
      class = "float-right"
      squared 
      @click="returnHome()">
      Exit
    </b-button>

    <div class="text-center">
      <h2 v-if="editMode" class="mt-5 text-muted">Resources factory</h2>
      <h2 v-else class="mt-5 text-muted">Resources showcase</h2>
    </div>

    <div class="mt-5 text-left">
      <h4 class="mt-2 text-muted">Choose the resource type</h4>    
      <b-card no-body justified>
        <b-tabs card fill>
          <b-tab title="PDF File" active>
            <PDF :mode="mode" :pdf-files="allPdfFiles" @pdf-changed="loadPdf"></PDF>
          </b-tab>

          <b-tab title="HTML Snippet">
            <HTML :mode="mode" :html-snippets="allHtmlSnippets" @html-changed="loadHtml"></HTML>
          </b-tab>

          <b-tab title="Link">
            <Link :mode="mode" :links="allLinks" @link-changed="loadLink"></Link>
          </b-tab>    

        </b-tabs>
      </b-card>
    </div>
  </b-container>
</template>

<script>
import PDF from '@/components/PDF.vue'
import HTML from '@/components/HTML.vue'
import Link from '@/components/Link.vue'

export default {
  name: 'Resources',
  components: {
    PDF, HTML, Link
  },

  data() {
    return {
      allPdfFiles: [],      
      allHtmlSnippets: [],
      allLinks: [],

      linkObject: {description: "eu", url: "eu tbm"}
    }
  },
  
  computed: {
    mode() {
      switch(this.$route.params.userType) {
        case 'admin': 
          return 'edit';
        case 'visitor':
          return 'view';
        default:
          return 'danger';
      } 
    },

    editMode() {
      return this.mode == 'edit';     
    }
  },

  mounted() {  
    this.loadPdf();
    this.loadHtml();
    this.loadLink();   
  },

  methods: {      
    loadPdf() {
      this.$http.get('pdf/all').then((response) => {
        this.allPdfFiles = response.data.allPdf;
      });      
    },

    loadHtml() {
      this.$http.get('html/all').then((response) => {
        this.allHtmlSnippets = response.data.allHtml;
      });      
    },
    
    loadLink() {
      this.$http.get('link/all').then((response) => {
        this.allLinks = response.data.allLink;
      }); 
    },
    
    returnHome() {  
      this.$router.push({ name: 'Home' });      
    }    
  }  
}
</script>

<style lang="scss">
.float-right {
  float: right !important;
}
.clickable,
.table-clickable tbody tr:hover {
  cursor: pointer;
}
</style>