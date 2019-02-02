

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
        var form = $("form#addDrugsAction").serialize();
        $("#ajax_add_drugs").load(url + "?" + form);
        
    }