<?php

namespace App\Http\Controllers;

use App\Models\PresentationSlide;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function index()
    {
        $slides = PresentationSlide::where('is_active', true)->orderBy('order')->get();
        return view('presentation', compact('slides'));
    }

    public function manage()
    {
        $slides = PresentationSlide::orderBy('order')->get();
        return view('presentation.manage', compact('slides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'layout_type' => 'required',
        ]);

        PresentationSlide::create($request->all());
        return back()->with('success', 'Slide added successfully.');
    }

    public function update(Request $request, PresentationSlide $slide)
    {
        $slide->update($request->all());
        return back()->with('success', 'Slide updated successfully.');
    }

    public function destroy(PresentationSlide $slide)
    {
        $slide->delete();
        return back()->with('success', 'Slide deleted successfully.');
    }
}