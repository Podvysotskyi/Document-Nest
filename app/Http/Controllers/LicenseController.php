<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class LicenseController extends Controller
{
    public function __invoke(): View
    {
        return view('license', [
            'licenseText' => File::get(base_path('LICENSE')),
        ]);
    }
}
