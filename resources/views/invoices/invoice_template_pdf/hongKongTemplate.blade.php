<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.invoice.invoice_pdf') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }

        @if(getInvoiceCurrencyIcon($invoice->currency_id) == '€')
        .euroCurrency {
            font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }
        @endif
    </style>
</head>
<body>
@php $styleCss = 'style'; @endphp
<table width="100%">
    <tr>
        <td>
            <div class="logo"><img width="150px" src="{{ getLogoUrl() }}" alt=""></div>
        </td>
    </tr>
    <tr>
        <td>
            <span {{ $styleCss }}="">{{ html_entity_decode(getAppName()) }}</span><br>
            <b>{{ __('messages.common.address').':' }}&nbsp;</b>{!! $setting['company_address'] !!}<br>
            <b>{{ __('messages.common.mo').':' }}&nbsp;</b>{{ $setting['company_phone'] }}
            <div class="main-heading mt-2" {{ $styleCss }}="color: {{ $invoice_template_color }}; font-size: 30px"
            >{{ __('messages.common.invoice') }}</div>
            <br>
            <strong>{{ __('messages.invoice.invoice_id').':' }}&nbsp;</strong>#{{ $invoice->invoice_id }}<br>
            <strong>{{ __('messages.invoice.invoice_date').':' }}
                &nbsp;</strong>{{\Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat(currentDateFormat()) }}
            <br>
            <strong>{{ __('messages.invoice.due_date').':' }}
                &nbsp;</strong>{{\Carbon\Carbon::parse($invoice->due_date)->translatedFormat(currentDateFormat()) }}
            <br><br>
            <strong class="to-font-size">{{ __('messages.common.to') }}</strong><br>
            <b>{{ __('messages.common.name').':' }}&nbsp;</b>{{ $client->user->full_name }}
            <br>
            <b>{{ __('messages.common.email').':' }}&nbsp;</b>{{ $client->user->email }}
            @if(!empty($client->address))
                <br><b>{{ __('messages.common.address').':' }}&nbsp;</b>{{ $client->address }}
            @endif
        </td>
    </tr>
