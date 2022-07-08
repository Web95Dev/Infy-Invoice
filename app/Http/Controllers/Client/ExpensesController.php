<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\AppBaseController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ExpensesController extends AppBaseController
{


    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {

        return view('client_panel.expenses.index');
    }
}
 