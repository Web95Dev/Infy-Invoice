<?php

namespace App\Http\Livewire;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ClientExpensesTable extends LivewireTableComponent
{
    protected $model = Invoice::class;
    protected string $tableName = 'invoices';
    public $showButtonOnHeader = true;
    public $buttonComponent = 'client_panel.expenses.components.add-button';

    public $filterComponent = ['client_panel.invoices.components.filter', Invoice::STATUS_ARR];
    public $listeners = ['changeInvoiceStatusFilter', 'changeRecurringStatusFilter', 'resetPageTable'];


    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.date'), 'invoice_date')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    return view('invoices.components.invoice-due-date')
                        ->withValue([
                            'invoice-date' => $row->invoice_date,
                        ]);
                }),
            Column::make(__('messages.previous_amount'), 'amount')
                ->searchable()
                ->view('invoices.components.transaction'),    
            Column::make(__('messages.amount_spent'), 'final_amount')
                ->searchable()
                ->view('invoices.components.amount'),
            Column::make(__('messages.current_amount'), 'status')
                ->searchable()
                ->view('invoices.components.transaction-status'),
        ];
    }




}
