<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .logo img {
            max-height: 60px;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f3f3f3;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <div class="logo">
        @if($empresa?->logotipo)
            <img src="{{ public_path($empresa->logotipo) }}">
        @endif

    </div>

    <div>
        <strong>{{ $empresa->nome }}</strong><br>
        {{ $empresa->morada }}<br>
        {{ $empresa->codigo_postal }} {{ $empresa->localidade }}<br>
        NIF: {{ $empresa->nif }}
    </div>
</div>

{{-- Info Proposta --}}
<div class="box">
    <strong>Proposta Nº {{ $proposta->numero }}</strong><br>
    Data: {{ $proposta->data_proposta->format('d/m/Y') }}<br>
    Validade: {{ $proposta->validade->format('d/m/Y') }}
</div>

{{-- Cliente --}}
<div class="box">
    <strong>Cliente</strong><br>
    {{ $proposta->cliente->nome }}<br>
    NIF: {{ $proposta->cliente->nif }}<br>
    {{ $proposta->cliente->morada }}
</div>

{{-- Linhas --}}
<table>
    <thead>
        <tr>
            <th>Artigo</th>
            <th>Qtd</th>
            <th>Preço</th>
            <th>IVA</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proposta->linhas as $linha)
            <tr>
                <td>{{ $linha->artigo->nome }}</td>
                <td class="text-right">{{ $linha->quantidade }}</td>
                <td class="text-right">{{ number_format($linha->preco_unitario, 2) }} €</td>
                <td class="text-right">{{ $linha->iva_percentagem }}%</td>
                <td class="text-right">{{ number_format($linha->total, 2) }} €</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Total --}}
<div class="text-right total" style="margin-top: 20px;">
    Total: {{ number_format($proposta->total, 2) }} €
</div>

</body>
</html>
