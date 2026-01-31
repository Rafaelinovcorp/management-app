<?php

namespace App\Http\Controllers;

use App\Models\Proposta;
use App\Models\PropostaLinha;
use App\Models\Entidade;
use App\Models\Artigo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\EmpresaSetting;


use App\Models\Encomenda;
use App\Models\EncomendaLinha;

use Barryvdh\DomPDF\Facade\Pdf;

class PropostasController extends Controller
{

    public function index()
    {
        $propostas = Proposta::with('cliente')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Propostas/Index', [
            'propostas' => $propostas,
        ]);
    }

    public function create()
    {
        $clientes = Entidade::where('is_cliente', true)->get();

        return Inertia::render('Propostas/Form', [
            'clientes' => $clientes,
            'proposta' => null,
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:entidades,id',
        ]);

        $numero = Proposta::max('numero') + 1;

        $proposta = Proposta::create([
            'numero' => $numero,
            'cliente_id' => $request->cliente_id,
            'validade' => Carbon::now()->addDays(30),
            'estado' => 'rascunho',
            'total' => 0,
        ]);

        return redirect()->route('propostas.edit', $proposta->id);
    }


    public function edit($id)
    {
        $proposta = Proposta::with([
            'cliente',
            'linhas.artigo',
            'linhas.fornecedor',
        ])->findOrFail($id);
    
        $clientes = Entidade::where('is_cliente', true)->get();
        $fornecedores = Entidade::where('is_fornecedor', true)->get();
        $artigos = Artigo::where('estado', 'ativo')->get();
    
        return Inertia::render('Propostas/Form', [
            'proposta' => $proposta,
            'clientes' => $clientes,
            'fornecedores' => $fornecedores,
            'artigos' => $artigos,
        ]);
    }


 
    public function update(Request $request, $id)
    {
        $proposta = Proposta::findOrFail($id);

        if ($proposta->estado === 'fechado') {
            abort(403, 'Proposta fechada não pode ser alterada.');
        }

        $request->validate([
            'cliente_id' => 'required|exists:entidades,id',
            'validade' => 'required|date',
        ]);

        $proposta->update([
            'cliente_id' => $request->cliente_id,
            'validade' => $request->validade,
        ]);

        return back()->with('success', 'Proposta atualizada.');
    }

   
    public function addLinha(Request $request, $id)
    {
        $proposta = Proposta::findOrFail($id);

        if ($proposta->estado === 'fechado') {
            abort(403);
        }

        $request->validate([
            'artigo_id' => 'required|exists:artigos,id',
            'quantidade' => 'required|integer|min:1',
            'preco_unitario' => 'required|numeric|min:0',
            'iva_percentagem' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $proposta) {
            $linha = new PropostaLinha([
                'artigo_id' => $request->artigo_id,
                'fornecedor_id' => $request->fornecedor_id,
                'quantidade' => $request->quantidade,
                'preco_unitario' => $request->preco_unitario,
                'preco_custo' => $request->preco_custo,
                'iva_percentagem' => $request->iva_percentagem,
            ]);

            $linha->calcularValores();

            $proposta->linhas()->save($linha);

            $proposta->recalcularTotais();
        });

        return back();
    }

  
    public function removeLinha($id)
    {
        $linha = PropostaLinha::findOrFail($id);
        $proposta = $linha->proposta;

        if ($proposta->estado === 'fechado') {
            abort(403);
        }

        $linha->delete();
        $proposta->recalcularTotais();

        return back();
    }

    public function fechar($id)
    {
        $proposta = Proposta::with('linhas')->findOrFail($id);

        if ($proposta->linhas->isEmpty()) {
            return back()->withErrors('A proposta tem de ter pelo menos uma linha.');
        }

        $proposta->update([
            'estado' => 'fechado',
            'data_proposta' => Carbon::now(),
        ]);

        return back()->with('success', 'Proposta fechada com sucesso.');
    }

    public function pdf($id)
    {
        $proposta = Proposta::with([
            'cliente',
            'linhas.artigo',
            'linhas.fornecedor',
        ])->findOrFail($id);

        if ($proposta->estado !== 'fechado') {
            abort(403, 'Só é possível gerar PDF de propostas fechadas.');
        }

    
        $empresa = EmpresaSetting::first();

        $pdf = Pdf::loadView('pdf.proposta', [
            'proposta' => $proposta,
            'empresa' => $empresa,
        ])->setPaper('a4');

        return $pdf->download("Proposta_{$proposta->numero}.pdf");
    }

    public function converterEmEncomenda($id)
    {
        $proposta = Proposta::with('linhas')->findOrFail($id);

        if ($proposta->estado !== 'fechado') {
            abort(403, 'Apenas propostas fechadas podem ser convertidas.');
        }

        return DB::transaction(function () use ($proposta) {

            $numero = Encomenda::max('numero') + 1;

            $encomenda = Encomenda::create([
                'numero' => $numero,
                'cliente_id' => $proposta->cliente_id,
                'estado' => 'rascunho',
                'total' => 0,
            ]);

            foreach ($proposta->linhas as $linha) {
                $novaLinha = new EncomendaLinha([
                    'artigo_id' => $linha->artigo_id,
                    'fornecedor_id' => $linha->fornecedor_id,
                    'quantidade' => $linha->quantidade,
                    'preco_unitario' => $linha->preco_unitario,
                    'iva_percentagem' => $linha->iva_percentagem,
                    'subtotal' => $linha->subtotal,
                    'total' => $linha->total,
                ]);

                $encomenda->linhas()->save($novaLinha);
            }

            $encomenda->recalcularTotais();

            return redirect()->route('encomendas.edit', $encomenda->id);
        });   
    }
}
