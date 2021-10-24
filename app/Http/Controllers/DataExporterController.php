<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Services\EconomyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use LogicException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataExporterController extends Controller
{
    /**
     * @param $filename
     * @param $data
     * @return StreamedResponse
     */
    protected function exportToCsv($filename, $data): StreamedResponse
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
        array_unshift($data, array_keys($data[array_key_first($data)]));

        $callback = function() use ($data) {
            $FH = fopen('php://output', 'w');
            foreach ($data as $row) {
                $status = fputcsv($FH, $row);
                if($status === false)
                    throw new LogicException("fputcsv() error");
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function temperancePays(Request $request): StreamedResponse
    {
        $paysList = EconomyService::getPaysResources();
        $data = [];

        foreach($paysList as $pays) {
            $array = $pays['resources'];
            $array['id'] = $pays['ch_pay_id'];
            $array['type'] = Pays::class;
            $array['name'] = $pays['ch_pay_nom'];

            $data[] = $array;
        }

        return $this->exportToCsv('temperance-pays', $data);
    }
}
