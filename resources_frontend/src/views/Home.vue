<template>
  <b-container class="text-center">
      <h1 class="mt-5 display-2 text-success">The place where you can manage the world's resources</h1>

      <h1 class="mt-5 display-4 text-muted">Choose your destiny:</h1>

      <div class="mt-3">
        <b-button-group size="lg">
          <b-button 
            squared 
            variant="primary" 
            @click="chooseDestiny('Administrator')">
            Do you want to be an administrator of our resources?
          </b-button>
          <b-button 
            squared 
            @click="chooseDestiny('Visitor')">
            Do you want to just consume our resources?
          </b-button>
        </b-button-group>
      </div>

      <h4 class= "mt-5">The Remote Company - Test assignment</h4>
      <h5 class= "text-muted">Bruno Araújo - 2021</h5>      
  </b-container>
</template>

<script>

export default {
  name: 'Home',
  
  methods: {
    chooseDestiny(userType) {
      if (userType == 'Administrator') {        
        this.$router.push({ name: 'Resources', params: { userType: 'admin' } });      
      } else if (userType == 'Visitor') {
        this.$router.push({ name: 'Resources', params: { userType: 'visitor' } })  
      } 
    }
  }  
}
</script>
