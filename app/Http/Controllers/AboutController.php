<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use App\Models\WorkExperience;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $certifications = Certification::orderBy('sort_order')->orderByDesc('earned_at')->get();
        $experiences = WorkExperience::orderBy('sort_order')->orderByDesc('start_date')->get();

        return view('about', compact('certifications', 'experiences'));
    }
}
