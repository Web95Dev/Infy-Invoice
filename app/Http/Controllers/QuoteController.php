<?php

namespace App\Http\Controllers;

use App\Exports\AdminQuotesExport;
use App\Http\Requests\CreateQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Quote;
use App\Repositories\QuoteRepository;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QuoteController extends AppBaseController
{
    /** @var QuoteRepository */
    public $quoteRepository;

    public function __construct(QuoteRepository $quoteRepo)
    {
        $this->quoteRepository = $quoteRepo;
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        $statusArr = Quote::STATUS_ARR;
        $status = $request->status;

        return view('quotes.index', compact('statusArr', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $data = $this->quoteRepository->getSyncList();

        return view('quotes.create')->with($data);
    }
}
