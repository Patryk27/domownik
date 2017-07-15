@php
    /**
     * @var \App\Models\Transaction $transaction
     * @var \App\Models\TransactionCategory[] $categories
     */
@endphp

{{-- Transaction name --}}
{!!
    Form::textInput()
        ->setIdAndName('transactionName')
        ->setLabel(__('views/finances/transaction/common/create-edit.transaction-name.label'))
        ->setPlaceholder( __('views/finances/transaction/common/create-edit.transaction-name.placeholder'))
        ->setValueFromModel($transaction, 'name')
        ->setRequired(true)
        ->setAutofocus(true)
 !!}

{{-- Transaction category --}}
{!!
    Form::select()
        ->setIdAndName('transactionCategoryId')
        ->setLabel(__('views/finances/transaction/common/create-edit.transaction-category.label'))
        ->setValueFromModel($transaction, 'category_id')
        ->setItems(function() use ($categories) {
            $result = [
                null => __('views/finances/transaction/common/create-edit.transaction-category.empty-option'),
            ];

            foreach ($categories as $category) {
                /**
                 * @var \App\Models\TransactionCategory $category
                 */

                 $categoryPresenter = $category->getPresenter();

                 $result[$category->id] = $categoryPresenter->getFullName();
            }

            return $result;
        })
 !!}

{{-- Transaction description --}}
{!!
    Form::textarea()
        ->setIdAndName('transactionDescription')
        ->setLabel(__('views/finances/transaction/common/create-edit.transaction-description.label'))
        ->setPlaceholder(__('views/finances/transaction/common/create-edit.transaction-description.placeholder'))
        ->setValueFromModel($transaction, 'description')
!!}

{{-- Transaction type --}}
{!!
    Form::select()
        ->setIdAndName('transactionType')
        ->setLabel(__('views/finances/transaction/common/create-edit.transaction-type.label'))
        ->setValueFromModel($transaction, 'type')
        ->setRequired(true)
        ->setItems(function() {
            $items = [];

            $transactionTypes = \App\Models\Transaction::getTypes();

            foreach ($transactionTypes as $transactionType) {
                $items[$transactionType] =  __('common/transaction.type.' . $transactionType);
            }

            return $items;
        })
 !!}