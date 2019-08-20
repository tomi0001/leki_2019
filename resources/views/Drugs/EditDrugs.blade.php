@extends('Layout.pageMain')
@section('content')
<div id="add_drugs">
    <div class="title0">

            
            EDYTUJ ISTNIEJĄCE GRUPY
           
    </div>
     <form method="get" id='addGroupAction'>
                <table class="table center">
                    <tr>
                        <td>
                            Nazwa grupy
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <select name="group" class="form-control form-control-lg" onChange="EditGroup('{{url('/Produkt/editGroup')}}')">
                                <option value="" selected></option>
                                @foreach ($listGroups as $listGro)
                                    <option value="{{$listGro->id}}">{{$listGro->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        
                    </tr>
                </table>
                <div id="ajax_editGroup" class='ajax'>
                    
                </div>
                
                
      </form>
</div>
    
<div id="add_drugs">
    <div class="title0">

            
            EDYTUJ ISTNIEJĄCE SUBSTANCJE
           
    </div>
     <form method="get" id='addSubstanceAction'>
                <table class="table center">
                    <tr>
                        <td>
                            Nazwa Substancji
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <select name="substance" class="form-control form-control-lg" onChange="EditSubstance('{{url('/Produkt/editSubstance')}}')">
                                <option value="" selected></option>
                                @foreach ($listSubstance as $listSub)
                                    <option value="{{$listSub->id}}">{{$listSub->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        
                    </tr>
                </table>
                <div id="ajax_editSubstance" class='ajax' style="overflow-y: scroll;  height:300;">
                    
                </div>
                
                
      </form>
</div>
<div id="add_drugs">
    <div class="title0">

            
            EDYTUJ ISTNIEJĄCE PRODUKTY
           
    </div>
     <form method="get" id='addProductAction'>
                <table class="table center">
                    <tr>
                        <td>
                            Nazwa Produktu
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <select name="product" class="form-control form-control-lg" onChange="EditProduct('{{url('/Produkt/editProduct')}}')">
                                <option value="" selected></option>
                                @foreach ($listProduct as $listPro)
                                    <option value="{{$listPro->id}}">{{$listPro->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        
                    </tr>
                </table>
                <div id="ajax_editProduct" class='ajax' style="overflow-y: scroll;  height:300;">
                    
                </div>
                
                
      </form>
</div>
<div id="add_drugs">
    <div class="title0">

            
            EDYTUJ SWOJE USTAWIENIA
           
    </div>
     <form method="get" id='addGroupAction'>
                <table class="table center">
                    <tr>
                        <td>
                            Godzina rozpoczęcia dnia
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <input type="number" name="start_day" class="form-control" value="{{$start_day}}">
                        </td>
                        
                    </tr>
                    <tr>
                        <td colspan="2" class="center">
                            ZMIEŃ HASŁO
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Wpisz swoje stare hasło
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <input type="password" name="password_old" class="form-control">
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Wpisz nowe hasło
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <input type="password" name="password_new" class="form-control">
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Wpisz jeszcze raz nowe hasło
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <input type="password" name="password_new2" class="form-control">
                        </td>
                        
                    </tr>
                    <tr>
                        <td  class="center" colspan="2">
                            <input type='button' class="btn btn-success" onclick="changePassword('{{url ('/User/changePassword')}}')" value='Zmień ustawienia'>
                        </td>
                        
                    </tr>
                </table>
                <div id="changeSetting" class='ajax' >
                    
                </div>
                
                
      </form>
</div>

@endsection