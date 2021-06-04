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
        ]
    });
    $('#upload_csv').on('submit', function(event){
        event.preventDefault();
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
                alert(err);
            }
        });
    });

    $('#reset-data').on('click',function(event){
        event.preventDefault();
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
                alert(err);
            }
        });
    });

} );