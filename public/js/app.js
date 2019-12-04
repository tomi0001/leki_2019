

//function addGroup() {

    function addGroup(url) {
        //var name = $("#name").val();
        //var name2 = name.replace(/ /g,"?");
        //var color = $("#color").val();
        var form = $("form#addGroupAction").serialize();
        $("#ajax_add_group").load(url + "?" + form);
        
    }
    function updateHash(url) {
        var form = $("form#Hash").serialize();
        $("#ajax_hash").load(url + "?" + form);
    }
    function generateHash() {
        var array = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','s','t','u','w','y','z','x','1','2','3','4','5','6','7','8','9','0'];
        //alert(array.length);
        var rand;
        var chr;
        var string = "";
        for (var i=0;i< 10;i++ ) {
           rand = parseInt(Math.random() * ((array.length  -1)  - 0) + 0);
           chr =  array[rand];
           string += chr;
        }
        
        $("#hash").val(string);
        
    }
    function addSubstances(url) {
        //var name = $("#name").val();
        //var group = $("#group").val();
        var form = $("form#addSubstancesAction").serialize();
        //alert('sadsd');
        $("#ajax_add_substances").load(url + "?" + form);
    }
    function addProduct(url) {
        
        var form = $("form#addProductAction").serialize();
        $("#ajax_add_product").load(url + "?" + form);
    }
    function saveDrugs(url) {
        //var form = $("form#addDrugsAction").serialize();
        //alert(form);
        //var data = $('form#addDrugsAction').serialize();
        var data = $('form#addDrugsAction').serialize();
        //alert(data);
            //data.push({name: 'wordlist', value: wordlist});
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        //alert("sdsf");
        //$("#ajax_add_drugs").post(url );
        //$.ajax({
           //url: url,
           //type: "post",
          // data: form
        //});
        //$.post(url, data);
        //$.get( url, { name: "John", time: "2pm" } );
        var name = $("select[name=name]").val();
        var dose = $("input[name=dose]").val();
        var description = escape($("textarea[name=description]").val());
        var date = $("input[name=date]").val();
        var time = $("input[name=time]").val();
        //alert(description);
         $.ajax({
            type: "POST", 
            url: url, 
            data: {_token: CSRF_TOKEN, name: name,dose: dose, description: description,date: date, time: time},
            datatype: "JSON",
 
                
                success : function(data) {
                //alert( "sukces");
                $("#ajax_add_drugs").html(data);
                },
                 
                 
                
                error: function(blad) {
                    //alert( "Wystąpił błąd");
                    //console.log(blad); /*Funkcja wyświetlająca informacje o ewentualnym błędzie 
                    //w konsoli przeglądarki
                }
        });
        
  //      $.post(url, form );
        
//        alert("ss");
        //$.post( 
                  //url,
                  //{ form: "sdd" },
                  //function(data) {
                    // $('#ajax_add_drugs').html("data");
                  //}
               //);
    //$.post( url, function( form ) {
      //$( "#ajax_add_drugs" ).html( form );
    //});
    }
    
    function show_description(i,url,id) {
        //$("#show_description"+i)
        //if (!$("#show_description"+i)) {
        //alert("d");
            $("#show_description"+i).toggle();
            $("#show_description"+i).load(url + "?id=" + id);
        //}
        //else {
          //  $("#show_description"+i).hide();
        //}
        
        
    }
    
    
    /*
     * 
     * @param {/ajax/edit_drugs} url
     * @param {type} i
     * @param {type} id
     * @param {/ajax/update_drugs} url2
     * @param {/ajax/show_update_drugs} url3
     * @param {/ajax/closeForm} url4
     * @returns {undefined}
     * 
     * 
     */
    function closeForm(url,i,id,url2,url3,url4) {
        //alert(id);
        $("#EditDrugs"+i).load(url4 + "?id=" + id + "&i=" + i);
        $("#updateDrugs"+i).html("<input type='button' class='btn btn-success' onclick=edit_drugs('" + url + "'," + id +  "," + i  + ",'" + url2 + "','" + url3 + ",2') value='Edytuj wpis'>");
    }
    function edit_drugs(url,idDrugs,i,url2,url3,bool = false) {
        $("#EditDrugs"+i).load(url + "?idDrugs=" + idDrugs + "&i=" + i);
        //if (bool != true) {
            $("#updateDrugs"+i).html("<input type='button' class='btn btn-success' onclick=update_drugs('"+ url +  "'," + idDrugs + "," +  i + ",'" + url2 + "','" + url3 + "') value='Uaktualnij wpis'>");
        //}
        //else if (bool == 2) {
//            $("#updateDrugs"+i).html("<input type='button' class='btn btn-success' onclick=update_drugs('"+ url3 +  "'," + idDrugs + "," +  i + ",'" + url + "','" + url3 + "') value='Uaktualnij wpis'>");
        //}
        //else {
  //          $("#updateDrugs"+i).html("<input type='button' class='btn btn-success' onclick=update_drugs('"+ url2 +  "'," + idDrugs + "," +  i + ",'" + url + "','" + url2 + "') value='Uaktualnij wpis'>");
        //}
        $("#viewDrugs"+i).html("");
    }
    function update_drugs(url,id,i,url2,url3) {
        var name = $("#nameProduct").val();
        var portion = $("#portion").val();
        var date = $("#date").val();
        var time = $("#time").val();
        var result = $("#viewDrugs"+i).load(url2 + "?id=" + id + "&nameProduct=" + name + "&portion=" + portion + "&date=" + date + "&time=" + time,function() {
          if (result.text() == "Pomyslnie dodano")  {
              $("#EditDrugs"+i).load(url3 + "?id=" + id + "&i=" + i);
              //$("#EditDrugs"+i).text("ss");
              
              //alert(url2);
              $("#updateDrugs"+i).html("<input type='button' class='btn btn-success' onclick=edit_drugs('" + url + "'," + id +  "," + i  + ",'" + url2 + "','" + url3 + "',true) value='Edytuj wpis'>");
          }
        });
        //if (result == "Pomyslnie dodano") {
          //  alert(result);
        //}
        //$("#EditDrugs"+i).load(url2 + "?id=" + id);
    }
    function changePassword(url) {
        var new_password = $("input[name=password_new]").val();
        var new_password2 = $("input[name=password_new2]").val();
        var old_password = $("input[name=password_old]").val();
        var start_day = $("input[name=start_day]").val();
        $("#changeSetting").load(url + "?password_old=" + old_password + "&password_new=" + new_password + "&password_new2=" + new_password2 + "&start_day=" + start_day);
        
    }
    function changeGroup(url,id) {
        var color = $("select[name=color]").val();
        var name = escape($("input[name=name]").val());
        
        $("#groupResult").load(url + "?id=" + id + "&color=" + color + "&name=" + name);
        //alert(color);
        
    }
    function EditGroup(url) {
        var id = $("select[name=group]").val();
        if (id != "") {
            $("#ajax_editGroup").load(url + "?id=" + id);
        }
    }
    function EditSubstance(url) {
        var id = $("select[name=substance]").val();
        if (id != "") {
            $("#ajax_editSubstance").load(url + "?id=" + id);
        }
    }
    function EditProduct(url) {
        var id = $("select[name=product]").val();
        if (id != "") {
            $("#ajax_editProduct").load(url + "?id=" + id);
        }
        
    }
    function changeProduct(url,id) {
        $("#productResult").load(url + "?" +  $( "form" ).serialize() + "&id_sub=" + id);
    }
    function changeSubstance(url,id) {
               /*         var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var data = $('form#changeSubstance').serialize();
        //alert("sdsf");
        //$("#ajax_add_drugs").post(url );
        //$.ajax({
           //url: url,
           //type: "post",
          // data: form
        //});
        //$.post(url, data);
        //$.get( url, { name: "John", time: "2pm" } );
        //var description = escape($("textarea[name=descriptions" + i +  "]").val());
        var id = $("checkbox[name=id]").val();
        var name = $("input[name=name]").val();
        //var description = $("textarea[name=description]").val();
        //var date = $("input[name=date]").val();
        //var time = $("input[name=time]").val();
           $.ajax({
            type: "GET", 
            url: url, 
            data: { _token: CSRF_TOKEN, id: id,name: name},
            datatype: "JSON",
 
                
                success : function(data) {
                //alert( "sukces");
                $("#substanceResult").html(data);
                },
                        error: function(blad) {
                    //alert( "Wystąpił błąd");
                    //console.log(blad); /*Funkcja wyświetlająca informacje o ewentualnym błędzie 
                    //w konsoli przeglądarki
                }
            });
            */
           
        $("#substanceResult").load(url + "?" +  $( "form" ).serialize() + "&id_sub=" + id);
        
    
    }
    function hide_description(i) {
        
        for (var j = 0;j <= i;j++) {
            $("#description"+j).hide();
            $("#show_description"+j).hide();
            $("#sum_average"+j).hide();
            
        }
    }
    function add_description(i) {
        $("#description"+i).toggle();
    }
    function delete_drugs(url,idDrugs,i) {
        var con = confirm("Czy napewno usunąć");
        if (con == true) {
            $("#titleDrugs"+i).load(url + "?id=" + idDrugs);
            $("#titleDrugs"+i).remove();
        }
        
    }
    function sum_average(url,idDrugs,i) {
         $("#sum_average"+i).toggle();
         //alert("d");
         $("#sum_average"+i).load(url + "?id=" + idDrugs);
        
    }
    function sum_average2(url,idDrugs,i,date1,date2) {
        $("#sum_average"+i).toggle();
         //alert("d");
         $("#sum_average"+i).load(url + "?id=" + idDrugs + "&date1=" + date1 + "&date2=" + date2 );
    }
    function calculateBenzo(url,i,equivalent) {
        //var data = $('form#changebenzo'+i).serialize();
        var name = $("select[name=benzo" + i +  "]").val();
        //alert(name);
        $("#sumbenzo"+i).load(url + "?id=" + name  + "&equivalent=" + equivalent);
        
    }
    function add_description_submit(i,url,id_use) {
        //alert("dobrze");
        
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var data = $('form#adddescription').serialize();
        //alert("sdsf");
        //$("#ajax_add_drugs").post(url );
        //$.ajax({
           //url: url,
           //type: "post",
          // data: form
        //});
        //$.post(url, data);
        //$.get( url, { name: "John", time: "2pm" } );
        var description = escape($("textarea[name=descriptions" + i +  "]").val());
        //var id_use = $("hidden[name=id_use]").val();
        //var dose = $("input[name=dose]").val();
        //var description = $("textarea[name=description]").val();
        //var date = $("input[name=date]").val();
        //var time = $("input[name=time]").val();
           $.ajax({
            type: "POST", 
            url: url, 
            data: {_token: CSRF_TOKEN, description: description,id_use: id_use},
            datatype: "JSON",
 
                
                success : function(data) {
                //alert( "sukces");
                $("#ajax_description_submit"+i).html(data);
                },
                 
                 
                
                error: function(blad) {
                    //alert( "Wystąpił błąd");
                    //console.log(blad); /*Funkcja wyświetlająca informacje o ewentualnym błędzie 
                    //w konsoli przeglądarki
                }
        });
        
    }