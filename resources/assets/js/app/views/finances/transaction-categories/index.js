module.exports = (function () {

    var deletedNodeIds = [];

    function initialize() {
        $('#transactionCategoryTree').jstree({
            'core': {
                'check_callback': true,

                'data': {
                    'url': '/finances/transaction-categories',
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
            'ready.jstree': function () {
                var jsTree = $('#transactionCategoryTree').jstree(true);
                jsTree.open_all();
            },

            'delete_node.jstree': function (e, data) {
                var deletedNode = data.node;

                if ($.isNumeric(deletedNode.id)) {
                    deletedNodeIds.push(deletedNode.id);
                }
            },
        });

        $('#btnCreateNewRootCategory').on('click', function () {
            bootbox.prompt(__('views.finances.transaction-categories.index.prompts.new-root-category-name'), function (categoryName) {
                if (typeof categoryName === 'string') {
                    var jsTree = $('#transactionCategoryTree').jstree(true);

                    jsTree.create_node('#', {
                        'text': categoryName,
                    }, 'last');
                }
            });
        });

        $('#editTransactionCategoriesForm').ajaxForm({
            prepareData: function () {
                var jsTree = $('#transactionCategoryTree').jstree(true);
                var tree = jsTree.get_json(null, {
                    no_state: true,
                    no_data: true,
                    no_li_attr: true,
                    no_a_attr: true,
                    flat: true,
                });

                if (tree.length === 0) {
                    bootbox.alert(__('requests.transaction.category.crud.validation.empty-tree'));
                    return null;
                }

                return {
                    newTree: tree,
                    deletedNodeIds: deletedNodeIds,
                };
            },

            success: function (msg) {
                this.getDefaultOptions().success(msg);

                if (msg.success) {
                    bootbox.alert(__('requests.transaction.category.crud.messages.updated'));
                    deletedNodeIds = [];
                }
            },
        });
    }

    return {
        initializeView: function () {
            $(initialize);
            return this;
        },
    };
})();