module.exports = (function() {

  function initialize() {
    $('#userForm').ajaxForm();
  }

  return {

    initializeView: function() {
      $(initialize);
      return this;
    },

  };
})();