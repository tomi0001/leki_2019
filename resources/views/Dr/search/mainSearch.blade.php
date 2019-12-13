
<div class='center search'>
    <div class='title0'>
        WYSZUKIWANIE SUBSTANCJI
    </div>
    <table class='table center'>
        <form method='get' action='{{ url('/ProduktDr/searchAction')}}'>
        <tr>
            <td width="40%">
                Nazwy produktów
            </td>
            <td>
                <input type='text' name='product' class='form-control' value="{{Input::old('product')}}">
            </td>
        </tr>
        <tr>
            <td>
                Nazwy substancji
            </td>
            <td>
                <input type='text' name='substances' class='form-control'  value="{{Input::old('substances')}}">
            </td>
        </tr>
        <tr>
            <td>
                Nazwy grup
            </td>
            <td>
                <input type='text' name='group' class='form-control'  value="{{Input::old('group')}}">
            </td>
        </tr>
        
        <tr>
            <td>
                Fraza
            </td>
            <td>
                <input type='text' name='search' class='form-control'  value="{{Input::old('search')}}">
            </td>
        </tr>
        <tr>
            <td>
                Dawka
            </td>
            <td>
               <div class="col-md-2">
                od
                </div>
               <div class="col-md-4">
                    <input  type='text' name='dose1' class='form-control'  value="{{Input::old('dose1')}}">
               </div>
                <div class="col-md-2">
                do 
                </div>
                <div class="col-md-4">
                    <input type='text' name='dose2' class='form-control'  value="{{Input::old('dose2')}}">
                </div>
                
            </td>
        </tr>
        <tr>
            <td>
                Dawka dobowa
            </td>
            <td>
                @if (Input::old('day') != "")
                <input type="checkbox" name="day" checked>
                @else
                <input type="checkbox" name="day">
                @endif
            </td>
        </tr>

        <tr>
            <td>
                Wyszukaj tylko te pozycje, które mają jakiś wpis
            </td>
            <td>
                @if (Input::old('inDay') != "")
                <input type="checkbox" name="inDay"  checked>
                @else
                <input type="checkbox" name="inDay">
                @endif
            </td>
        </tr>
        <tr>
            <td>
                Data od
            </td>
            <td>
              
                    <input type='date' name='data1' class='form-control' value="{{Input::old('data1')}}">
                
                
            </td>
        </tr>
        <tr>
            <td>
                Data do
            </td>
            <td>
              
                    <input type='date' name='data2' class='form-control' value="{{Input::old('data2')}}">
                
                
            </td>
        </tr>
        <tr>
            <td>
                Godzina
            </td>
            <td>
               <div class="col-md-2">
                od
                </div>
               <div class="col-md-4">
                    <input  type='number' name='hour1' class='form-control' value="{{Input::old('hour1')}}">
               </div>
                <div class="col-md-2">
                do 
                </div>
                <div class="col-md-4">
                    <input type='number' name='hour2' class='form-control' value="{{Input::old('hour2')}}">
                </div>
                
            </td>
        </tr>
        <tr>
            <td>
                Sortuj
            </td>
            <td>
              
                <select name="sort" class="form-control form-control-lg">
                    @if (Input::old("sort") == "date")
                    <option value="date" selected>Daty</option>
                    @else
                    <option value="date">Daty</option>
                    @endif
                    @if (Input::old("sort") == "portion")
                    <option value="portion" selected>Dawki</option>
                    @else
                    <option value="portion">Dawki</option>
                    @endif
                    @if (Input::old("sort") == "product")
                    <option value="product" selected>Produktu</option>
                    @else
                    <option value="product">Produktu</option>
                    @endif
                    @if (Input::old("sort") == "hour")
                    <option value="hour" selected>Godziny</option>
                    @else
                    <option value="hour" >Godziny</option>
                    @endif
                </select>
                
                
            </td>
        </tr>
        <tr>
            <td colspan="2" class="center">
                <input type="submit" class="btn btn-success" value="Szukaj">
            </td>
            
        </tr>
        </form>
         <form method='get' action='{{ url('/ProduktDr/selectDrugs')}}'>
        <TR>
            <td rowspan="2">
                <br>
                Wybierz leki z tych dat
            </td>
            <td>
                <input type="date" name="dateStart" class='form-control'>
            </td>
        </TR>
        <TR>
            
            <td>
                <input type="date" name="dateEnd" class='form-control'>
            </td>
        </TR>
        <tr>
            <td colspan="2"  class="center">
                <input type='submit' value='Szukaj' class='btn btn-success'>
            </td>
        </tr>
        <tr>
            <td colspan='2' class='center'>
                 
                @if (session('errorSelect') )
                <span class='error'>{{session('errorSelect')}}</span>
                @endif
            </td>
        </tr>
         </form>
    </table>
           
       
    
</div>