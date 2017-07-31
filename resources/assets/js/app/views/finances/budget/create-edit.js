module.exports = (function() {
  var form = undefined;

  function showConsolidatedBudgets(show) {
    if (show) {
      $('#consolidated_budgets_wrapper').show().addClass('required');
    } else {
      $('#consolidated_budgets_wrapper').hide().removeClass('required');
    }

    $(form).find('select[name="consolidated-budgets"]').prop('required', show);
  }

  function initialize() {
    form = $('#budgetForm');

    form.on('change', '[name="type"]', function() {
      showConsolidatedBudgets($(this).val() === 'consolidated');
    });

    form.find('[name="type"]').trigger('change');
  }

  return {
    initializeView: function() {
      $(initialize);
      return this;
    },
  };
})();