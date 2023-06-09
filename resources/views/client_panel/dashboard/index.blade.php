@extends('client_panel.layouts.app')
@section('title')
    {{__('messages.dashboard')}}
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="row">
                <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                    <a href="{{ route('client.transactions.index') }}"
                           class="mb-xl-8 text-decoration-none">

                            <div
                                    class="bg-info shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                        class="bg-blue-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-money-bill-transfer display-4 card-icon text-white"></i>

                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{getCurrencyAmount($total_payments) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.total_transactions') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('client.invoices.index') }}"
                           class="mb-xl-8 text-decoration-none">

                            <div
                                    class="bg-secondary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                        class="bg-gray-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-file-invoice-dollar display-4 card-icon text-dark"></i>

                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{ formatTotalAmount($total_invoices) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.total_invoices') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <div
                                    class="bg-warning shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                        class="bg-yellow-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-wallet display-4 card-icon text-white"></i>

                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{ formatTotalAmount($total_invoices) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.total_wallet') }}</h3>
                                </div>
                            </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                            <div
                                    class="bg-success shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                        class="bg-green-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-piggy-bank display-4 card-icon text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{getCurrencyAmount($paid_amount) }}
                                    </h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.total_economy') }}</h3>
                                </div>
                            </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                    <a href="{{ route('client.invoices.index',['status'=>3]) }}"
                           class="mb-xl-8 text-decoration-none">

                            <div
                                    class="bg-primary shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                        class="bg-cyan-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-money-bill-trend-up display-4 card-icon text-white"></i>
                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{ getCurrencyAmount($due_amount) }}</h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.total_due') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-sm-6 widget">
                        <a href="{{ route('client.invoices.index',['status'=>1]) }}"
                           class="mb-xl-8 text-decoration-none">

                            <div
                                    class="bg-danger shadow-md rounded-10 p-xxl-10 px-7 py-10 d-flex align-items-center justify-content-between my-3">
                                <div
                                        class="bg-red-300 widget-icon rounded-10 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-sack-dollar display-4 card-icon text-white"></i>

                                </div>
                                <div class="text-end text-white">
                                    <h2 class="fs-1-xxl fw-bolder text-white">{{ formatTotalAmount($unpaid_invoices) }}
                                    </h2>
                                    <h3 class="mb-0 fs-4 fw-light">{{ __('messages.admin_dashboard.total_unpaid_invoices') }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
