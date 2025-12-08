@extends('admin.layouts.app')
@section('title', 'StaticPage Edit')
@section('content')
<div class="container mt-4">
    <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.faq.index') }}">Faq Edit</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Faq Edit Page</li>
        </ol>
    </nav>    
    <!-- ✅ Breadcrumb End -->
    <div class="card p-4">
        <h3>Edit Page</h3>
        <form id="faqPageEditForm" action="{{ route('admin.faq.update', $faq->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Page Name</label>
                <input type="text" name="page_name" value="{{ $faq->page_name }}" class="form-control" required>
            </div>
            <!-- Question Field -->
            <div class="mb-3">
                <label>Question</label>
                <textarea name="question" class="form-control" rows="4" required>{{ trim($faq->question ?? '') }}</textarea>
                
            </div>

            <!-- Answer Field -->
            <div class="mb-3">
                <label>Answer</label>
                <textarea name="answer" class="form-control" rows="4" required>{{ trim($faq->answer ?? '') }}</textarea>
            </div>
             <div class="mb-3">
                <label>Sort Order</label>
                <input type="number" name="order_no" class="form-control" value="{{ $faq->order_no }}">
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $faq->status=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ $faq->status=='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Page</button>
        </form>
    </div>
</div>

<!-- ✅ CKEditor 4 (Full Version) -->
<!-- ✅ jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ✅ Validation Plugin -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<!-- ✅ CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
$(document).ready(function () {

    // ✅ Initialize CKEditor
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

    
   

    
    // ✅ Initialize validation
    $("#faqPageEditForm").validate({
        ignore: [],
        rules: {
             page_name: {
                required: true,
                lettersOnly: true
            },
            question: {
                required: true,
            },
            answer: {
                required: true,
            },
        },
        messages: {
             page_name: {
                required: "Please enter a Page Name."
            },
            page_slug: {
                required: "Please enter a Question.",
            },
            answer: {
                required: "Please enter Answer."
            }
        },
        errorClass: "text-danger mt-1",
        highlight: function(element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function(form) {
            $("button[type=submit]").prop("disabled", true).text("Saving...");
            form.submit();
        }
    });
});
</script>
@endsection
