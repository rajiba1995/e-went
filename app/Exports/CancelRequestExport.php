<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CancelRequestExport implements FromView
{
    public $requests;

    public function __construct($requests)
    {
        $this->requests = $requests;
    }

    public function view(): View
    {
        return view('exports.cancel_requests', [
            'requests' => $this->requests
        ]);
    }
}
