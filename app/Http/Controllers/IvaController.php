<?php

namespace App\Http\Controllers;

use App\Models\Iva;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IvaController extends Controller
{
    public function index()
    {
        return Inertia::render('Iva/Index', [
            'ivas' => Iva::withCount('artigos')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Iva/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:50',
            'percentagem' => 'required|numeric|min:0|max:100',
        ]);

        Iva::create($data);

        return redirect()->route('iva.index');
    }

    public function edit(Iva $iva)
    {
        return Inertia::render('Iva/Edit', [
            'iva' => $iva,
        ]);
    }

    public function update(Request $request, Iva $iva)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:50',
            'percentagem' => 'required|numeric|min:0|max:100',
        ]);

        $iva->update($data);

        return redirect()->route('iva.index');
    }

    public function destroy(Iva $iva)
    {
        if ($iva->artigos()->exists()) {
            return back()->withErrors([
                'delete' => 'Este IVA está associado a artigos e não pode ser apagado.',
            ]);
        }

        $iva->delete();

        return redirect()->route('iva.index');
    }
}

