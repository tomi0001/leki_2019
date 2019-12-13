@extends('Dr.Layout.pageMain')
@section('content')
<body onload='hide_description(10)'>
<br><br>

    <div id="addDrugs">

    @foreach ($listSearch as  $list)
        
   
            
            
        
        <div class='show_drugs{{$colorDrugs[$i]}}' id='titleDrugs{{$i}}'>
            
        <div class='title{{$colorDrugs[$i]}}' >
            
            {{$i+1}}
        </div>
           
        <table class='table center'>
            <tr>
                <td class="center">
                    Nazwa produktu
                </td>
                <td class="center">
                    {{$list->products}}
                    
                    
                    
                </td>
            </tr>
            <tr>
                <td class="center" colspan='2'>
                    <input type="button" class="btn btn-success" onclick="sum_average2('{{url('/ajax/sum_average2')}}',{{$list->id2}},{{$i}},'{{Input::get('dateStart')}}','{{Input::get('dateEnd')}}')" value="Oblicz średnią">
                </td>
                
            </tr>

            <tr>
                <td colspan='2'>
                    <div id='sum_average{{$i}}' style="overflow-y: scroll;  height:300;">
                       
                    </div>
                </td>
            </tr>
        </table>
        </div>
    <br>
    @php
        $i++;
    @endphp
    @endforeach
    
    </div>
    
<div class="paginate">
{{$listSearch->appends(['dateStart'=>Input::get('dateStart')])
                ->appends(['dateEnd'=>Input::get("dateEnd")])
                ->links()}}
                
</div>
@endsection