@extends('backend.master')
@section('title') Bank System | Transaction Category List @endsection
@section('styles')
@endsection
@section('content')

<div id="content" class="content filter_3">
    <main>

        <div class="align-items-center p-2">
            <h3 class="breadcump-header">Transaction Category <span class="divider"></span></h3>
            <span class="nav_indicator"><a href="{{route('home')}}" class="breadcump-text">Home</a> > <span
                    class="breadcump-active"> List</span></span>
        </div>

        <!-- table section -->
        <div class="container-fluid">
            <section class="row">
                <div class="col-lg-12 table_wrap hide_scrollbar table_data">
                    <div class="card-body card" style="padding-bottom: 5px;">
                
                        <div class="card-content custom-card-content-for-datatable pb-3 pt-2">
                            <h2 class="card-title">Category List</h2>
                            <div class="float-right justify-content-end">
                                <a class="custom-datatable-add-btn" href="#" data-bs-toggle="modal" data-bs-target="#addTransactionCategoryData">
                                    <i class="fa fa-plus"></i> Add Category
                                </a>
                            </div>
                        </div>

                        <table id="example" class="table table-bordered table-striped table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Category Image</th>
                                    <th>Category Type</th>
                                    <th>Category Name</th>
                                    <th>Category Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactionCategoryData as $key=>$item)
                                    @if(isset($item) && $item != null)
                                        <tr>

                                            <td>
                                                @if(isset($item->image) && $item->image != null)
                                                <img class="custom-datatable-image" src="{{asset('backend/uploads/transactionCategory/thumbnail/'.$item->image)}}" />
                                                @else
                                                <img class="custom-datatable-image" src="{{asset('backend')}}/assets/images/img_preview.png" />
                                                @endif
                                            </td>
                                            <td>
                                                {{$item->category_type}}
                                                <div class="row-actions">
                                                    <span><a href="#" data-bs-toggle="modal" data-bs-target="#updateTransactionCategoryData{{$item->id}}">Edit </a> |
                                                    </span>
                                                    <span>
                                                        <a href="{{route('transaction-category.destroy', $item->id)}}">
                                                            <span class="trash">Trash</span>
                                                        </a>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row_title">
                                                    <strong><a href="#">{{$item->category_name}}</a></strong>
                                                </div>
                                                
                                            </td>
                                            <td>
                                                Created Date: {{Carbon\Carbon::createFromFormat('Y-m-d', $item->created_at->toDateString())->format('d-m-Y')}}
                                            </td>
                                        </tr>

                                        {{-- //To update the Transaction Category data... --}}
                                        <div class="modal fade" id="updateTransactionCategoryData{{$item->id}}" tabindex="-1" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-850px">
                                                <div class="modal-content rounded">
                                                    <div class="modal-header border-0 justify-content-between">
                                                        <h2 class="modal_head">Update Transaction Category</h2>
                                                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                            <span class="svg-icon svg-icon-1">
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                                                        fill="currentColor"></rect>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                                        <form class="gy-1 pt-75" action="{{route('transaction-category.update', $item->id)}}" method="POST"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('put')
                                        
                                                            <div class="row mt-4">
                                                                <div class="col-md-6 mb-3">
                                                                    <div class="single_input">
                                                                        <label for="category_name" class="fw-bolder mb-1">Category Name <span
                                                                                class="text-danger custom-required-font">(Required)</span></label>
                                                                        <input onblur="inputValidator(this.value,'warnTextName','text')" placeholder="Category Name"
                                                                            class="form-control" type="text" name="category_name" id="category_name" value="{{$item->category_name}}"
                                                                            required>
                                                                        <span id="warnTextName" class="text-danger warn">Category name is required</span>
                                                                
                                                                        @error('category_name')
                                                                        <span class=text-danger>{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 mb-3 custom-select2-dropdown">
                                                                    <label for="category_type" class="fw-bolder mb-1">Select Category Type <span
                                                                            class="text-danger custom-required-font">(Required)</span></label>
                                                                    <select id="category_type{{$item->id}}" class="form-control w-100" name="category_type" required>
                                                                        <option value="" selected disabled>Select Category Type</option>
                                                                        <option value="Income" 
                                                                        {{ $item->category_type == "Income" ? 'selected' : '' }}
                                                                        >Income (Credit)</option>
                                                                        <option value="Expense" 
                                                                        {{ $item->category_type == "Expense" ? 'selected' : '' }}
                                                                        >Expense (Debit)</option>
                                                                    </select>
                                                                
                                                                    @error('category_type')
                                                                    <span class=text-danger>{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            
                                                                <div class="col-12 mb-3">
                                                                    <label for="uploadImage{{$item->id}}" class="fw-bolder mb-1">Category Icon <span
                                                                            class="text-danger custom-required-font"></span></label>
                                                                    <div class="select_imgWith_preview">
                                                                        @if(isset($item->image) && $item->image != null)
                                                                        <img id="uploadPreview{{$item->id}}" src="{{asset('backend/uploads/transactionCategory/'.$item->image)}}" />
                                                                        @else
                                                                        <img id="uploadPreview{{$item->id}}" src="{{asset('backend')}}/assets/images/img_preview.png" />
                                                                        @endif
                                                                        <input id="uploadImage{{$item->id}}" type="file" name="image" onchange="PreviewImage('uploadImage{{$item->id}}','uploadPreview{{$item->id}}');" />
                                                                        <i onclick="cancelPreview('uploadPreview{{$item->id}}')" class="fa-sharp fa-solid fa-xmark cross_mark"></i>
                                                                    </div>
                                                            
                                                                    @error('image')
                                                                    <span class=text-danger>{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                        
                                                            <div class="text-end mt-4">
                                                                <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">Update</button>
                                                                <button type="reset" id="kt_modal_new_target_cancel" data-bs-dismiss="modal"
                                                                    class="btn btn-light me-3">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            $(document).ready(function() {
                                                $('#category_type'+{{$item->id}}).select2({
                                                    dropdownParent: $('#updateTransactionCategoryData'+{{$item->id}})
                                                });
                                            });
                                        </script>

                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Category Image</th>
                                    <th>Category Type</th>
                                    <th>Category Name</th>
                                    <th>Category Created</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        {{-- //To create a new Transaction Category... --}}
        <div class="modal fade" id="addTransactionCategoryData" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-850px">
                <div class="modal-content rounded">
                    <div class="modal-header border-0 justify-content-between">
                        <h2 class="modal_head">Add Transaction Category</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                        <form class="gy-1 pt-75" action="{{route('transaction-category.store')}}" method="POST" enctype="multipart/form-data">
        
                            @csrf

                            <div class="row mt-4">

                                <div class="col-md-6 mb-3">
                                    <div class="single_input">
                                        <label for="category_name" class="fw-bolder mb-1">Category Name <span
                                                class="text-danger custom-required-font">(Required)</span></label>
                                        <input onblur="inputValidator(this.value,'warnTextName','text')" placeholder="Category Name"
                                            class="form-control" type="text" name="category_name" id="category_name" required>
                                        <span id="warnTextName" class="text-danger warn">Category name is required</span>
                                
                                        @error('category_name')
                                        <span class=text-danger>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3 custom-select2-dropdown">
                                    <label for="category_type" class="fw-bolder mb-1">Select Category Type <span
                                            class="text-danger custom-required-font">(Required)</span></label>
                                    <select id="category_type" class="form-control w-100" name="category_type" required>
                                        <option value="" selected disabled>Select Category Type</option>
                                        <option value="Income">Income (Credit)</option>
                                        <option value="Expense">Expense (Debit)</option>
                                    </select>
                                
                                    @error('category_type')
                                    <span class=text-danger>{{ $message }}</span>
                                    @enderror
                                </div>
                            
                                <div class="col-12 mb-3">
                                    <label for="uploadImage" class="fw-bolder mb-1">Category Icon <span
                                            class="text-danger custom-required-font"></span></label>
                                    <div class="select_imgWith_preview">
                                        <img id="uploadPreview" src="{{asset('backend')}}/assets/images/img_preview.png" />
                                        <input id="uploadImage" type="file" name="image" onchange="PreviewImage('uploadImage','uploadPreview');" />
                                        <i onclick="cancelPreview('uploadPreview')" class="fa-sharp fa-solid fa-xmark cross_mark"></i>
                                    </div>
                            
                                    @error('image')
                                    <span class=text-danger>{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
        
                            <div class="text-end mt-4">
                                <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">Submit</button>
                                <button type="reset" id="kt_modal_new_target_cancel" data-bs-dismiss="modal"
                                    class="btn btn-light me-3">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#category_type').select2({
            dropdownParent: $('#addTransactionCategoryData')
        });
    });
</script>
@endsection