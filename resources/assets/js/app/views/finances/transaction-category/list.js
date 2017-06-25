module.exports = (function() {

  var deletedNodeIds = [];

  /**
   * Initializes the view.
   */
  function initialize() {
    $('#transactionCategoryTree').jstree({
      'core': {
        'check_callback': true,

        'data': {
          'url': '/finances/transaction-category/list',
          'dataType': 'json',
        },

        'themes': {
          'dots': true,
          'icons': true,
          'responsive': true,
        },
      },

      'plugins': [
        'dnd',
        'sort',
        'contextmenu',
      ],
    });

    $('#transactionCategoryTree').on({
      'ready.jstree': function() {
        var jsTree = $('#transactionCategoryTree').jstree(true);
        jsTree.open_all();
      },

      'delete_node.jstree': function(e, data) {
        var deletedNode = data.node;

        if ($.isNumeric(deletedNode.id)) {
          deletedNodeIds.push(deletedNode.id);
        }
      },

      'select_node.jstree': function(e, data) {
        data.node.text = 'asdf'; // @todo
      },
    });

    $('.form-save-button').on('click', function() {
      onSubmit();
    });
  }

  /**
   * Form submit handler.
   */
  function onSubmit() {
    var jsTree = $('#transactionCategoryTree').jstree(true);
    var tree = jsTree.get_json(null, {
      no_state: true,
      no_data: true,
      no_li_attr: true,
      no_a_attr: true,
      flat: true,
    });

    $.ajax({
      url: '/finances/transaction-category/store',
      method: 'post',
      data: {
        newTree: tree,
        deletedNodeIds: deletedNodeIds,
      },
    }).done(function(msg) {
      if (msg.success) {
        deletedNodeIds = [];
        bootbox.alert(__('Finances:views.transaction-category.list.alerts.save-success'));
      } else {
        bootbox.alert(__('Finances:views.transaction-category.list.alerts.save-error'));
      }
    });
  }

  return {
    initializeView: function() {
      $(initialize);
      return this;
    },
  };
})();