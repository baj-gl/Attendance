<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Report;
use Carbon\Carbon;
use DateTime;
use Carbon\CarbonInterval;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $current_week = Report::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        // dd($current_week);
        return view('export');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        $from = $request->from ?? "";
        $to = $request->to ?? "";

        $datetime1 = new DateTime($from);
        $datetime2 = new DateTime($to);
        $interval = $datetime1->diff($datetime2);
        $dayCount = $interval->format('%d');//now do whatever you like with $days
        
        if($dayCount > 4)
        {
            return response()->json(['success' => false, "error" => "Please Select Only One Week."]);
        }
        if($from == $to)
        {
            $dateFrom = Carbon::parse($from)->format('d M');
            return Excel::download(new ReportExport($from, $to), $dateFrom . '.xlsx');
        }
        else
        {
            $dateFrom = Carbon::parse($from)->format('M d');
            $dateTo = Carbon::parse($to)->format('M d');
            return Excel::download(new ReportExport($from, $to), $dateFrom . ' - ' . $dateTo . '.xlsx');
        }
    }
}
