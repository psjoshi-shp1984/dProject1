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
                <a href="{{ route('admin.static_pages.index') }}">Static Pages</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit Page</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->
    <div class="card p-4">
        <h3>Edit Page</h3>
        <form id="staticPageEditForm" action="{{ route('admin.static_pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Page Name</label>
                <input type="text" name="page_name" value="{{ $page->page_name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Page Slug</label>
                <input type="text" name="page_slug" value="{{ $page->page_slug }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Image</label><br>
                @if($page->image)
                    <img src="{{ asset('storage/'.$page->image) }}" width="100" class="mb-2">
                @endif
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label>Image Name</label>
                <input type="text" name="image_name" value="{{ $page->image_name }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Image Hover Text</label>
                <input type="text" name="image_hover_text" value="{{ $page->image_hover_text }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Page Description</label>
                <textarea name="page_descriptions" id="editor" class="form-control">{!! $page->page_descriptions !!}</textarea>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $page->status=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ $page->status=='inactive'?'selected':'' }}>Inactive</option>
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

    // ✅ AJAX uniqueness check for Page Slug
    $.validator.addMethod("uniqueSlug", function(value, element) {
        let isValid = false;
        $.ajax({
            url: "{{ route('admin.static_pages.checkSlug') }}", // you must create this route
            type: "GET",
            dataType: "json",
            async: false,
            data: { page_slug: value,
                id: "{{ $page->id }}"
             },
            success: function(response) {
                isValid = response.valid;
            },
            error: function() {
                isValid = true; // don't block on AJAX error
            }
        });
        return isValid;
    }, "This slug already exists.");

    // ✅ Initialize validation
    $("#staticPageEditForm").validate({
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
