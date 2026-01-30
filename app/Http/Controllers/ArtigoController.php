<?php

namespace App\Http\Controllers;

use App\Models\Artigo;
use App\Models\Iva;
use Illuminate\Http\Request;
use Inertia\Inertia;
class ArtigoController extends Controller
{
    public function index()
    {
        return Inertia::render('Artigos/Index', [
            'artigos' => Artigo::with('iva')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Artigos/Create', [
            'ivas' => Iva::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'referencia' => 'required|unique:artigos,referencia',
            'nome' => 'required',
            'descricao' => 'nullable',
            'preco' => 'required|numeric|min:0',
            'iva_id' => 'required|exists:ivas,id',
            'foto' => 'nullable|image|max:2048',
            'observacoes' => 'nullable',
            'estado' => 'required|in:Ativo,Inativo',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('artigos', 'public');
        }

        Artigo::create($data);

        return redirect()->route('artigos.index')
            ->with('success', 'Artigo criado com sucesso');
    }

    public function edit(Artigo $artigo)
    {
        return Inertia::render('Artigos/Edit', [
            'artigo' => $artigo,
            'ivas' => Iva::all(),
        ]);
    }

    public function update(Request $request, Artigo $artigo)
    {
        $data = $request->validate([
            'referencia' => 'required|unique:artigos,referencia,' . $artigo->id,
            'nome' => 'required',
            'descricao' => 'nullable',
            'preco' => 'required|numeric|min:0',
            'iva_id' => 'required|exists:ivas,id',
            'foto' => 'nullable|image|max:2048',
            'observacoes' => 'nullable',
            'estado' => 'required|in:Ativo,Inativo',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('artigos', 'public');
        }

        $artigo->update($data);

        return redirect()->route('artigos.index')
            ->with('success', 'Artigo atualizado com sucesso');
    }

    public function destroy(Artigo $artigo)
    {
        $artigo->delete();

        return redirect()->back()
            ->with('success', 'Artigo removido');
    }
}
