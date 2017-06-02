@php
    /**
     * @var \App\Modules\Finances\Models\Transaction $transaction
     * @var \App\Modules\Finances\Models\TransactionCategory[] $categories
     */
@endphp

@php
    $transactionTypes = \App\Modules\Finances\Models\Transaction::getTypes();
@endphp

{{-- Transaction name --}}
{!!
    Form::textInput()
        ->setIdAndName('transactionName')
        ->setLabel(__('Finances::views/transaction/create-edit.transaction-name.label'))
        ->setPlaceholder( __('Finances::views/transaction/create-edit.transaction-name.placeholder'))
        ->setValueFromModel($transaction, 'name')
        ->setRequired(true)
        ->setAutofocus(true)
 !!}

{{-- Transaction category --}}
{!!
    Form::select()
        ->setIdAndName('transactionCategoryId')
        ->setLabel(__('Finances::views/transaction/create-edit.transaction-category.label'))
        ->setValueFromModel($transaction, 'category_id')
        ->setItems(function() use ($categories) {
            $result = [
                null => __('Finances::views/transaction/create-edit.transaction-category.empty-option'),
            ];

            foreach ($categories as $category) {
                /**
                 * @var \App\Modules\Finances\Models\TransactionCategory $category
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
        ->setLabel(__('Finances::views/transaction/create-edit.transaction-description.label'))
        ->setPlaceholder(__('Finances::views/transaction/create-edit.transaction-description.placeholder'))
        ->setValueFromModel($transaction, 'description')
!!}

{{-- Transaction type --}}
{!!
    Form::select()
        ->setIdAndName('transactionType')
        ->setLabel(__('Finances::views/transaction/create-edit.transaction-type.label'))
        ->setValueFromModel($transaction, 'type')
        ->setRequired(true)
        ->setItems(function() use ($transactionTypes) {
            $items = [];

            foreach ($transactionTypes as $transactionType) {
                $items[$transactionType] =  __('Finances::common/transaction.type.' . $transactionType);
            }

            return $items;
        })
 !!}