<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizedDatabaseDump;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatabaseExportController extends Controller
{
    public function __invoke(SanitizedDatabaseDump $exporter): StreamedResponse
    {
        $this->authorize('export', SanitizedDatabaseDump::class);

        return response()->streamDownload(
            $exporter->stream(...),
            'database-'.now()->format('Y-m-d-His').'.sql',
            [
                'Content-Type' => 'application/sql',
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
                'X-Accel-Buffering' => 'no',
            ],
        );
    }
}
