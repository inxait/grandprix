<?php

namespace App\Helpers;

use Carbon\Carbon;

class Calendar
{
    public static function listMonths()
    {
        $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                   'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        return $months;
    }

    public static function getHumanMonth($number)
    {
        $months = self::listMonths();

        return $months[(int) $number-1];
    }

    public static function getNumberMonth($month)
    {
        $months = self::listMonths();

        return (int) array_search($month, $months) + 1;
    }

    public static function getCurrentPeriod()
    {
        $period = 1;
        $now = Carbon::now();

        if ($now->month > 3 && $now->month < 7) {
            $period = 2;
        }

        if ($now->month > 6 && $now->month < 10) {
            $period = 3;
        }

        if ($now->month >= 10) {
            $period = 4;
        }

        return $period;
    }

    public static function getMonthsInPeriod($period)
    {
        $months = [];

        $start = Carbon::createFromFormat('Y-m-d', $period->start_date);
        $startMonth = $start->month;

        $finish = Carbon::createFromFormat('Y-m-d', $period->finish_date);
        $finishMonth = $finish->month;
        $allMonths = self::listMonths();

        for ($i = ($startMonth - 1); $i < $finishMonth; $i++) {
            array_push($months, $allMonths[$i]);
        }

        return $months;
    }
}
