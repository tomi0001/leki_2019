@extends('Layout.pageMain')
@section('content')
<body onload='hide_description(10)'>
    <div id="addDrugs">
        @if ($error != "")
        <div class="center">
            <span class="error">{{$error}}</span><br>
            <button class="btn btn-success" onclick="javascript:history.back()">Wstecz</button>
        </div>
        @endif
    @foreach ($listSearch as  $list)
        
            @if ($i == 0)
            <div class="title_search">{{$day[$i][0]}}</div>
            
            @elseif ($day[$i-1][0] != $day[$i][0])
            <div class="title_search">{{$day[$i][0]}}</div>
            @else
            <div class="empty"></div>
            @endif
            
            
        
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
                    {{$list->name}}
                    
                    
                    
                </td>
            </tr>
            <tr>
                <td class="center">
                    Data
                </td>
                <td class="center">
                    {{$list->date}} 
                    
                </td>
            </tr>
            <tr>
                <td class="center">
                    Dawka
                </td>
                <td class="center">
                    {{$list->portion}} {{$day[$i][4]}}
                    
                </td>
            </tr>
            @if ($inDay != "")
            <tr>
                <td class="center">
                    Dawka dobowa
                </td>
                <td class="center">
                    {{$list->por}} {{$day[$i][4]}}
                    
                </td>
            </tr>
            @endif
            <tr>
                <td class="center">
                    <a href="{{url('/Main')}}/{{$day[$i][1]}}/{{$day[$i][2]}}/{{$day[$i][3]}}"><input type='button' value="Idź do dnia" class="btn btn-success"></a>
                </td>
                <td class="center">
                    
                    @if ($list->description != "")
                    <input type='button' value="Pokaż opis" class="btn btn-success" onclick="show_description({{$i}},'{{url('/ajax/show_description_submit')}}',{{$list->id_usees}})">
                    @else
                    <input type='button' value="Nie było opisu"  disabled class="btn btn-danger">
                    @endif
                    
                </td>
            </tr>
            <tr>
                <td class="center" colspan="2">
                    <div id="show_description{{$i}}"></div>
                    
                </td>
            </tr>
        </table>
        </div>
    @php
        $i++;
    @endphp
    @endforeach
    
    </div>
<div class="paginate">
{{$listSearch->appends(['sort'=>Input::get('sort')])
                ->appends(['product'=>Input::get("product")])
                ->appends(['substances'=>Input::get("substances")])
                ->appends(['group'=>Input::get("group")])
                ->appends(['search'=>Input::get("search")])
                ->appends(['dose1'=>Input::get("dose1")])
                ->appends(['dose2'=>Input::get("dose2")])
                ->appends(['inDay'=>Input::get("inDay")])
                ->appends(['day'=>Input::get("day")])
                ->appends(['data1'=>Input::get("data1")])
                ->appends(['data2'=>Input::get("data2")])
                ->appends(['hour1'=>Input::get("hour1")])
                ->appends(['hour2'=>Input::get("hour2")])
                ->links()}}
                
</div>
@endsection