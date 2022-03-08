  
<!DOCTYPE html>
<html> 
<head>
    <title>Datatables Server Side Processing in Laravel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>       
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head> 
<body> 
    
<div class="container">
    <br />
    <h3 align="center">Datatables Server Side Processing in Laravel</h3>
    <br />
    <div align="right">
        <button type="button" name="add" id="add_data" class="btn btn-success btn-sm">Add</button>
    </div>
    <table id="teacher_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

<div id="teacherModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="teacher_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="header" class="modal-title">Add Data</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="output_message"></span>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="edit_id" id="edit_id" value="">
                    <input type="hidden" name="button_action" id="button_action" value="insert">
                    <input type="submit" name="action" id="action" class="btn btn-info" value="Add">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $('#teacher_table').DataTable({
            "processing":true,
            "serverside":true,
            "ajax":"{{route('ajaxdata.getdata')}}",
            "columns":[
                {"data":"first_name"},
                {"data":"last_name"},
                {"data":"action",searchable:false,sortable:false}
            ]
        });
        
        $('#add_data').click(function(){
            $('#teacherModal').modal('show');
            $('#teacher_form')[0].reset();
            $('#button_action').val('insert');
            $('#action').val('Add');
            $('#output_message').html('');
            $('.modal-title').text('Add Data');
        });

        $('#teacher_form').on('submit',function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url:"{{route('ajaxdata.postdata')}}",
                method:"POST",
                data:form_data,
                dataType:"json",
                success:function(data)
                {
                   if (data.error.length > 0) 
                    {
                        var error_message='';

                        for (var i = 0; i < data.error.length; i++) 
                        {
                            error_message+='<div class="alert alert-danger">'+data.error[i]+'</div>';
                        }

                        $('#output_message').html(error_message);
                    }
                    else
                    {
                        $('#output_message').html(data.success);
                        $('#teacher_form')[0].reset();
                        $('#button_action').val('insert');
                        $('#action').val('Add');
                        $('#teacher_table').DataTable().ajax.reload();
                        $('.modal-title').text('Add Data');
                        //$('#teacherModal').modal('hide');
                    }
                }
            });
        });

        $(document).on('click','.edit',function(){
            var id = $(this).attr('id');
            $.ajax({
                url:"{{route('ajaxdata.fetchdata')}}",
                mathod:"get",
                data: {teacher_id:id},
                dataType: "json",
                success:function(data)
                {
                    //alert(data.id);
                    $('#first_name').val(data.first_name);
                    $('#last_name').val(data.last_name);
                    $('#edit_id').val(data.id);
                    $('#teacherModal').modal('show');
                    $('#button_action').val('update');
                    $('.modal-title').text('Edit Data');
                    $('#action').val('Edit');

                }
            });
        });

        $(document).on('click','.delete',function(){
            var id = $(this).attr('id');
            if (confirm('Do you want to delete this?'))
            { 
                $.ajax({
                    url:"{{route('ajaxdata.removedata')}}",
                    method: "get",
                    data: {teacher_id:id},
                    success: function(data)
                    {
                        $('#teacher_table').DataTable().ajax.reload();
                        alert(data); 
                    }
                });
            } 
            else{
                return false;
            }
        });

    });
</script>

</body>
</html>

