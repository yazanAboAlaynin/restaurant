@extends('layouts.admin')

@section('content')

    <div class="container">

        <h1>Reservations:</h1>

        <br/>
        <br/>

        <table class="table table-bordered table-striped data-table">

            <thead>

            <tr>

                <th>id</th>
                <th>name</th>

                <th>date</th>
                <th>time</th>


                <th>persons number</th>



                <th width="100px">Action</th>

            </tr>

            </thead>

            <tbody>

            </tbody>

        </table>

    </div>

    <script type="text/javascript">
        $(function () {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.reservations') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {
                        "name": "user",
                        "data": "user",
                        "render": function (data, type, full, meta) {

                            return data['name'];
                        },
                        "title": "user name",
                        "orderable": true,
                        "searchable": true
                    },
                    {data: 'date', name: 'date'},
                    {data: 'time', name: 'time'},
                    {data: 'persons_number', name: 'persons_number'},

                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
        function update(id) {
            window.location.href = 'reservation/'+id+'/edit';
        }
        function del(id) {

            $.ajax({
                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },
                type:'POST',

                url:'{{ route("admin.delete.reservation") }}',

                data:{id:id},

                success:function(data){
                    $('.data-table').DataTable().ajax.reload();
                    alert("deleted");

                }

            });
        }
    </script>


@endsection