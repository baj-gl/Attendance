<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Attendance([
            "name" => $row['user'],
            "user_id" => $row['workid'],
            "date" => date('Y-m-d', strtotime($row['date'])),
            "time" => $row['time'],
        ]);
    }
}
