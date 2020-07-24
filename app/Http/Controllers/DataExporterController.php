<?php

namespace App\Http\Controllers;

use App\Models\TemperanceOrganisation;
use App\Models\TemperancePays;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DataExporterController extends Controller
{
    protected function exportToCsv($filename, $data)
    {
        $filename = $filename . '-' . Carbon::today()->format('Y-m-d') . '.csv';

        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=' . $filename
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        # add headers for each column in the CSV download
        array_unshift($data, array_keys($data[0]));

        $callback = function() use ($data) {
            $FH = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function temperancePays(Request $request)
    {
        $data = TemperancePays::all()->toArray();
        return $this->exportToCsv('temperance-pays', $data);
    }

    public function temperanceOrganisation(Request $request)
    {
        $data = TemperanceOrganisation::all()->toArray();
        return $this->exportToCsv('temperance-organisation', $data);
    }
}
