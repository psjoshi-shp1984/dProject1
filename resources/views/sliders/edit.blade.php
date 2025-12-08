@extends('admin.layouts.app') {{-- or admin.layouts.app if your folder is named that --}}
@section('title', 'Sliders Edit')
@section('content')
<div class="container">
    <h1>Edit Slider</h1>
     <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.sliders.index') }}">Slider Pages</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit Slider</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="sliderEditForm" action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $slider->title }}">
        </div>
        <div class="mb-3">
            <label>Subtitle</label>
            <input type="text" name="subtitle" class="form-control" value="{{ $slider->subtitle }}">
        </div>
        <div class="mb-3">
            <label>Image</label>
            @if($slider->image)
                <img src="{{ asset('storage/' . $slider->image) }}" width="100" class="mb-2">
            @endif
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label>Link</label>
            <input type="url" name="link" class="form-control" value="{{ $slider->link }}">
        </div>
        <div class="mb-3">
            <label>Type</label>
            <input type="text" name="type" class="form-control" value="{{ $slider->type }}">
        </div>
        <div class="mb-3">
            <label>Page Type</label>
         <select name="type" class="form-control">
            <option value="homepage" {{ $slider->type == 'homepage' ? 'selected' : '' }}>Home</option>
            <option value="tenda_partner" {{ $slider->type == 'tenda_partner' ? 'selected' : '' }}>Tenda Partner</option>
            <option value="si_partner" {{ $slider->type == 'si_partner' ? 'selected' : '' }}>SI Partner</option>
        </select>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" {{ $slider->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$slider->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ $slider->sort_order }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Slider</button>
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
        data: { sort_order: value,
            id: "{{ $slider->id }}"
         },
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
    $("#sliderEditForm").validate({
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
            image: {
                required: true,
                imageExtension: true
            },
            type: {
                required: true,
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

