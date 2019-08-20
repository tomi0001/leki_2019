<table class='table center'>
    @foreach ($listDrugs as $list)
            <tr>
                <td class="center">
                    Nazwa produktu
                </td>
                <td class="center">
                    {{$list->name}}
                    
                </td>
            </tr>
             <tr>
                <td class="center">
                    Dawka produktu
                </td>
                <td class="center">
                    {{$list->portion}} 
                    @switch ($list->type)
                    @case ($list->type == 1) mg
                    @break
                    @case ($list->type == 2) mililtry
                    @break
                    @default ilości
                    @endswitch
                </td>
            </tr>
             <tr>
                <td class="center">
                    Data wzięcia
                </td>
                <td class="center">
                    {{$list->date}} 

                </td>
            </tr>
             <tr>
                <td class="center">
                    Wydałeś na to
                </td>
                <td class="center">
                    {{$list->price}} 

                </td>
            </tr>
            @if ($list->percent != 0)
             <tr>
                <td class="center_danger">
                    Ilośc wypitego alkoholu
                </td>
                <td class="center_danger">
                    {{$list->percent}} 
                    @switch ($list->type)
                    @case ($list->type == 1)
                        Mg
                    @break
                    @case ($list->type == 2)
                        Militry
                    @break
                    @default
                        ilości
                    @endswitch
                    

                </td>
            </tr>
            @endif
            @if ($equivalent[0] != 0)
             <tr>
                <td class="center_danger">
                    Równoważnik diazepamu
                </td>
                <td class="center_danger">
                    {{$equivalent[0]}}
                    @switch ($list->type)
                        @case ($list->type == 1)
                        Mg
                        @break
                        @case ($list->type == 2)
                        Militry
                        @break
                        @default
                        ilości
                    
                    
                    @endswitch

                </td>
            </tr>
            <tr>
                <td class="center">
                    Przelicz na inną benzodiazepinę
                </td>
                <td class="center">
                    <form method='get' id='changebenzo{{$i}}'>
                        <select name='benzo{{$i}}' class='form-control form-control-lg'>
                            @foreach ($benzo as $ben)
                            <option value='{{$ben->id}}'>{{$ben->name}}</option>
                            @endforeach
                        </select>
                        
                    
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <div class='center'> <input type='button' class='btn btn-success' onclick="calculateBenzo('{{ url('/ajax/sum_benzo')}}',{{$i}} ,{{$equivalent[0]}})" value='Oblicz równoważnik'></div>
                </td>
                
            </tr>
         
                    
              
           
            </form>
            @endif   
            
           @endforeach
        </table>