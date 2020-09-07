<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Reservation;
use App\Reservation_item;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SampleChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $reservations = Reservation_item::select('created_at',DB::raw('SUM(tot_price) AS total'))
            ->where(DB::raw("year(created_at)"),Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString())->year)

            ->orderBy("created_at")
            ->groupBy(DB::raw("month(created_at)"));

        $vals = $reservations->pluck('total')
            ->toArray();

        $months = $reservations->selectRaw('month(created_at) as date')->pluck('date')
            ->toArray();




        return Chartisan::build()
            ->labels($months)
            ->dataset('Sample', $vals);

    }
}
