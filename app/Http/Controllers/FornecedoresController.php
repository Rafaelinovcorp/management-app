<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use Illuminate\Http\Request;

class FornecedoresController extends Controller
{
    public function index()
    {
        return Entidade::where('is_fornecedor', true)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nif' => 'required|unique:entidades,nif',
            'nome' => 'required',
            'codigo_postal' => ['nullable', 'regex:/^\d{4}-\d{3}$/'],
        ]);

        $numero = (Entidade::max('numero') ?? 0) + 1;

        return Entidade::create([
            ...$request->all(),
            'numero' => $numero,
            'is_fornecedor' => true,
        ]);
    }

    public function show(Entidade $entidade)
    {
        abort_if(!$entidade->is_fornecedor, 404);
        return $entidade;
    }

    public function update(Request $request, Entidade $entidade)
    {
        abort_if(!$entidade->is_fornecedor, 404);

        $request->validate([
            'nif' => 'required|unique:entidades,nif,' . $entidade->id,
            'codigo_postal' => ['nullable', 'regex:/^\d{4}-\d{3}$/'],
        ]);

        $entidade->update($request->all());
        return $entidade;
    }

    public function destroy(Entidade $entidade)
    {
        abort_if(!$entidade->is_fornecedor, 404);

        $entidade->delete();
        return response()->noContent();
    }
}
