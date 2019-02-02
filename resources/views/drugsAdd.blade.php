@extends('Layout.pageMain')
@section('content')
<div id="add_drugs">
    <div class="title">

            
            DODAJ NOWĄ GRUPĘ
           
    </div>
     <form method="get" id='addGroupAction'>
                <table class="table center">
                    <tr>
                        <td>
                            Nazwa grupy
                        </td>
                        <td>
                            <input type="text" name="name" class="form-control">
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Kolor dla grupy
                        </td>
                        <td>
                            <select name="color" class="form-control form-control-lg">
                                <option value="" selected>Brak koloru</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                
                            </select>
                        </td>
                        
                    </tr>
                    <tr>

                        <td colspan="2" class="center">
                            <input type="button" class="btn btn-success" onclick="addGroup('{{url('/ajax/addGroup')}}')" value="Dodaj grupę">
                        </td>
                        
                    </tr>
                </table>
                <div id="ajax_add_group" class='ajax'>
                    
                </div>
                
                
      </form>
</div>
    


<div id="add_drugs">
    <div class="title">

            
            DODAJ NOWĄ SUBSTANCJE
           
    </div>
     <form method="get" id='addSubstancesAction'>
                <table class="table center">
                    <tr>
                        <td>
                            Nazwa substancji
                        </td>
                        <td>
                            <input type="text" name="name" class="form-control">
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Grupy, które należą<br> do tej substancji
                        </td>
                        <td>
                            <div style="overflow-y: scroll;  height:150; ">
                                <table width='100%' class='color'>
                                @foreach ($listGroup as $list)
                                <tr>
                                <td>
                                    <input type='checkbox' name='group[]' class='form-control' value='{{$list->id}}'>
                                </td>
                                <td>
                                    {{$list->name}}
                                  </td>
                                </tr>
                                @endforeach
         
                                
                                </table>
                                
                            </div>
                        </td>
                        
                    </tr>
                    <tr>
                                
                                <td>
                                    Równoważnik jeżeli jest <br> to benzodiazepina
                                  </td>
                                  <td>
                                    <input type='text' name='equivalent' class='form-control'>
                                </td>
                    </tr>
                    <tr>

                        <td colspan="2" class="center">
                            <input type="button" class="btn btn-success" onclick="addSubstances('{{url('/ajax/addSubstances')}}')" value="Dodaj substancje">
                        </td>
                        
                    </tr>

                </table>
                <div id="ajax_add_substances" class='ajax'>
                    
                </div>
                
                
      </form>
</div>
<div id="add_drugs">
        <div class="title">

            
            DODAJ NOWY PRODUKT
           
        </div>
      <form method="get" id='addProductAction'>
                <table class="table center">
                    <tr>
                        <td>
                            Nazwa produktu
                        </td>
                        <td>
                            <input type="text" name="name" class="form-control">
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Ile ma procent <br> (w przypadku napoju alkholowego)
                        </td>
                        <td>
                                <input type='text' name='percent' class='form-control'>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Rodzaj porcji
                        </td>
                        <td>
                            <select name="portion" class="form-control form-control-lg">
                                <option value="1" selected>Mg</option>
                                <option value="2">Mililitry</option>
                                <option value="3">ilości</option>
                                
                                
                            </select>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Cena
                        </td>
                        <td>
                                <input type='text' name='price' class='form-control'>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            Za ile
                        </td>
                        <td>
                                <input type='text' name='how' class='form-control'>
                        </td>
                        
                    </tr>
                     <tr>
                        <td>
                            Substancje, które należą<br> do tego produktu
                        </td>
                        <td>
                            <div style="overflow-y: scroll;  height:150; ">
                                <table width='100%' class='color'>
                                @foreach ($listSubstance as $list)
                                <tr>
                                <td>
                                    <input type='checkbox' name='substance[]' class='form-control' value='{{$list->id}}'>
                                </td>
                                <td>
                                    {{$list->name}}
                                  </td>
                                </tr>
                                @endforeach
         
                                
                                </table>
                                
                            </div>
                        </td>
                        
                    </tr>
                    <tr>

                        <td colspan="2" class="center">
                            <input type="button" class="btn btn-success" onclick="addProduct('{{url('/ajax/addProduct')}}')" value="Dodaj produkt">
                        </td>
                        
                    </tr>
                </table>
                <div id="ajax_add_product" class='ajax'>
                    
                </div>
                
                
      </form>
</div>
@endsection