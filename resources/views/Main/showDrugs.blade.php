<div id='addDrugs'>
    <body onload="hide_description({{$j}})">
    @php
    $i = 0;
    @endphp
    @foreach ($listDrugs as $list) 
    <div class="empty"></div>
    <div class='show_drugs{{$colorDrugs[$i]}}' id='titleDrugs{{$i}}'>
        <div class='title{{$colorDrugs[$i]}}' >
            
            {{$i+1}}
        </div>
        <div id="EditDrugs{{$i}}">
        <table class='table center'>
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
            @if ($equivalent[$i] != 0)
             <tr>
                <td class="center_danger" id='equivalent_tr_{{$i}}'>
                    Równoważnik diazepamu
                </td>
                <td class="center_danger">
                    <div id="equivalent_{{$i}}">{{$equivalent[$i]}}
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
                    </div>
                </td>
            </tr>
            <tr>
                <td class="center">
                    Przelicz na inną benzodiazepinę
                </td>
                <td class="center">
                    <form method='get' id='changebenzo{{$i}}}'>
                        <select name='benzo{{$i}}' class='form-control form-control-lg'>
                            @foreach ($benzo as $ben)
                            <option value='{{$ben->id}}'>{{$ben->name}}</option>
                            @endforeach
                        </select>
                        
                    
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <div class='center'> <input type='button' class='btn btn-success' onclick="calculateBenzo('{{ url('/ajax/sum_benzo')}}',{{$i}} ,{{$equivalent[$i]}})" value='Oblicz równoważnik'></div>
                </td>
                
            </tr>
         
                    
              
           
            </form>
            @endif    
           
        </table>
        </div>
        <div id='sumbenzo{{$i}}' class='center'></div>
        <table class="table center">
          
            <tr>
                <td class="center">
                    
                    
                    @if ($ifDescription[$i] == true)
                    <input type="button" class="btn btn-success" onclick="show_description({{$i}},'{{url('/ajax/show_description_submit')}}',{{$list->idDrugs}})" value="Pokaż opis">
                    @else
                    <input type="button" class="btn btn-danger" onclick="show_description()" value="Nie było opisu" disabled>
                    @endif
                </td>
                <td class="center">
                    <input type="button" class="btn btn-success" onclick="add_description({{$i}})" value="Dodaj opis">
                </td>
            </tr>
            <tr>
                <td class="center">
                    <input type="button" class="btn btn-success" onclick="sum_average('{{url('/ajax/sum_average')}}',{{$list->idDrugs}},{{$i}})" value="Oblicz średnią">
                </td>
                <td class="center">
                    <input type="button" class="btn btn-danger" onclick="delete_drugs('{{url('/ajax/delete_drugs')}}',{{$list->idDrugs}},{{$i}})" value="Usuń wpis">
                </td>
            </tr>
            <tr>
                <td class="center" colspan="2">
                    <div id="updateDrugs{{$i}}">
                        <input type="button" class="btn btn-success" onclick="edit_drugs('{{url('/ajax/edit_drugs')}}',{{$list->idDrugs}},{{$i}},'{{url('/ajax/update_drugs')}}','{{url('/ajax/show_update_drugs')}}')" value="Edytuj wpis">
                    </div>
                </td>
            </tr>
             <tr>
                <td class="center" colspan="2">
                    <div id="viewDrugs{{$i}}">
                        
                    </div>
                </td>
            </tr>
            <tr>
                <td class="center" colspan="2">
                    <div id="show_description{{$i}}"></div>
                    <div id="description{{$i}}">
                        <form method="get" id="adddescription">
                            <textarea name="descriptions{{$i}}" cols="25" rows="4"></textarea>
                            
                            <meta name="csrf-token" content="{{ csrf_token() }}" />
                            
                            <input type="button" class="btn btn-success" onclick="add_description_submit({{$i}},'{{ url('/ajax/addDescriptions')}}',{{$list->idDrugs}})" value="dodaj opis">
                        </form>
                        <div id="ajax_description_submit{{$i}}"></div>
                        
                    </div>
                </td>

                   
            
 
            
            
        </table>
        
         <div  class="sum_average" id='sum_average{{$i}}' style="overflow-y: scroll;  height:300;"></div>
    </div>
    @if ($separate[$i]["bool"] == true)
        <br><br><br>
    @endif
    
        @php
            $i++;
        @endphp
        
    @endforeach
    
    
</div>