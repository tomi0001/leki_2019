<div class='show_sum_drugs'>
        <div class='title' >
            LISTA SUBSTANCJI BRANA TEGO DNIA
        </div>
    <table class='table showSumDrugs'>
@foreach ($listSum as $list)
<tr>
    <td>{{$list->name}}</td>
    <td>{{$list->portion}}</td>
    @if($list->type == 1)
    <td>Mg</td>
    @elseif ($list->type == 2)
    <td>Mililtry</td>
    @else
    <td>Ilości</td>
    @endif
</tr>
    
@endforeach
@if ($sumAlkohol != 0)
    <tr>
        <td class='center_danger'>
            Ilośc wypitego alkoholu
        </td>
        <td class='center_danger'>
            {{$sumAlkohol}}
        </td>    
        <td class='center_danger'>
            Militry
        </td>
    </tr>
@endif
@if ($allEquivalent != 0)
    <tr>
        <td class='center_danger'>
           Równoważnik dzienny diazepamu
        </td>
        <td class='center_danger'>
            {{$allEquivalent}}
        </td>    
        <td class='center_danger'>
            
        </td>
    </tr>
            <tr>
                <td class="center">
                    Przelicz na inną benzodiazepinę
                </td>
                <td class="center">
                    <form method='get' id='changebenzoall'>
                        <select name='benzoall' class='form-control form-control-lg'>
                            @foreach ($benzo as $ben)
                            <option value='{{$ben->id}}'>{{$ben->name}}</option>
                            @endforeach
                        </select>
                        
                    
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <div class='center'> <input type='button' class='btn btn-success' onclick="calculateBenzo('{{ url('/ajax/sum_benzo')}}','all' ,{{$allEquivalent}})" value='Oblicz równoważnik'></div>
                </td>
                
            </tr>

@endif
    </table>
    <div id='sumbenzoall' class='center'></div>
</div>