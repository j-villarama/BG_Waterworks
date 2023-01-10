@extends('admin.layouts.app')
@section('content')

<style>

 .card_custom{
    height: auto;
    width: 1000px;
    background-color: white;
    /* border-radius: 15px;
    background: linear-gradient(145deg, #ffffff, #e6e6e6);
    box-shadow:  20px 20px 60px #d9d9d9,
                -20px -20px 60px #ffffff; */
 }

</style>

<div class="page__container pt-4">
   
    {{-- <div class="back__button"><a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back</a></div> --}}
    <div class="card_custom">
        
        <div class="card-header ">
            <div class="card-header-text">
                <span style="color:#073b3a;"><strong>ATTACHED FILES</strong></span>
            </div>

            
                <div class="card-body--header d-flex justify-content-end">
                    {{-- <form style="display: flex;" action="{{ route('admin.add_files.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="pt-1"><input class="form-control form-control-sm"  type="file" name="file"></div>
                        <div><button type="submit" class="btn btn-lg btn-sm"><i style="color:#2364aa;" class='bx bx-save bx-sm pt-1 pe-1'></i></button></div>
                    </form>     --}}

                    <form action="{{ route('admin.add_files.post') }}" method="post" enctype="multipart/form-data" style="display: flex;">
                        {{-- @include('layouts.partials.messages') --}}
                        @csrf
                        <div class="form-group pt-2">
                          <input required type="file" name="file" class="form-control form-control-sm" accept=".jpg,.jpeg,.bmp,.png,.gif,.doc,.docx,.csv,.rtf,.xlsx,.xls,.txt,.pdf,.zip">
                        </div>
            
                        <button type="submit" class="btn btn-lg btn-sm"><i style="color:#073b3a;" class='bx bx-save bx-sm pt-1 pe-1'></i></button>
                    </form>
                    
                </div>
                

    </div>

    <div class="p-5 rounded">
        
        <div style="display: flex;">
            
            <div class=""><a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger"><i class='bx bx-arrow-back'></i>Cancel</a></div>
        </div>    
        {{-- @include('layouts.partials.messages') --}}

        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Files</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($files as $file)
              <tr>
                
                <td>{{ $file->files }}</td>
                
                <td width="5%"><span data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <a href="#" class="btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-placement="top" title="Delete"><i class='bx bx-trash bx-xs py-2'><span class="px-1">Delete</span></i></a>
                </span></td>
              </tr>

              <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <strong>{{ $file->files }}</strong> record?
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('admin.delete_attachment',$file->id) }}" method="POST" class="del__button">
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
    </div>




</div> 
</div>


@endsection