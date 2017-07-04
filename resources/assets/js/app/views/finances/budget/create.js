module.exports = (function() {

  function initialize() {
    function showConsolidatedBudgetsWrapper() {
      $('#consolidatedBudgetsWrapper').show().addClass('required');
      $('#consolidatedBudgets').prop('required', true);
    }

    function hideConsolidatedBudgetsWrapper() {
      $('#consolidatedBudgetsWrapper').hide().removeClass('required');
      $('#consolidatedBudgets').prop('required', false);
    }

    $('#budgetType').change(function() {
      switch ($(this).val()) {
        case 'regular':
          hideConsolidatedBudgetsWrapper();
          break;

        case 'consolidated':
          showConsolidatedBudgetsWrapper();
          break;
      }
    }).change();

    $('#budgetForm').ajaxForm();
  }

  return {

    initializeView: function() {
      $(initialize);
      return this;
    },

  }
})();