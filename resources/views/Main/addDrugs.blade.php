<div id="add_drugs">
    <div class="title0">

            
            DODAJ NOWY WPIS
           
    </div>
    <table class='table center'>
        <form method='post' id='addDrugsAction'>
        <tr>
            <td>
                Nazwe produktu
            </td>    
            <td>
                <select name='name'  class='form-control form-control-lg'>
                    @foreach ($list_product as $list)
                    <option value='{{$list->id}}'>{{$list->name}}</option>
                    @endforeach
                    
                </select>
            </td>
        </tr>
        <tr>
            <td>
                Dawka
            </td>    
            <td>
                <input type='text' name='dose' class='form-control'>
            </td>
        </tr>
        <tr>
            <td style='vertical-align: middle;'>
                Opis spożycia
            </td>    
            <td>
                <textarea  name='description' class='form-control'></textarea>
            </td>
        </tr>
        <tr>
            <td rowspan='2' style='vertical-align: middle;'>
                Data spożycia
            </td>    
            <td>
                <input type='time' name='time' class='form-control'>
            </td>
        </tr>
   
            <td>
                <input type='date' name='date' class='form-control'>
            </td>
        </tr>
        <tr>
           
            <td colspan='2' class='center'>
                <input type='button' class='btn btn-success' onclick=saveDrugs('{{url('/ajax/addDrugs')}}') value='Zapisz wpis'>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id='ajax_add_drugs'  class='ajax'></div>
            </td>
            
        </tr>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        </form>
        
    </table>
    
    
    
    
    
   
    
</div>