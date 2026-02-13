<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectGallery;

class PortfolioController extends Controller
{
    public function index()
    {
        // Get high-quality images from across projects to showcase work
        $portfolioItems = ProjectGallery::with('client')
            ->whereIn('type', ['design', 'other'])
            ->latest()
            ->get();

        return view('portfolio.index', compact('portfolioItems'));
    }
}