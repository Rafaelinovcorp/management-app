<?php

namespace App\Http\Controllers;

use App\Models\Encomenda;
use App\Models\EncomendaLinha;
use App\Models\Artigo;
use App\Models\Entidade;
use App\Models\Proposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmpresaSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use App\Http\Requests\StoreEncomendaRequest;
use App\Http\Requests\UpdateEncomendaRequest;

class EncomendaController extends Controller
{
    /* =======================
     *  INDEX
     * ======================= */
    public function index()
    {
        $encomendas = Encomenda::with('entidade')
            ->orderByDesc('data')
            ->get();

        return Inertia::render('Encomendas/Index', [
            'encomendas' => $encomendas,
        ]);
    }

    /* =======================
     *  CREATE
     * ======================= */
    public function create(Request $request)
    {
        $clientes = Entidade::clientes()->get();
        $artigos = Artigo::with('iva')->get();
    
        return Inertia::render('Encomendas/Create', [
            'clientes' => $clientes,
            'artigos' => $artigos,
            'propostaId' => $request->get('proposta_id'),
        ]);
    }

        /* =======================
     *  STORE
     * ======================= */
    public function store(Request $request)
    {
        $request->validate([
            'data_encomenda' => 'required|date',
            'entidade_id' => 'required|exists:entidades,id',
            'estado' => 'required|in:Rascunho,Fechado',
            'linhas' => 'required|array|min:1',
            'linhas.*.artigo_id' => 'required|exists:artigos,id',
            'linhas.*.quantidade' => 'required|integer|min:1',
            'linhas.*.preco_unitario' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $numero = Encomenda::max('numero') + 1;

            $encomenda = Encomenda::create([
                'numero' => $numero,
                'data' => $request->data_encomenda,
                'entidade_id' => $request->entidade_id,
                'estado' => $request->estado,
            ]);

            foreach ($request->linhas as $linha) {

                $artigo = Artigo::with('iva')->findOrFail($linha['artigo_id']);

                $subtotal = $linha['quantidade'] * $linha['preco_unitario'];
                $ivaValor = ($subtotal * $artigo->iva->percentagem) / 100;

                EncomendaLinha::create([
                    'encomenda_id' => $encomenda->id,
                    'artigo_id' => $artigo->id,
                    'fornecedor_id' => $linha['fornecedor_id'] ?? null,
                    'quantidade' => $linha['quantidade'],
                    'preco_unitario' => $linha['preco_unitario'],
                    'preco_total' => $subtotal + $ivaValor,
                    'iva_percentagem' => $artigo->iva->percentagem,
                    'iva_valor' => $ivaValor,
                ]);
            }

            $encomenda->recalcularTotal();
        });

        return redirect()->route('encomendas.index')
            ->with('success', 'Encomenda criada com sucesso.');
    }


    /* =======================
 *  UPDATE
 * ======================= */
    public function update(Request $request, Encomenda $encomenda)
    {
        if ($encomenda->isFechado()) {
            abort(403, 'Encomenda fechada não pode ser editada.');
        }

        $request->validate([
            'data_encomenda' => 'required|date',
            'entidade_id' => 'required|exists:entidades,id',
            'estado' => 'required|in:Rascunho,Fechado',
            'linhas' => 'required|array|min:1',
            'linhas.*.artigo_id' => 'required|exists:artigos,id',
            'linhas.*.quantidade' => 'required|integer|min:1',
            'linhas.*.preco_unitario' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $encomenda) {

            $encomenda->update([
                'data' => $request->data_encomenda,
                'entidade_id' => $request->entidade_id,
                'estado' => $request->estado,
            ]);

            
            $encomenda->linhas()->delete();

            foreach ($request->linhas as $linha) {

                $artigo = Artigo::with('iva')->findOrFail($linha['artigo_id']);

                $subtotal = $linha['quantidade'] * $linha['preco_unitario'];
                $ivaValor = ($subtotal * $artigo->iva->percentagem) / 100;

                EncomendaLinha::create([
                    'encomenda_id' => $encomenda->id,
                    'artigo_id' => $artigo->id,
                    'fornecedor_id' => $linha['fornecedor_id'] ?? null,
                    'quantidade' => $linha['quantidade'],
                    'preco_unitario' => $linha['preco_unitario'],
                    'preco_total' => $subtotal + $ivaValor,
                    'iva_percentagem' => $artigo->iva->percentagem,
                    'iva_valor' => $ivaValor,
                ]);
            }

            $encomenda->recalcularTotal();
        });

        return redirect()->route('encomendas.index')
            ->with('success', 'Encomenda atualizada com sucesso.');
    }


    /* =======================
     *  DESTROY
     * ======================= */
    public function destroy(Encomenda $encomenda)
    {
        if ($encomenda->isFechado()) {
            abort(403);
        }

        $encomenda->delete();

        return redirect()->back()
            ->with('success', 'Encomenda eliminada.');
    }

    /* =======================
     *  HELPERS
     * ======================= */

    private function gerarNumero(): string
    {
        $ultimo = Encomenda::orderByDesc('id')->first();

        $numero = $ultimo
            ? intval($ultimo->numero) + 1
            : 1;

        return str_pad($numero, 6, '0', STR_PAD_LEFT);
    }


public function pdf(Encomenda $encomenda)
{
    $encomenda->load(['entidade', 'linhas.artigo.iva']);

    $pdf = Pdf::loadView('pdf.encomenda', [
        'encomenda' => $encomenda,
        'empresa' => EmpresaSetting::first(),
    ]);

    return response()->make(
        $pdf->output(),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Encomenda_'.$encomenda->numero.'.pdf"',
        ]
    );
}


}
