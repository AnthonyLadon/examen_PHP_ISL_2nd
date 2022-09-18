//------------ Action de masse de l'admin ---------------------------
    $("#dropdown_action").change(function(){
        var action = $("#dropdown_action").val();

        if(action == "none"){
            $("#mass_action").attr("disabled","disabled");
        }else{
            $("#mass_action").removeAttr("disabled");
            if(action == "delete"){
                $("#mass_action").addClass("btn_open");
            }
        }
    });

//----------- Creation de modale box pour les suppressions d'enregistrement du coté admin ---------------------
    $('.btn_open').click(function(){
        openDialog($(this).attr('href'));
    });

    $(".delete_entry").click(function(event){
        event.preventDefault();
    });

    function openDialog(href){

        $("#dialog-confirm").dialog({
                resizable: false,
                modal: true,
                title: "Suppression",
                width:270,
                height:180,
                buttons: {
                    'Oui': function() {
                       window.location = href;
                    },
                    'Non': function() {
                        $(this).dialog('close');
                    }
                }

         });
    }

//---------------------------- Gestion de tabs du coté admin ---------------------------------
    $("#tabs").tabs();

//---------------------------- CKeditor ------------------------------------------------------
$('.editor').ckeditor();

//--------------------- Affichage type item - Creation de menu --------------------------------
$('.external').hide();
$('.entity').hide();
$('.loading').hide();

$('.choice-item').click(function(){
    var name = $(this).attr("name");

    if(name == "external"){
        $('.entity').hide();
        $('.external').toggle();
    }else{
        $('.external').hide();
        var name_entity = "";
        var path = $(".data-path-entity").attr("data-path");
        name_entity = $(this).attr("name");
        $('.loading').show();
        $.ajax({
            url: path,
            type: 'POST',
            dataType: 'json',
//            data: $('#form_menu').serialize(),
            data: {'name': name_entity},
            success: function(items){
                $('.entity-dropdown').html('');
                $.each(items, function(index, rows) {
                    $.each(rows, function(key,value){
                        $('.entity-dropdown').append('<option id="'+ value.id +'" value="'+ value.entity +'">'+  value.title +'</option>');
                    });
                });

                $('.loading').hide();
                $('.entity').toggle();
            },
            error: function(jqXHR, exception) {
                if (jqXHR.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (jqXHR.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (jqXHR.status == 500) {
                    alert('Internal Server Error [500].');
                } else if (exception === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (exception === 'timeout') {
                    alert('Time out error.');
                } else if (exception === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('Uncaught Error.\n' + jqXHR.responseText);
                }
            }
        });
    }

});

$(window).load(function() {

    var $container = $('#aiki_newsbundle_news_images');
    var $addLink = $('<a href="#" id="add_image" class="btn btn-default btn-sm">Ajouter un champ image</a>');
    $container.append($addLink);


    $addLink.click(function(e) {
        addImage($container);
        e.preventDefault();
        return false;
    });

    var index = $container.find(':input').length;


    if (index == 0) {
        addImage($container);
    } else {
        $container.children('div').each(function() {
            addDeleteLink($(this));
        });
    }

    function addImage($container) {
        var $prototype = $($container.attr('data-prototype').replace(/__name__label__/g, 'Image n°' + (index+1))
            .replace(/__name__/g, index));

        addDeleteLink($prototype);

        $container.append($prototype);

        index++;
    }

    function addDeleteLink($prototype) {
        $deleteLink = $('<a href="#" class="btn btn-sm btn-dark-red">Supprimer ce champ</a>');

        $prototype.append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault();
            return false;
        });
    }

});