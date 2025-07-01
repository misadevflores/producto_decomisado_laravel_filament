<h1>Decomiso</h1>
<style>
    table td{
        font-family: monospace;
        font-size: 13px;
       
    }
    table th{
        font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }
</style>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>N°</th>
            <th>Detalle</th>
            <th>Cant.</th>
            <th>Estado</th>
            <th>Observacion</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($seizures as $seizure)
            <tr>
                <td>{{ $loop->iteration }}</td> {{-- N° correlativo --}}

                <td>{{ $seizure->product->nombre }}</td>
                <td>{{ $seizure->quantity }}</td>
                <td>{{ $seizure->status_producto }}</td>
                <td>{{ $seizure->reason }}</td>
            </tr>
        @endforeach
    </tbody>
   
    
</table>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td width="50%" style="text-align: left; vertical-align: top;">
            <strong>Recibido por:</strong>
            @foreach ($seizures as $seizure)
                <p>{{ $seizure->recibido_por }}</p>
            @endforeach
            
        </td>
        <td width="50%" style="text-align: left; vertical-align: top;">
            <strong>Entregado por:</strong><br><br>
            Nombre: ________________________<br><br>
            CI: ____________________________<br><br>
            Parentesco: ____________________
        </td>
    </tr>
</table>