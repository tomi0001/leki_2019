
<script type="text/javascript">

    var showAddressModal = function () {
        var button = $(this);
        var propertyID = button.attr('data-property-id');
        var propertySource = button.attr('data-property-source');
        $('input#suchen_stadt').val('');
        $('input#suchen_strasse').val('');
        $('input#suchen_haus_nr').val('');
//alert(encodeURI(ui.item.value));
        $('#suchen_stadt').autocomplete({
            source: '/includes/json_atlas.php?search=city',
            minLength: 2,
            select: function (event, ui) {

                $('#suchen_strasse').autocomplete({
                    //url: '/przykladowy/skrypt.php', type: 'post', data: JSON.stringify(adres)
                    //url: '/includes/json_atlas.php?search=street', type: 'post', data: city,



                    url: '/includes/json_atlas.php?search=street&city=' + encodeURI(ui.item.value),
                    minLength: 2,
                    select: function (event, ui) {

                        $('#suchen_haus_nr').autocomplete({
                            source: '/includes/json_atlas.php?search=hausnr&street=' + encodeURI(ui.item.value)
                            + '&city=' + encodeURI($('#suchen_stadt').val()),
                            minLength: 1,
                            select: function (event, ui) {

                                var addressID = ui.item.id;

                                $.ajax({
                                    source: '/includes/json_atlas.php?search=property&id=' + encodeURI(addressID),
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (property) {


                                        if (property.zipcode !== undefined && property.city !== undefined) {

                                            $.ajax({
                                                url: '/includes/json_atlas.php?action=licytacje',
                                                data: JSON.stringify(property),
                                                type: 'post',
                                                succes: function (html) {
                                                    
                                                }
                                              
                                            });

                                            $('button#modalAddressAdd')
                                                .prop('disabled', false)
                                                .click(function () {

                                                    $.ajax({
                                                        url: '/includes/json_atlas.php?action=save_address&id=' + propertyID + '&source=' + propertySource ,
                                                        type: 'post',
                                                        dataType: 'json',
                                                        data: JSON.stringify(property),
                                                        success: function (saved) {
                                                            if (saved.success === undefined || !saved.success) alert('Fehler! Bitte versuchen Sie es noch mal');
                                                            else {
                                                                $('#modalAddress').modal('hide');
                                                                $('#td-exact-address').html(
                                                                    property.street + ' ' + property.houseNumber + ', ' +
                                                                    property.zipcode + ' ' + property.city + ' ' +
                                                                    '<button type="button" id="modal-address-add" data-property-id="' + propertyID +
                                                                    '" data-property-source="' + propertySource + '" class="btn green btn-sm">Adresse aktualisieren</button>'
                                                                );
                                                                $('button#modal-address-add').click(showAddressModal);
                                                            }
                                                        },
                                                        error: function () {
                                                            alert('Fehler! Bitte versuchen Sie es noch mal');
                                                        }
                                                    })
                                                });
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

        $('#modalAddress').modal('show');
    };

    $('button#modal-address-add').click(showAddressModal);

</script>
