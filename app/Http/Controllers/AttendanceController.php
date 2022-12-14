<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Imports\AttendanceImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Report;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //
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
        Attendance::truncate();
        Report::truncate();
        
        Excel::import(new AttendanceImport, $request->file);

        $users = Attendance::select('user_id')->groupBy('user_id')->get();
        $dates = Attendance::select('date')->groupBy('date')->orderBy('id', 'asc')->get();

        foreach($dates as $date)
        {
            foreach($users as $user)
            {
                if($user->user_id == 0)
                {
                    continue;
                }
                
                $in = Attendance::where('user_id', $user->user_id)->where('date', $date->date)->first();
                $out = Attendance::where('user_id', $user->user_id)->where('date', $date->date)->where('time', '<', '24:00:00')->orderBy('id', 'desc')->first();

                if($in && $in->time > '00:00:00' && $in->time < '07:00:00')
                {
                    $prevDate = Carbon::parse($in->date)->subDay()->format('Y-m-d');

                    $updateOut = Report::where('user_id', $user->user_id)->where('date', $prevDate);
                    if($updateOut->count() <= 0)
                    {
                        $prevTime = Attendance::where('user_id', $user->user_id)->where('date', $prevDate)->where('time', '>', '07:00:00')->first();

                        if($prevTime != null)
                        {
                            $checkIn = strtotime($prevTime->time);
                            $checkOut = strtotime($in->time);
                            $res = $checkOut - $checkIn;
                            $total_hrs = date("H:i:s", $res);
            
                            Report::create(
                            [
                                'name' => $prevTime->name ?? '',
                                'user_id' => $prevTime->user_id ?? '',
                                'date' => $prevTime->date ?? '',
                                'in_time' => $prevTime->time ?? '',
                                'out_time' => $in->time ?? '',
                                'total_hours' => $total_hrs ?? '',
                                'seconds' => $res,
                            ]);
                        }
                        else
                        {
                            continue;
                        }
                    }
                    else
                    {
                        $updateOut->update([
                            'out_time' => $in->time,
                        ]);                        
                    }

                    $in = Attendance::where('user_id', $user->user_id)->where('date', $date->date)->where('time', '>', '07:00:00')->first();
                }

                if($out == $in)
                {
                    continue;
                }
                if($in)
                {
                    $checkIn = strtotime($in->time);
                    $checkOut = strtotime($out->time);
                    $res = $checkOut - $checkIn;
                    $total_hrs = date("H:i:s", $res);

                    Report::create([
                        'name' => $in->name ?? '',
                        'user_id' => $in->user_id ?? '',
                        'date' => $in->date ?? '',
                        'in_time' => $in->time ?? '',
                        'out_time' => $out->time ?? '',
                        'total_hours' => $total_hrs ?? '',
                        'seconds' => $res,
                    ]);
                }
            }
        }

        return redirect()->route('report.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
