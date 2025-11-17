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
                <textarea name="question" class="form-control" rows="4" required>
                    {{ old('question', $faq->question ?? '') }}
                </textarea>
            </div>

            <!-- Answer Field -->
            <div class="mb-3">
                <label>Answer</label>
                <textarea name="answer" class="form-control" rows="4" required>
                    {{ old('answer', $faq->answer ?? '') }}
                </textarea>
            </div>
             <div class="mb-3">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ $faq->order_no }}">
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

    // ✅ Custom validation rules
    $.validator.addMethod("lettersOnly", function(value, element) {
        return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
    }, "Only letters and spaces are allowed.");

   

    $.validator.addMethod("slugFormat", function(value, element) {
        return this.optional(element) || /^[a-z0-9-]+$/.test(value);
    }, "Slug can only contain lowercase letters, numbers, and hyphens.");

    $.validator.addMethod("imageExtension", function(value, element) {
        return this.optional(element) || /\.(jpe?g|png|gif|webp)$/i.test(value);
    }, "Allowed formats: jpeg, jpg, png, gif, webp.");

    
    // ✅ Initialize validation
    $("#faqPageEditForm").validate({
        ignore: [],
        rules: {
            page_name: {
                required: true,
                lettersOnly: true
            },
            page_slug: {
                required: true,
                slugFormat: true,
                uniqueSlug: true
            },
            image: {
                imageExtension: true
            },
            image_name: {
                lettersOnly: true
            },
            image_hover_text: {
                lettersOnly: true
            },
            page_descriptions: {
                required: function() {
                    CKEDITOR.instances.editor.updateElement();
                    return true;
                }
            }
        },
        messages: {
            page_name: {
                required: "Please enter a Page Name."
            },
            page_slug: {
                required: "Please enter a Page Slug.",
                uniqueSlug: "This slug is already in use."
            },
            page_descriptions: {
                required: "Please enter page content."
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
