/**
 * Created by bkapalamula on 01/10/2017.
 */

$( document ).ready(function()
{
    //initialise the document
    addEditForm();

    /**DATATABLE*/
    var d = new Date();
    var max_height=Math.round($(window).height()*0.54);
    $('.exampleTable').DataTable({
        language: {
            searchPlaceholder: 'Search records',
            sSearch: '',
            sLengthMenu: 'Show _MENU_',
            sLength: 'dataTables_length',
            oPaginate: {
                sFirst: '<i class="fa fa-chevron-left"></i>',
                sPrevious: '<i class="fa fa-chevron-left"></i>',
                sNext: '<i class="fa fa-chevron-right"></i>',
                sLast: '<i class="fa fa-chevron-right"></i>'
            }
        },

        scrollY:        max_height+"px",
        scrollCollapse: true,
        fixedHeader: true,
        colReorder: true,
        "initComplete": function(settings, json) {
            $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
        },


        "order": [],
        orderCellsTop: true,
        aLengthMenu: [[-1, 10, 25, 50, 100, 200, 300, 400, 500],[ "All", 10, 25, 50, 100, 200, 300, 400, 500]],
        iDisplayLength: 10,
        select: true
    });
    $('.dataTables_length select').addClass('browser-default');
});
//edit
function addEdit(str)
{
    var id=str.getAttribute('data-info');
    if(id=="create_record")
    {
        $("#hidden_action").val("add");
    }
    else
    {
        $("#hidden_id").val(id);
        $("#hidden_action").val("edit");
    }
    $("#hidden_form").submit();
}
function addEditForm()
{
    $("form#addEditForm").on("submit",function(e)
    {
        e.preventDefault();
        $("#addEditForm :input").prop("disabled", false); //enable some form elements which may be disabled
        var formData = new FormData($(this)[0]);
        var script_url=$("#script_url").val();
        $.ajax({
            url:script_url,
            type: 'POST',
            data: formData,
            async: false,
            complete: function() { },
            success: function (response)
            {
                showResponse(response);
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                showError(jqXHR);
            },
            cache: false,
            contentType: false,
            processData: false
        });

        return false;
    });
}
function deleteItem(str)
{
    var id=str.getAttribute('data-info');
    var script_url=$("#script_url").val();
    if (confirm('Are you sure you want to delete selected item?'))
    {
        // Yes delete
        $.ajax({
            url:script_url,/*Where data to be deleted*/
            type: 'POST',
            dataType: "html",
            data:
                {
                    action : "delete",
                    hidden_id: id
                },
            success: function (response)
            {
                showResponse(response)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showError(jqXHR);
            },
            async: true
        });
        return false;
    }
    else {
        // Do nothing!
    }
}
function showResponse(response) {
    var response_array=JSON.parse(response);
    alert(response_array.message);
    if(response_array.error_code=="1")
    {
        //reload the page
        window.location = window.location.href;
    }
}
function showError(jqXHR) {
    alert("Could not complete the request : "+jqXHR.responseText);
}