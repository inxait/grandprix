<?php

namespace App\Helpers;

use App\Point;
use App\Fulfillment;
use App\User;
use Carbon\Carbon;

class Points
{
    public static function getUserPointsTotal($id)
    {
        $now = Carbon::now();
        $points = Point::where('user_id', $id)->where('year', '=', $now->year)->get();

        $total = 0;

        foreach ($points as $item) {
            $total += $item->value;
        }

        return $total;
    }

    public static function getUserTrimesterPoints($id, $period)
    {
        $now = Carbon::now();
        $month = $now->month;

        if ($period == 1) {
            $points = Point::where('user_id', $id)
                           ->where('year', '=', $now->year)->where('month', '<=', 3)->get();
        }

        if ($period == 2) {
            $points = Point::where('user_id', $id)
                           ->whereBetween('month', [4, 6])->where('year', '=', $now->year)->get();
        }

        if ($period == 3) {
            $points = Point::where('user_id', $id)
                           ->whereBetween('month', [7, 9])->where('year', '=', $now->year)->get();
        }

        if ($period == 4) {
            $points = Point::where('user_id', $id)
                           ->where('month', '>=', 10)->where('year', '=', $now->year)->get();
        }

        $total = 0;

        foreach ($points as $item) {
            $total += $item->value;
        }

        return $total;
    }

    public static function getUsersGeneralRanking()
    {
        $users = User::role('seller')->where('updated_data', true)->get();

        foreach ($users as $user) {
            $user->total_points = self::getUserPointsTotal($user->id);
        }

        $ranking = collect($users)->sort(function($a, $b) use ($users) {
            if ($a->total_points == $b->total_points) {
                return ($a->data_update_date < $b->data_update_date) ? -1 : 1;
            }

            return ($a->total_points > $b->total_points) ? -1 : 1;
        });

        return $ranking->values()->all();
    }

    public static function getUsersTrimesterRanking($period)
    {
        $users = User::role('seller')->where('updated_data', true)->get();
        foreach ($users as $user) {
            $user->total_points = self::getUserTrimesterPoints($user->id, $period);
            $user->avatar = (is_null($user->avatar()->first())) ? '' : $user->avatar()->first()->helmet;
        }

        $ranking = collect($users)->sort(function($a, $b) use ($users) {
            if ($a->total_points == $b->total_points) {
                return ($a->data_update_date < $b->data_update_date) ? -1 : 1;
            }

            return ($a->total_points > $b->total_points) ? -1 : 1;
        });

        return $ranking->values()->all();
    }

    public static function getType($num)
    {
        switch ($num) {
            case 1:
                return 'Activación en la plataforma';
                break;
            case 2:
                return 'Cumplimiento metrica';
                break;
            case 3:
                return 'Sobrecumplimiento metrica';
                break;
            case 4:
                return 'Participación Trivia';
                break;
            case 5:
                return 'Puntuación custom';
                break;
        }
    }
}

