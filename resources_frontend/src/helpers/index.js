var helpers = {
  makeErrorMessage: function(data) {
    if (data.errors) {
      return data.errors[Object.keys(data.errors)[0]][0];
    } else {
      return data.message;
    }
  }
};

helpers = Object.freeze(helpers);
export default helpers;
