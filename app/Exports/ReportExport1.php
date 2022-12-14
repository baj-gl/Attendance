<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class ReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $fromDate;
    private $toDate;


    public function __construct($from, $to)
    {
        $this->fromDate = $from;
        $this->toDate = $to;
    }

    public function headings():array{
        if($this->fromDate == $this->toDate){
            return[
                'Emp ID',
                'Name',
                'Date',
                'CheckedIn',
                'CheckedOut',
                'Total Hours',
            ];
        }
        else
        {
            return[
                'Emp ID',
                'Name',
                'Mon',
                'Tue',
                'Wed',
                'Thur',
                'Fri',
                'Total Hours',
            ];

        }
    } 
    
    public function collection()
    {

        if($this->fromDate != null && $this->toDate != null)
        {
            if($this->fromDate != $this->toDate)
            {
                $emps = Report::select('user_id', 'name')->groupBy('user_id')->get();
            
                foreach($emps as $emp)
                {
                    if($emp->user_id == 0)
                    {
                        continue;
                    }
                    else
                    {
                        $datetime1 = new DateTime($this->fromDate);
                        $datetime2 = new DateTime($this->toDate);
                        $interval = $datetime1->diff($datetime2);
                        $dayCount = $interval->format('%d');//now do whatever you like with $days
                        $dayCount = $dayCount;
                        // dd($dayCount);

                        $days = Report::where('user_id', $emp->user_id)->whereBetween('date', [$this->fromDate, $this->toDate])->orderBy('user_id', 'ASC')->select('date', 'total_hours')->get();
                        
                        $start = Carbon::parse($this->fromDate);
                        $end = Carbon::parse($this->toDate);

                        $key = 1;
                        // do{
                            foreach($days as $day)
                            {
                                $checkDate = Carbon::parse($day->date);
                                // do
                                // {
                                    for($i=1; $i<=$dayCount; $i++)
                                    {
                                        if($start == $checkDate)
                                        {
                                            $emp[$key] = $day->total_hours;
                                            $key++;
                                            // dd($key);
                                            // continue;
                                            $start = Carbon::parse($start)->addDay(1);
                                            break;
                                        }
                                        elseif($start < $checkDate)
                                        {
                                            $emp[$key] = "";
                                            $key++;
                                            $start = Carbon::parse($start)->addDay(1);

                                            // continue;

                                        }
                                        else
                                        {
                                            break;
                                        }
                                        // elseif($start > $checkDate)
                                        // {
                                        //     break;
                                        // }
                                        // else
                                        // {
                                        //     $emp[$key + 1] = "Test";
                                        // }
                                        // $start = Carbon::parse($start)->addDay(1);
                                    }

                                    // $start = Carbon::parse($this->fromDate);
                                // }
                                // while($start <= $end);
                            }
                            // dd($dayCount);
                            // if($key <= $dayCount)
                            // {
                            //     for($i = $key; $i <= $dayCount; $i++)
                            //     {
                            //         $emp[$key] = "Leave";
                            //         $key++;
                            //     }
                            // }
                        // }
                        // while($start <= $end);
                        $reports = Report::where('user_id', $emp->user_id)->whereBetween('date', [$this->fromDate, $this->toDate])->groupBy('user_id')->select(DB::raw('sum(seconds) as sum'))->get();
            
                        foreach($reports as $rep)
                        {

                            $time = ($rep->sum) / 3600;

                            $hrsArray = (explode('.',$time));

                            $hrs = $hrsArray[0];

                            $minutes = ("0." . $hrsArray[1])*60;

                            $minArray = (explode('.',$minutes));

                            $min = $minArray[0];

                            if ( ! isset($minArray[1])) {
                                $minArray[1] = "00";
                            }

                            $seconds = ("0." . $minArray[1])*60;

                            $secArray = (explode('.',$seconds));

                            $sec = $secArray['0'];

                            if(strlen($hrs) < 2)
                            {
                                $hrs = '0' . $hrs;
                            }

                            if(strlen($min) < 2)
                            {
                                $min = '0' . $min;
                            }

                            if(strlen($sec) < 2)
                            {
                                $sec = '0' . $sec;
                            }


                            $total = $hrs . ':' . $min . ':' . $sec;

                            $emp['total'] = $total;

                        }


                    }

                    // dd($emp);
                }
                // dd($emps);

            }

            else
            {
                $report = Report::whereBetween('date', [$this->fromDate, $this->toDate])->select('user_id', 'name', 'date', 'in_time', 'out_time', 'total_hours')->get();
            }
        }


        if($this->fromDate != $this->toDate){

        $data = $emps->map(function($emps, $key) {									
            return [
                    'id' => $emps->user_id ?? '',
                    'name' => $emps->name ?? '',
                    'mon' => $emps[1] ?? '',
                    'tue' => $emps[2] ?? '',
                    'wed' => $emps[3] ?? '',
                    'thur' => $emps[4] ?? '',
                    'fri' => $emps[5] ?? '',
                    'total' => $emps->total ?? '',
                ];
            });

            // [1, 2, 3, 4, 5].forEach(v => {
            //       return;
            //   });

        return $data;
        }

        else{
            return $report;
        }
    }
}
