

//function addGroup() {

    function addGroup(url) {
        //var name = $("#name").val();
        //var name2 = name.replace(/ /g,"?");
        //var color = $("#color").val();
        var form = $("form#addGroupAction").serialize();
        $("#ajax_add_group").load(url + "?" + form);
        
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
        var description = $("textarea[name=description]").val();
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
        
            $("#show_description"+i).toggle();
            $("#show_description"+i).load(url + "?id=" + id);
        //}
        //else {
          //  $("#show_description"+i).hide();
        //}
        
        
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
        var description = $("textarea[name=descriptions" + i +  "]").val();
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