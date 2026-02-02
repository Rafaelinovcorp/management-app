<?php

namespace App\Http\Controllers;

use App\Models\FaturaFornecedor;
use App\Models\FaturaFornecedorLinha;
use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class FaturaFornecedorController extends Controller
{
    /* =======================
     * INDEX
     * ======================= */
    public function index()
    {
        $faturas = FaturaFornecedor::with('fornecedor')
            ->orderByDesc('data')
            ->get();

        return Inertia::render('Financas/FaturasFornecedor/Index', [
            'faturas' => $faturas,
        ]);
    }

    /* =======================
     * CREATE
     * ======================= */
    public function create()
    {
        $fornecedores = Entidade::fornecedores()->get();

        return Inertia::render('Financas/FaturasFornecedor/Create', [
            'fornecedores' => $fornecedores,
        ]);
    }

    /* =======================
     * STORE
     * ======================= */
    public function store(Request $request)
{
    $request->validate([
        'data_fatura' => 'required|date',
        'fornecedor_id' => 'required|exists:entidades,id',
        'estado' => 'required|in:Rascunho,Fechado',
        'linhas' => 'required|array|min:1',
        'linhas.*.descricao' => 'required|string',
        'linhas.*.quantidade' => 'required|integer|min:1',
        'linhas.*.preco_unitario' => 'required|numeric|min:0',
        'linhas.*.iva_percentagem' => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($request) {

        $numero = (FaturaFornecedor::max('numero') ?? 0) + 1;

        $fatura = FaturaFornecedor::create([
            'numero' => $numero,
            'data' => $request->data_fatura,
            'fornecedor_id' => $request->fornecedor_id,
            'estado' => $request->estado,
        ]);

        foreach ($request->linhas as $linha) {

            $subtotal = $linha['quantidade'] * $linha['preco_unitario'];
            $ivaValor = ($subtotal * $linha['iva_percentagem']) / 100;

            FaturaFornecedorLinha::create([
                'fatura_fornecedor_id' => $fatura->id,
                'descricao' => $linha['descricao'],
                'quantidade' => $linha['quantidade'],
                'preco_unitario' => $linha['preco_unitario'],
                'iva_percentagem' => $linha['iva_percentagem'],
                'iva_valor' => $ivaValor,
                'preco_total' => $subtotal + $ivaValor,
            ]);
        }

        $fatura->recalcularTotal();
    });

    return redirect()
        ->route('faturas-fornecedor.index')
        ->with('success', 'Fatura de fornecedor criada com sucesso.');
}

}
