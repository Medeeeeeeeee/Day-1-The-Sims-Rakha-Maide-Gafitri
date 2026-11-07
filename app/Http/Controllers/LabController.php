<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;

class LabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // ensure only supervisor users can access lab routes
        $this->middleware(function($request, $next) {
            if (! $request->user() || $request->user()->role !== 'supervisor') {
                abort(403);
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labs = Lab::orderBy('nama_lab')->get();
        return view('labs.index', compact('labs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('labs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lab' => ['required','string','max:255'],
            'pc_count' => ['required','integer','min:1'],
        ]);
        Lab::create($data);
        // always return to lab index; the form now lives there
        return redirect()->route('labs.index')->with('success','Lab created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lab = Lab::findOrFail($id);
        return view('labs.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lab = Lab::findOrFail($id);
        return view('labs.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lab = Lab::findOrFail($id);
        $data = $request->validate([
            'nama_lab' => ['required','string','max:255'],
            'pc_count' => ['required','integer','min:1'],
        ]);
        $lab->update($data);
        return redirect()->route('labs.index')->with('success','Lab updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lab = Lab::findOrFail($id);
        $lab->delete();
        return redirect()->route('labs.index')->with('success','Lab deleted');
    }
}
