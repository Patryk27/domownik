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
    });

    $('#btnCreateNewRootCategory').on('click', function() {
      bootbox.prompt(__('views.finances.transaction-category.list.prompts.new-root-category-name'), function(categoryName) {
        if (typeof categoryName === 'string') {
          var jsTree = $('#transactionCategoryTree').jstree(true);

          jsTree.create_node('#', {
            'text': categoryName,
          }, 'last');
        }
      });
    });

    $('#editTransactionCategoriesForm').ajaxForm({
      prepareData: function() {
        var jsTree = $('#transactionCategoryTree').jstree(true);
        var tree = jsTree.get_json(null, {
          no_state: true,
          no_data: true,
          no_li_attr: true,
          no_a_attr: true,
          flat: true,
        });

        return {
          newTree: tree,
          deletedNodeIds: deletedNodeIds,
        };
      },

      success: function(msg) {
        this.getDefaultOptions().success(msg);

        if (msg.success) {
          bootbox.alert(__('views.finances.transaction-category.list.alerts.save-success'));
        }
      },

      always: function() {
        deletedNodeIds = [];
      },
    });
  }

  return {
    initializeView: function() {
      $(initialize);
      return this;
    },
  };
})();