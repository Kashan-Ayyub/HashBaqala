@extends('components.layout')
@section('container')

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Users</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">User</li>
                    </ol>
                    <a href="{{route('dashboard.user.create')}}" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All User</h4>
                        <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6>
                        <div class="table-responsive m-t-40 border p-2">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#S.no</th>
                                        <th>Profile Image</th>
                                        <th>username</th>
                                        <th>email</th>
                                        <th>phone number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data) > 1)
                                    <?php $count = 1; ?>
                                    @foreach ($data as $item)
                                    
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td><img src="{{$item['user_image']}}" style="width: 139px;"></td>
                                            <td>{{$item['username']}}</td>
                                            <td>{{$item['email']}}</td>
                                            <td>{{$item['phone_number']}}</td>
                                            @if($item['status'] == 1)
                                                <td class="text-success">Active</td>
                                            @else
                                                <td class="text-danger">Non Active</td>
                                            @endif
                                            <td>
                                                <span>
                                                    <a href="{{route('dashboard.user.edit' , ['id' => $item['id']])}}"><i class="bi bi-pencil"></i></a>
                                                    <a href="{{route('dashboard.user.delete' , ['id' => $item['id']])}}">
                                                        <i class="mx-3 bi bi-trash3"  onclick=" return confirm('Are You Sure?')"></i>
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php $count++; ?>
                                    @endforeach
                                    @elseif(count($data) == 0)
                                    <tr><td class="text-center" colspan="7">No Data Found.</td></tr>
                                    @else
                                        <tr>
                                            <td>1</td>
                                            <td><img src="{{$data[0]['user_image']}}" style="width: 139px;"></td>
                                            <td>{{$data[0]['username']}}</td>
                                            <td>{{$data[0]['email']}}</td>
                                            <td>{{$data[0]['phone_number']}}</td>
                                            @if($data[0]['status'] == 1)
                                                <td class="text-success">Active</td>
                                            @else
                                                <td class="text-danger">Non Active</td>
                                            @endif
                                            <td>
                                                <span>
                                                    <a href="{{route('dashboard.user.edit' , ['id' => $data[0]['id']])}}"><i class="bi bi-pencil"></i></a>
                                                    <a href="{{route('dashboard.user.delete' , ['id' => $data[0]['id']])}}"><i class="mx-3 bi bi-trash3"></i></a>
                                                   
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </div>
</div>
    <script src="{{url('assets/node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
 
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{url('dist/js/perfect-scrollbar.jquery.min.js')}}"></script>

    <!--stickey kit -->
    <script src="{{url('assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{url('assets/node_modules/sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- This is data table -->
    <script src="{{url('assets/node_modules/datatables/jquery.dataTables.min.js')}}"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- end - This is for export functionality only -->
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "columnDefs": [{
                    "visible": false,
                    "targets": 2
                }],
                "order": [
                    [2, 'asc']
                ],
                "displayLength": 25,
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                }
            });
            // Order by the grouping
            $('#example tbody').on('click', 'tr.group', function() {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });
    });
    $('#example23').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    </script>

@endsection