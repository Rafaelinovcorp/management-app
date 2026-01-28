<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientesController extends Controller
{
    public function index()
    {
        return Inertia::render('Clientes/Index', [
            'clientes' => Entidade::where('is_cliente', true)->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Clientes/Create');
    }

public function store(Request $request)
{
    $data = $request->validate([
        'nif' => 'required|unique:entidades,nif',
        'nome' => 'required',
        'morada' => 'nullable',
        'telefone' => 'nullable',
        'email' => 'nullable|email',
        'rgpd' => 'boolean',
    ]);

    // normalizar strings vazias para null (campos encrypted)
    foreach ($data as $k => $v) {
        if ($v === '') {
            $data[$k] = null;
        }
    }

    $data['rgpd'] = $request->boolean('rgpd');

    Entidade::create(array_merge($data, [
        'numero' => (Entidade::max('numero') ?? 0) + 1,
        'is_cliente' => true,
        'estado' => 'ativo',
    ]));

    return redirect()->route('clientes.index');
}




    public function edit(Entidade $cliente)
    {
        abort_if(!$cliente->is_cliente, 404);

        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente,
        ]);
    }

    public function update(Request $request, Entidade $cliente)
    {
        abort_if(!$cliente->is_cliente, 404);

        $data = $request->validate([
            'nif' => 'required|unique:entidades,nif,' . $cliente->id,
            'nome' => 'required',
            'morada' => 'nullable',
            'telefone' => 'nullable',
            'email' => 'nullable|email',
            'rgpd' => 'boolean',
        ]);

        foreach ($data as $k => $v) {
            if ($v === '') {
                $data[$k] = null;
            }
        }

        $data['rgpd'] = $request->boolean('rgpd');

        $cliente->update($data);

        return redirect()->route('clientes.index');
    }

    public function destroy(Entidade $cliente)
    {
        abort_if(!$cliente->is_cliente, 404);

        $cliente->delete();

        return redirect()->route('clientes.index');
    }
}
