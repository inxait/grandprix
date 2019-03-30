<?php

namespace App\Http\Controllers;

use App\Helpers\Calendar;
use App\Post;
USE App\Document;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Points;
use Auth;
use DB;

class HomeController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $currentPeriod = Calendar::getCurrentPeriod();
        $ranking = Points::getUsersTrimesterRanking($currentPeriod);
        $goals = FulfillmentsController::getGoalsUser($user->id);
        $month = Calendar::getHumanMonth(date('m'));
        $month = strtoupper($month);

        return view('pages.home')->with(compact('ranking', 'currentPeriod', 'goals', 'month'));
    }

    public function showNews()
    {
        $news = Post::where('published', true)->orderBy('created_at', 'DESC')->get();
        return view('pages.news')->with(compact('news'));
    }

    public function showDetailNews($slug)
    {
        $news = Post::where('published', true)->where('slug', $slug)->first();
        return view('pages.news-detail')->with(compact('news'));
    }

    public function getRankingInPeriod($period)
    {
        $ranking = Points::getUsersTrimesterRanking($period);

        return response()->json(['success' => true, 'info' => $ranking]);
    }

    /**
     *
     * Obtengo el material de estudio registrado.
     * @return Illuminate\Support\Facades\View
     *
     */
    public function getStudyMaterial()
    {
        $roles = Auth::user()->getRoleNames();
        $role = Role::where('name', $roles[0])->first();

        $documents = [];
        $docs = DB::table('documents_role')->where('role_id', $role->id)->get();

        foreach ($docs as $item) {
            $document = Document::find($item->document_id);
            array_push($documents, $document);
        }

        return view('pages.study-material')->with(compact('documents'));
    }

}
