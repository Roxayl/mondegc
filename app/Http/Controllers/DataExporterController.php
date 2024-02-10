<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use LogicException;
use Roxayl\MondeGC\Models\Pays;
use Roxayl\MondeGC\Services\EconomyService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataExporterController extends Controller
{
    /**
     * Formate des données <code>$data</code> dans une chaîne au format CSV, et provoque le téléchargement du fichier
     * sous forme de réponse HTTP.
     *
     * @param  string  $filename
     * @param  array  $data
     * @return StreamedResponse
     */
    protected function exportToCsv(string $filename, array $data): StreamedResponse
    {
        $filename = $filename . '-' . Carbon::today()->format('Y-m-d') . '.csv';

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',   'Content-type' => 'text/csv',   'Content-Disposition' => 'attachment; filename=' . $filename,   'Expires' => '0',   'Pragma' => 'public',
        ];

        // Ajoute les en-têtes dans le fichier CSV résultat.
        array_unshift($data, array_keys($data[array_key_first($data)]));

        $callback = function () use ($data): void {
            $FH = fopen('php://output', 'w');
            foreach ($data as $row) {
                $status = fputcsv($FH, $row);
                if ($status === false) {
                    throw new LogicException('fputcsv() error');
                }
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * @return StreamedResponse
     */
    public function temperancePays(): StreamedResponse
    {
        $paysList = EconomyService::getPaysResources();
        $data = [];

        foreach ($paysList as $pays) {
            $array = $pays['resources'];
            $array['id'] = $pays['ch_pay_id'];
            $array['type'] = Pays::class;
            $array['name'] = $pays['ch_pay_nom'];

            $data[] = $array;
        }

        return $this->exportToCsv('temperance-pays', $data);
    }
}
