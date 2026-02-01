<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f3f3; }
        .right { text-align: right; }
    </style>
</head>
<body>

<h2>Encomenda nº {{ $encomenda->numero }}</h2>

<p>
    <strong>Data:</strong> {{ \Carbon\Carbon::parse($encomenda->data)->format('d/m/Y') }}<br>
    <strong>Cliente:</strong> {{ $encomenda->entidade->nome }}
</p>

<table>
    <thead>
        <tr>
            <th>Artigo</th>
            <th class="right">Qtd</th>
            <th class="right">Preço</th>
            <th class="right">IVA</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($encomenda->linhas as $linha)
            <tr>
                <td>{{ $linha->artigo->nome }}</td>
                <td class="right">{{ $linha->quantidade }}</td>
                <td class="right">{{ number_format($linha->preco_unitario, 2) }} €</td>
                <td class="right">{{ $linha->iva_percentagem }}%</td>
                <td class="right">{{ number_format($linha->preco_total, 2) }} €</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h3 style="text-align:right; margin-top:15px;">
    Total: {{ number_format($encomenda->valor_total, 2) }} €
</h3>

</body>
</html>
