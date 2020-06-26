@extends('adminlte::page')

@section('title') 
    Role
@endsection
@section('css')
   
@stop
@section('content_header')
    <h1>Role List </h1>
@stop

@section('content')

    <div class="wraper container-fluid">
        
         <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><a class="btn btn-primary" href="{{route('role.create')}}">New Role</a></h3>
                <div class="box-tools pull-right">
                    <!--span class="label label-primary">Label</span-->
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="col-lg-12">
                    <table id="tbl_role" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ ('ID')}}</th>
                                <th>{{ ('Role name')}}</th>
                                <th>{{ ('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>  
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
           
        </div><!-- /.box -->
    </div>
@endsection

@section('js')
    
    <script type="text/javascript"> 
        $(document).ready(function(){      
            $('#tbl_role').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! route('role.list') !!}",
                columns: [
                    { data: 'id', name: 'id', orderable: false },
                    { data: 'name', name: 'name' },
                    { data: 'action', name:'action', orderable: false }
                ],
                "order": [[ 1, "asc" ]]
            });
        });

        function clickDestroy(item){
            var item_id  =   $(item).data('item');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: "No, cancel",
                closeOnConfirm: false,
                closeOnCancel: true
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{!! route('role.destroy') !!}",
                        type: "POST",
                        dataType: "html",
                        data: {item:item_id, _token:"{{ csrf_token() }}"},
                        success: function(result)
                        {
                            if(result=="success"){
                                window.location = "{!! route('role.index') !!}";
                            }else{
                                Swal.fire("Warning!", "This item is used by other or you don't have permission!", "error");
                            }
                        },
                        error:function(){
                            Swal.fire("Warning!", "This item is used by other or you don't have permission!", "error");
                        }
                    });
                    }
                });
            return true;
        }
    </script>
@stop
