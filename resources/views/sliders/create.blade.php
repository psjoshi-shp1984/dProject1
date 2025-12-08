@extends('admin.layouts.app')
@section('title', 'Sliders Create')
@section('content')
<div class="container">
    <h1>Add Slider</h1>
     <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.sliders.index') }}">Slider Pages</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Create Slider</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="sliderForm" action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="mb-3">
            <label>Subtitle</label>
            <input type="text" name="subtitle" class="form-control">
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label>Link</label>
            <input type="url" name="link" class="form-control">
        </div>
        <div class="mb-3">
            <label>Page Type</label>
            <select name="type" class="form-control">
                <option value="" selected>Please Select</option>
                <option value="homepage">Home</option>
                <option value="tenda_partner">Tenda Partner</option>
                <option value="si_partner">SI Partner</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" selected>Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="0">
        </div>
        <button type="submit" class="btn btn-primary">Add Slider</button>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

{{-- jQuery & Validation --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(function () {

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
$.validator.addMethod("uniqueSortOrder", function (value, element) {
    var isValid = true; // default true to avoid breaking
    if (value === "") return true; // skip empty

    $.ajax({
        url: "{{ route('admin.sliders.checkSortOrder') }}",
        type: "GET",
        dataType: "json",
        data: { sort_order: value },
        async: false,
        success: function (response) {
            // Defensive: ensure valid JSON and key exists
            if (response && typeof response.valid !== "undefined") {
                isValid = response.valid;
            } else {
                console.warn("Unexpected response:", response);
                isValid = true;
            }
        },
        error: function (xhr) {
          //  console.error("AJAX error:", xhr.status, xhr.statusText);
            isValid = false; // block submission on AJAX error
        }

    });

    return isValid;
}, "This sort order already exists.");


    // Initialize validation
    $("#sliderForm").validate({
        ignore: [],
        rules: {
            title: {
                required: true,
                lettersOnly: true
            },
            subtitle: {
                required: true,
                lettersOnly: true
            },
            type: {
                required: true,
            },
            image: {
                required: true,
                imageExtension: true
            },
            link: {
                url: true
            },
            sort_order: {
                number: true,
                uniqueSortOrder: true
            }
        },
        messages: {
            title: {
                required: "Please enter a title."
            },
            image: {
                required: "Please upload an image."
            },
            type: {
                required: "Please Select type."
            },
            link: {
                url: "Please enter a valid URL (e.g., https://example.com)."
            },
            sort_order: {
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
