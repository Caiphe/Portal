<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        if(Gate::allows('administer-products')){
            return redirect()->route('admin.product.index');
        }

        if(Gate::allows('administer-users')){
            return redirect()->route('admin.user.index');
        }
        
        if(Gate::allows('administer-dashboard')){
            return redirect()->route('admin.dashboard.index');
        }

        if(Gate::allows('administer-content')){
            return redirect()->route('admin.content.index');
        }
    }
}
