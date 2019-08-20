<form method='get' id="changeSubstance">
<table class='table'>
    <tr>
        <td  class="center">
            Nazwa substancji
            
        </td>
        <td  class="center">
            <input type="text" name="name" value="{{$name}}" class="form-control">
            
        </td>
    </tr>
    <tr>
        <td colspan="2" class="center">
            <b>Grupy należące do tej substancji</b>
            
        </td>
    </tr>
    @for ($i=0;$i < count($list);$i++)
    <tr>
        <td class='center'>{{$list[$i][1]}}</td>
        
        @if ($list[$i][2] == true)
        <td class='center'><input type='checkbox' class='form-control' value='{{$list[$i][0]}}' name="id[]"  checked ></td>
        @else
        <td class='center'><input type='checkbox' class='form-control' value='{{$list[$i][0]}}' name="id[]" ></td>
        @endif
        
    </tr>
    @endfor
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <tr>
        <td colspan="2" class="center">
            <input type="button" class="btn btn-success" value="Zmień" onclick="changeSubstance('{{url('/ajax/changeSubstance')}}',{{$id}})">
        </td>
    </tr>
    <tr>
        <td colspan="2" class="center">
            <div id="substanceResult"></div>
        </td>
    </tr>
    
</table>
</form>
