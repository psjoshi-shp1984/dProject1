@extends('admin.layouts.app') {{-- or admin.layouts.app if your folder is named that --}}
@section('title', 'News Edit')
@section('content')
<h2>Edit News</h2>

<div class="container mt-4">
    <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.news.index') }}">News List</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Update News</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->
    <div class="card p-4">
        <h3>Update New Page</h3>
        <form action="{{ route('admin.news.update', $news->id) }}" id="update_form"  method="POST" enctype="multipart/form-data">
             @csrf
            @method('PUT')
            <div class="mb-3">
               <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $news->title }}" required>
            </div>
            <div class="mb-3">
                <label>Image</label>
                @if($news->image)
                    <img src="{{ asset('storage/' . $news->image) }}" width="100" class="mb-2">
                @endif
                <input type="file" name="image" class="form-control" value="{{ $news->image }}">
            </div>
            <div class="mb-3">
                <label>Content</label>
                <textarea name="content"  value="{{ $news->content }}" id="editor" class="form-control" rows="4">{!! $news->content !!}</textarea>
            </div>
            
            
            <div class="mb-3">
                <label>Sort Order</label>
                <input type="number" name="order_no" value="{{ $news->order_no }}" class="form-control" value="0">
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $news->status=='active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $news->status=='inactive' ? 'selected' : '' }}>Inactive</option>
              
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save Page</button>
        </form>
    </div>
</div>

{{-- jQuery & Validation --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ✅ Validation Plugin -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<!-- ✅ CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
$(function () {

    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('editor', {
            height: 400,
            filebrowserUploadUrl: "{{ route('admin.ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserBrowseUrl: "{{ route('admin.ckeditor.browse') }}",
            filebrowserUploadMethod: 'form'
        });
    } else {
        console.error('CKEditor failed to load.');
    }
    // Ensure jQuery loaded
    if (typeof $.validator === 'undefined') {
        console.error('jQuery Validation plugin not loaded.');
        return;
    }

    // Custom rule: letters only
    $.validator.addMethod("lettersOnly", function (value, element) {
        return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
    }, "This column should contain only letters and spaces.");

    

    // Custom rule: image file extension
    $.validator.addMethod("imageExtension", function (value, element) {
        return this.optional(element) || /\.(jpe?g|png|gif|webp)$/i.test(value);
    }, "Allowed image formats: jpeg, jpg, png, gif, webp.");

    // Custom rule: unique sort order check via AJAX
   // Safe unique sort order validation


    // Initialize validation
    $("#news_form").validate({
        ignore: [],
        rules: {
            title: {
                required: true,
                lettersOnly: true
            },
            content: {
                required: true,
             
            },
            image: {
                required: true,
                imageExtension: true
            },
            order_no: {
                number: true,
            }
        },
        messages: {
            title: {
                required: "Please enter a title."
            },
            image: {
                required: "Please upload an image."
            },
            content: {
                required: "Please Enter Content."
            },
            order_no: {
                number: "Sort order must be a number."
            }
        },
        errorElement: "div",
        errorClass: "text-danger mt-1",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            $("button[type=submit]").prop("disabled", true).text("Please wait...");
            form.submit();
        }
    });
});
</script>
@endsection
