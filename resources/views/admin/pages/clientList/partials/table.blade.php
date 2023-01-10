
<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Client Name</th>
            <th scope="col">Birthday</th>
            <th scope="col">Gender</th>
            <th scope="col">Address</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($select as $data)
            <tr>
                <th scope="row">{{ $data->id }}</th>
                <td name="fullname">{{ $data->client_firstname }} {{ $data->client_lastname }}</td>
                <td>{{$data->client_birthday ? date('m-d-Y', strtotime($data->client_birthday)): '' }}</td>
                <td>{{ $data->client_gender }}</td>
                <td>{{ $data->client_adress }}</td>
                <td class="actions">
                     

                        <a role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre  href="" ><i class='bx bx-dots-horizontal-rounded bx-sm ps-1 mt-2'></i></a>
                    
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.edit_client_data',$data->id) }}" class="btn-sm"><i class='bx bx-edit bx-xs py-2'><span class="px-1">Edit</span></i></a></li>
                            <li><span data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <a href="#" class="btn-sm" data-bs-toggle="modal" data-bs-target="#DeleteClient_{{ $data->id }}" data-bs-placement="top" title="Delete"><i class='bx bx-trash bx-xs py-2'><span class="px-1">Delete</span></i></a>
                            </span></li>
                            {{-- <li><a href="{{ route('admin.attachment', $data->id) }}" class="btn-sm"><i class='bx bx-paperclip bx-xs py-2'><span class="px-1">Attachment</span></i></a></li> --}}
                        </ul>


                </td>
            </tr>

            <!-- Modal -->
                <div class="modal fade" id="DeleteClient_{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                    <div class="modal-body">
                        Are you sure you want to delete <strong>{{ $data->client_lastname }}, {{ $data->client_firstname }}</strong> record?
                    </div>
                        <div class="modal-footer">
                            <form action="{{ route('admin.delete_client',$data->id) }}" method="POST" class="del__button">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Yes</a>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        </div>
                    </div>
                </div>
            <!-- Modal End -->

        @endforeach
    </tbody>
</table>