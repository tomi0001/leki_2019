<form method='get'>
<table class='table'>
    <tr>
        <td class='center'>Nazwa</td>
        
    
        <td class='center'><input type='text' class='form-control' value='{{$list->name}}' name="name"></td>
        
    </tr>
    <tr>
        <td class='center'>
            Kolor
        </td>
        <td class='center'>
            <select name='color' class='form-control form-control-lg'>
                @for ($i=1;$i <= 7;$i++)
                    @if ($i == $list->color and $list->color != "")
                    <option value='{{$i}}' selected>{{$i}}</option>
                    @elseif ($i == 1)
                    <option value=''>Brak koloru</option>
                    
                    @else
                    <option value='{{$i}}'>{{$i}}</option>
                    @endif
                    
                    
                @endfor
                
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="center">
            <input type="button" class="btn btn-success" value="Zmień" onclick="changeGroup('{{url('/ajax/changeGroup')}}',{{$list->id}})">
        </td>
    </tr>
    <tr>
        <td colspan="2" class="center">
            <div id="groupResult"></div>
        </td>
    </tr>
    
</table>
</form>
