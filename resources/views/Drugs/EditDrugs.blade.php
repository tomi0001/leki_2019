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

            
            WYGENERUJ HASH
           
    </div>
     <form method="get" id='Hash'>
                <table class="table center">
                    <tr>
                        <td>
                            Czy można się logować za pomocą tego hasha
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            <select name="if_true" class="form-control form-control-lg" onChange="EditProduct('{{url('/Produkt/editProduct')}}')">
                                @if ($hash != null)
                                    @if ($hash->if_true == true)
                                    <option value=1 selected>Tak</option>
                                    <option value=0>Nie</option>
                                    @endif
                                    @if ($hash->if_true == false)
                                    <option value=0 selected>Nie</option>
                                    <option value=1>Tak</option>
                                    @endif
                                
                                @else
                                   <option value=0 selected>Nie</option>
                                    
                                    <option value=1>Tak</option>
                                @endif
                                                         </select>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Hash
                        </td>
                       
                    
                    
                    

                        <td  class="center">
                            @if ($hash != null)
                                <input type="text" name="hash" id="hash" value="{{$hash->hash}}" class="form-control">
                            @else
                                <input type="text" name="hash" id="hash"  class="form-control">
                            @endif
                        </td>
                        
                    </tr>
                    <tr>
                        <td  class="center" colspan="2">
                            <input type="button" onclick="generateHash()" class="btn btn-primary" value="Wygeneruj Hash">
                        </td>
                        
                    </tr>
                    <tr>
                        <td  class="center" colspan="2">
                            <input type="button" onclick="updateHash('{{url('/ajax/updateHash')}}')" class="btn btn-success" value="Zapisz zmiany">
                        </td>
                        
                    </tr>
                </table>
                <div id="ajax_hash" class='ajax'>
                    
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