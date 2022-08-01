<?php

use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client as Client;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceTemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminWalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::middleware(['xss'])->group(function () {
    Route::get('/', function () {
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                return Redirect::to(getDashboardURL());
            }

            return Redirect::to(getClientDashboardURL());
        }

        return redirect(route('login'));
    });

    Route::post('update-language', [UserController::class, 'updateLanguage'])->name('change-language');
    //Notification routes
    Route::get('/notification/{notification}/read',
        [NotificationController::class, 'readNotification'])->name('read.notification');
    Route::post('/read-all-notification',
        [NotificationController::class, 'readAllNotification'])->name('read.all.notification');
    //update darkMode Field
    Route::get('update-dark-mode', [UserController::class, 'updateDarkMode'])->name('update-dark-mode');

    Route::get('invoice/{invoiceId}', [InvoiceController::class, 'showPublicInvoice'])->name('invoice-show-url');
    Route::get('quote/{quoteId}', [QuoteController::class, 'showPublicQuote'])->name('quote-show-url');
    Route::get('invoice/{invoiceId}/payment',
        [InvoiceController::class, 'showPublicPayment'])->name('invoices.public-payment');
    Route::get('invoice-pdf/{invoice}',
        [InvoiceController::class, 'getPublicInvoicePdf'])->name('public-view-invoice.pdf');
    Route::get('quote-pdf/{quote}',
        [QuoteController::class, 'getPublicQuotePdf'])->name('public-view-quote.pdf');
});

Route::prefix('client')->middleware(['auth', 'xss', 'role:client'])->group(function () {
    Route::get('dashboard',
        [Client\DashboardController::class, 'index'])->name('client.dashboard');

    Route::get('transactions', [Client\PaymentController::class, 'index'])->name('client.transactions.index');

    //Expenses
    Route::get('expenses', [Client\ExpensesController::class, 'index'])->name('client.expenses.index');

    //Invoice
    Route::get('invoices',
        [Client\InvoiceController::class, 'index'])->name('client.invoices.index');
    Route::get('invoices/{invoice}',
        [Client\InvoiceController::class, 'show'])->name('client.invoices.show');
    Route::get('invoices/{invoice}/pdf',
        [Client\InvoiceController::class, 'convertToPdf'])->name('clients.invoices.pdf');

    //Quote
    Route::name('client.')->group(function () {
        Route::resource('quotes', Client\QuoteController::class);
    });
    Route::get('quotes/{quote}/pdf',
        [Client\QuoteController::class, 'convertToPdf'])->name('client.quotes.pdf');

    //export quotes Excel file in client route
    Route::get('/quotes-excel',[Client\QuoteController::class, 'exportQuotesExcel'])->name('client.quotesExcel');
    // export quotes Pdf in client route
    Route::get('quotes-pdf', [Client\QuoteController::class, 'exportQuotesPdf'])->name('client.export.quotes.pdf');
    // export invoices Pdf in client route
    Route::get('/invoice-excel', [client\InvoiceController::class, 'exportInvoicesExcel'])->name('client.invoicesExcel');
    Route::get('invoice-pdf', [client\InvoiceController::class, 'exportInvoicesPdf'])->name('client.invoices.pdf');
    Route::get('transactions-excel', [client\PaymentController::class, 'exportTransactionsExcel'])->name('client.transactionsExcel');
    // export transactions Pdf in client route
    Route::get('transactions-pdf', [client\PaymentController::class, 'exportTransactionsPdf'])->name('client.export.transactions.pdf');
});

Route::prefix('client')->middleware('xss')->group(function () {
    Route::get('invoices/{invoice}/payment', [Client\PaymentController::class, 'show'])->name('clients.payments.show');
    //Payments
    Route::post('payments', [Client\PaymentController::class, 'store'])->name('clients.payments.store');
    Route::post('stripe-payment', [Client\StripeController::class, 'createSession'])->name('client.stripe-payment');
    Route::get('razorpay-onboard', [Client\RazorpayController::class, 'onBoard'])->name('razorpay.init');
    Route::get('paypal-onboard', [Client\PaypalController::class, 'onBoard'])->name('paypal.init');

    Route::get('payment-success', [Client\StripeController::class, 'paymentSuccess'])->name('payment-success');
    Route::get('failed-payment', [Client\StripeController::class, 'handleFailedPayment'])->name('failed-payment');

    Route::get('paypal-payment-success', [Client\PaypalController::class, 'success'])->name('paypal.success');
    Route::get('paypal-payment-failed', [Client\PaypalController::class, 'failed'])->name('paypal.failed');

    // razorpay payment
    Route::post('razorpay-payment-success', [Client\RazorpayController::class, 'paymentSuccess'])
        ->name('razorpay.success');
    Route::post('razorpay-payment-failed', [Client\RazorpayController::class, 'paymentFailed'])
        ->name('razorpay.failed')->middleware('');
    Route::get('razorpay-payment-webhook', [Client\RazorpayController::class, 'paymentSuccessWebHook'])
        ->name('razorpay.webhook');
});

require __DIR__.'/upgrade.php';
