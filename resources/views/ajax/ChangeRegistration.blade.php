            `
            <div  align="center" style="width: 90%;"><div  class="close"><img src="{{asset('/image/images.png')}}" onclick="closeForm('{{url('/ajax/edit_drugs')}}',{{$i}},{{$idDrugs}},'{{url('/ajax/update_drugs')}}','{{url('/ajax/show_update_drugs')}}','{{url('/ajax/closeForm')}}')"></div></div>
<table class='table center'>
    
            <tr>
                <td class="center">
                    Nazwa produktu
                </td>
                <td class="center">
                    <select id="nameProduct" class="form-control form-control-lg">
                       @foreach ($listProduct as $list)
                        @if ($list->id == $id)
                        <option value="{{$list->id}}" selected>{{$list->name}}</option>
                        
                        @else
                        <option value="{{$list->id}}">{{$list->name}}</option>
                        @endif
                        
                       @endforeach
                    </select>
                    
                </td>
            </tr>
             <tr>
                <td class="center">
                    Dawka produktu
                </td>
               <td class="center">
                   <input type="text" id="portion" value="{{$portion}}" class="form-control">
                </td>
            </tr>
             <tr>
                <td class="center " rowspan="2" >
                    <div class="centerVertical">
                        Data wzięcia
                    </div>
                </td>
                <td class="center">
                    <input type="date" id="date" class="form-control" value="{{$date1}}">
                    

                </td>
            </tr>
             <tr>
           
                <td class="center">
                    <input type="time" id="time" class="form-control" value="{{$date2}}">

                </td>
            </tr>
           
     
         
                    
              
           
     
         
        </table>