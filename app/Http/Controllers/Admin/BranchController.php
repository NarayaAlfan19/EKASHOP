<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::paginate(15);
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'address' => 'required|string',
            'whatsapp_number' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
            'opening_hours' => 'nullable|string|max:100',
            'maps_url' => 'nullable|string',
        ]);

        Branch::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'is_active' => $validated['is_active'],
            'opening_hours' => $validated['opening_hours'] ?? '08:00 - 17:00',
            'maps_url' => $validated['maps_url'] ?? '-',
        ]);

        return redirect()->route('admin.cabang.index')->with('success', 'Cabang baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'address' => 'required|string',
            'whatsapp_number' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'is_active' => $validated['is_active'],
            'maps_url' => $request->maps_url ?? $branch->maps_url ?? '-', // Mempertahankan atau mengisi maps_url saat update
        ]);

        return redirect()->route('admin.cabang.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('admin.cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }
}