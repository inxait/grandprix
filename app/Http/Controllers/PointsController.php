<?php

namespace App\Http\Controllers;

use App\Point;
use App\User;
use App\Helpers\Calendar;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function showUpdatePoints()
    {
        $points = Point::orderBy('created_at', 'desc')->paginate(10);

        foreach ($points as $item) {
            $item->month = Calendar::getHumanMonth($item->month);
            $item->user_identification = User::find($item->user_id)->identification;
        }

        return view('pages.admin.update-points')->with(compact('points'));
    }
}
