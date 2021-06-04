$(document).ready(function() {
    $('#example').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
	    "ajax": '../ajax/data',
        "columns": [
            { "data": "Name" },
            { "data": "age" },
            { "data": "dob" },
            { "data": "ReportingManager" },
            { "data": "Salary" },
            { "data": "Department" }
        ],
        "lengthMenu": [10, 25, 50, 100, 500]
    });
    $('#upload_csv').on('submit', function(event){
        event.preventDefault();
        $('.upload-loader').show();
        $.ajax({
            url:"../ajax/import",
            method:"POST",
            data:new FormData(this),
            dataType:'json',
            contentType:false,
            cache:false,
            processData:false,
            success:function(jsonData)
            {
                location.reload();
            },
            error:function(err){
                $('.upload-loader').hide();
                alert(err);
            }
        });
    });

    $('#reset-data').on('click',function(event){
        event.preventDefault();
        $('.clear-data-loader').show();
        $.ajax({
            url:"../ajax/resetdata",
            method:"POST",
            contentType:false,
            cache:false,
            success:function(jsonData)
            {
                location.reload();
            },
            error:function(err){
                $('.clear-data-loader').hide();
                alert(err);
            }
        });
    });
    $('#csv_file').on('change',function(){
        $('#upload').removeClass('disabledbtn').removeAttr('disabled');
    });

} );