</table>
<table width="100%">
    <tr class="invoice-items">
        <td colspan="2">
            <table class="items-table">
                <thead>
                <tr class="tu">
                    <th {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }};padding: 5px;">#</th>
                    <th {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                    >{{ __('messages.product.product') }}</th>
                    <th class="number-align"
                    {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                    >{{ __('messages.invoice.qty') }}</th>
                    <th class="number-align"
                    {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                    >{{ __('messages.product.unit_price') }}</th>
                    <th class="number-align"
                    {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                    >{{ __('messages.invoice.tax').' (in %)' }}</th>
                    <th class="number-align"
                    {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                    >{{ __('messages.invoice.amount') }}</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($invoice) && !empty($invoice))
                    @foreach($invoice->invoiceItems as $key => $invoiceItems)
                        <tr>
                            <td {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                            >{{ $key + 1 }}</td>
                            @if(!empty($invoiceItems->product->description) && $setting['show_product_description'] == 1)
                                <td style="width: 30%!important;border: 1px solid {{ $invoice_template_color }};padding: 5px;">
                            @else
                                <td style="border: 1px solid {{ $invoice_template_color }}; padding: 5px;">
                            @endif
                            {{ isset($invoiceItems->product->name)?$invoiceItems->product->name:$invoiceItems->product_name?? __('messages.common.n/a') }}
                                @if(!empty($invoiceItems->product->description) && $setting['show_product_description'] == 1)
                                    <br><span style="font-size: 12px; word-break: break-all">{{ $invoiceItems->product->description }}</span>
                                @endif
                            </td>
                            
                            <td class="number-align"
                            {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                            >{{  number_format($invoiceItems->quantity,2) }}</td>
                            <td class="number-align"
                            {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;"
                            ><b class="euroCurrency">{{isset($invoiceItems->price) ? getInvoiceCurrencyAmount($invoiceItems->price,$invoice->currency_id,true): __('messages.common.n/a')}}</b></td>
                            <td class="number-align"
                            {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;">
                            @foreach($invoiceItems->invoiceItemTax as $keys => $tax)
                                {{ $tax->tax ?? __('messages.common.n/a') }}
                                @if (!$loop->last)
                                    ,
                                    @endif
                                    @endforeach
                                    </td>
                                    <td class="number-align"
                                    {{ $styleCss }}="border: 1px solid {{ $invoice_template_color }}; padding: 5px;" ><b
                                            class="euroCurrency">{{isset($invoiceItems->total) ? getInvoiceCurrencyAmount($invoiceItems->total,$invoice->currency_id,true): __('messages.common.n/a')}}</b></td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            @if(count($invoice->invoiceTaxes) > 0)
                <table width="20%" class="mt-2">
                    <thead>
                    <tr>
                        <td class="font-weight-bold"
                            style="white-space: nowrap">{{ __('messages.tax_information').':' }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->invoiceTaxes as $tax)
                        <tr>
                            <td>
                                <div class="mb-1"><b>{{ $tax->value.'%' }}</b>{{ ' ('. $tax->name .')' }}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </td>
        <td>
            <table class="invoice-footer">
                <tr>
                    <td class="font-weight-bold tu">{{ __('messages.invoice.amount').':' }}</td>
                    <td class="text-nowrap">
                        <b class="euroCurrency">{{ getInvoiceCurrencyAmount($invoice->amount,$invoice->currency_id,true) }}</b>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold tu">{{ __('messages.invoice.discount').':' }}</td>
                    <td class="text-nowrap">
                        @if($invoice->discount == 0)
                            <span>{{ __('messages.common.n/a') }}</span>
                        @else
                            @if(isset($invoice) && $invoice->discount_type == \App\Models\Invoice::FIXED)
                                <b class="euroCurrency">{{isset($invoice->discount) ? getInvoiceCurrencyAmount($invoice->discount,$invoice->currency_id,true):__('messages.common.n/a')}}</b>
                            @else
                                {{ $invoice->discount }}<span {{ $styleCss }}="font-family: DejaVu Sans">&#37;</span>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    @php
                        $itemTaxesAmount = $invoice->amount + array_sum($totalTax);
                        $invoiceTaxesAmount = ($itemTaxesAmount * $invoice->invoiceTaxes->sum('value') / 100);
                        $totalTaxes = (array_sum($totalTax) + $invoiceTaxesAmount)
                    @endphp
                    <td class="font-weight-bold tu">{{ __('messages.invoice.tax').':' }}</td>
                    <td class="text-nowrap">
                        {!!   (numberFormat($totalTaxes) != 0 ) ? '<b class="euroCurrency">'.getInvoiceCurrencyAmount($totalTaxes,$invoice->currency_id,true).'</b>'
                        : __('messages.common.n/a') !!}
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold tu">{{ __('messages.invoice.total').':' }}</td>
                    <td class="text-nowrap">
                        <b class="euroCurrency">{{ getInvoiceCurrencyAmount($invoice->final_amount,$invoice->currency_id,true) }}</b>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold tu">{{ __('messages.admin_dashboard.total_due').':' }}</td>
                    <td class="text-nowrap" {{ $styleCss }}="color: red">
                    <b class="euroCurrency">{{ getInvoiceCurrencyAmount(getInvoiceDueAmount($invoice->id),$invoice->currency_id,true) }}</b>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold tu">{{ __('messages.admin_dashboard.total_paid').':' }}</td>
                    <td class="text-nowrap" {{ $styleCss }}="color: green">
                    <b class="euroCurrency">{{ getInvoiceCurrencyAmount(getInvoicePaidAmount($invoice->id),$invoice->currency_id,true) }}</b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="w-100">
                <tr>
                    <td>
                        <strong>{{ __('messages.client.notes') }}:</strong>
                        <p class="font-color-gray">{!! nl2br(($invoice->note ?? __('messages.common.n/a'))) !!}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{ __('messages.invoice.terms') }}:</strong><br>
                        <p class="font-color-gray">{!! nl2br(($invoice->term ?? __('messages.common.n/a'))) !!}</p>
                    </td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="vertical-align-bottom">
            <strong>{{ __('messages.common.regards').':' }}</strong><br>
            <br><span {{ $styleCss }}="color: {{ $invoice_template_color }}">{{ getAppName() }}</span>
        </td>
    </tr>
</table>
</body>
</html>