<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.form', ['slider' => new Slider()]);
    }

    public function store(Request $request)
    {
        $isText = $request->input('type') === 'text';

        $validated = $request->validate([
            'type'            => 'required|in:image,text',
            'title'           => 'nullable|string|max:255',
            'subtitle'        => 'nullable|string',
            'image'           => ($isText ? 'nullable' : 'required') . '|image|max:2048',
            'bg_color_start'  => 'nullable|string|max:20',
            'bg_color_end'    => 'nullable|string|max:20',
            'text_align'      => 'nullable|in:left,center,right',
            'button_text'     => 'nullable|string|max:100',
            'button_link'     => 'nullable|string|max:255',
            'order'           => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        Slider::create($validated);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(Slider $slider)
    {
        return view('admin.sliders.form', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $isText = $request->input('type') === 'text';

        $validated = $request->validate([
            'type'            => 'required|in:image,text',
            'title'           => 'nullable|string|max:255',
            'subtitle'        => 'nullable|string',
            'image'           => 'nullable|image|max:2048',
            'bg_color_start'  => 'nullable|string|max:20',
            'bg_color_end'    => 'nullable|string|max:20',
            'text_align'      => 'nullable|in:left,center,right',
            'button_text'     => 'nullable|string|max:100',
            'button_link'     => 'nullable|string|max:255',
            'order'           => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image) Storage::disk('public')->delete($slider->image);
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $slider->update($validated);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroy(Slider $slider)
    {
        Storage::disk('public')->delete($slider->image);
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil dihapus.');
    }
}
