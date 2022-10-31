<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ProjectStatusLabel;
class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projects=Booking::all();
        return view('project',compact('projects'))->render();
    }

    public function renderproject(Request $request )
    {   
        $project=Booking::find($request->get('id'));
        $ProjectStatusLabel=ProjectStatusLabel::all();
        return view('single-project',compact('project','ProjectStatusLabel'))->render();
    }
}
