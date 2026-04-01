<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('id')->paginate(20);
        return Inertia::render('Admin/Testimonials/Index', ['testimonials' => $testimonials]);
    }

    public function create()
    {
        return Inertia::render('Admin/Testimonials/Form', ['testimonial' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote'      => 'required|string',
            'name'       => 'required|string|max:255',
            'role'       => 'nullable|string|max:255',
            'company'    => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status'     => 'boolean',
        ]);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['status'] = $request->boolean('status', true);
        Testimonial::create($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        return Inertia::render('Admin/Testimonials/Form', ['testimonial' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'quote'      => 'required|string',
            'name'       => 'required|string|max:255',
            'role'       => 'nullable|string|max:255',
            'company'    => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status'     => 'boolean',
        ]);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['status'] = $request->boolean('status', true);
        $testimonial->update($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
