<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EntidadesController extends Controller
{
    public function index()
    {
        return Inertia::render('Entidades/Index', [
            'entidades' => Entidade::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Entidades/Create');
    }

public function store(Request $request)
{
    $data = $request->validate([
        'nif' => ['required', 'digits_between:9,9'],
        'nome' => ['required', 'string', 'max:255'],
        'email' => ['nullable', 'email'],
        'telefone' => ['nullable', 'digits_between:9,15'],
        'morada' => ['nullable', 'string'],
        'is_cliente' => ['boolean'],
        'is_fornecedor' => ['boolean'],
    ], [
        'nif.digits_between' => 'O NIF deve ter exatamente 9 números.',
        'telefone.digits_between' => 'O telefone deve conter apenas números.',
    ]);

    Entidade::create([
        ...$data,
        'numero' => (Entidade::max('numero') ?? 0) + 1,
    ]);

    return redirect()->route('entidades.index')
        ->with('success', 'Entidade criada com sucesso.');
}


    public function edit(Entidade $entidade)
    {
        return Inertia::render('Entidades/Edit', [
            'entidade' => $entidade,
        ]);
    }

    public function update(Request $request, Entidade $entidade)
    {
        $data = $request->validate([
            'nif' => 'required',
            'nome' => 'required',
            'email' => 'nullable|email',
            'telefone' => 'nullable',
            'morada' => 'nullable',
            'is_cliente' => 'boolean',
            'is_fornecedor' => 'boolean',
        ]);

        if (
            empty($data['is_cliente']) &&
            empty($data['is_fornecedor'])
        ) {
            return back()->withErrors([
                'tipo' => 'A entidade tem de ser Cliente, Fornecedor ou ambos.',
            ]);
        }

        $entidade->update($data);

        return redirect()->route('entidades.index');
    }

    public function destroy(Entidade $entidade)
    {
        $entidade->delete();
        return redirect()->route('entidades.index');
    }
}
