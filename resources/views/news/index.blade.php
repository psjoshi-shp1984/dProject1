@extends('admin.layouts.app')
@section('title', 'News Create')

@section('content')
<h2>News List</h2>

<a href="{{ route('admin.news.create') }}" class="btn btn-primary mb-3">Add News</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
           
            <th>ID</th>
            <th>Title</th>
            <th>Image</th>
            <th>Content</th>
            <th>Order</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($news as $item)
        <tr>
            
            <td>{{ $item->id }}</td>
            <td>{{ $item->title }}</td>
             <td>
                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}" width="60">
                @endif
            </td>
            <td>
                <button type="button" 
                        class="btn btn-info btn-sm view-description"
                        data-bs-toggle="modal"
                        data-bs-target="#descriptionModal"
                        data-description="{!! htmlspecialchars($item->content) !!}">
                    View
                </button>
            </td>
            <td>{{ $item->order_no }}</td>
            <td>{{ $item->status }}</td>
           
            <td>
                <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Delete this record?')" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="max-height: 90vh;">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Page Description</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="descriptionContent" style="overflow-y:auto; max-height:70vh;">
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.view-description').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const description = this.getAttribute('data-description');
            document.getElementById('descriptionContent').innerHTML = description;
        });
    });
});
</script>
{{ $news->links() }}

@endsection

