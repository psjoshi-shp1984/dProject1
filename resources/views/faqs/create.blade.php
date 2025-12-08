@extends('admin.layouts.app')
@section('title', 'StaticPage Create')

@section('content')
<div class="container mt-4">
    <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.faq.index') }}">Faq List</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Create Page</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->
    <div class="card p-4">
        <h3>Add New Page</h3>
        <form id="faqCreatForm" action="{{ route('admin.faq.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Page Name</label>
                <select name="page_name" class="form-control" required>
                    <option value="" selected>Please Select</option>
                    <option value="homepage">Home</option>
                    <option value="tenda_partner">Tenda Partner</option>
                    <option value="si_partner">SI Partner</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Question</label>
                <textarea name="question" class="form-control" rows="4" required></textarea>
            </div>
            
            <div class="mb-3">
                <label>Answer</label>
                <textarea name="answer" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="0">
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save Page</button>
        </form>
    </div>
</div>

<!-- ✅ jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ✅ Validation Plugin -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<!-- ✅ CKEditor -->

<script>
$(document).ready(function () {

    // ✅ Custom validation rules
    $.validator.addMethod("lettersOnly", function(value, element) {
        return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
    }, "Only letters and spaces are allowed.");
    
    // ✅ Initialize validation
    $("#faqCreatForm").validate({
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
