<div id='addDrugs'>
    @php
    $i = 1;
    @endphp
    @foreach ($listDrugs as $list) 
    <div class='show_drugs'>
        <div class='title'>
            {{$i}}
        </div>
        <table class='table center'>
            <tr>
                <td>
                    Nazwa produktu
                </td>
                <td>
                    {{$list->name}}
                </td>
            </tr>
             <tr>
                <td>
                    Dawka produktu
                </td>
                <td>
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
                <td>
                    Data wzięcia
                </td>
                <td>
                    {{$list->date}} 

                </td>
            </tr>
             <tr>
                <td>
                    Wydałeś na to
                </td>
                <td>
                    {{$list->price}} 

                </td>
            </tr>
        </table>
        
    </div>
        @php
            $i++;
        @endphp
        
    @endforeach
    
    
</div>