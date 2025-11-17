@extends('admin.layouts.app')
@section('title', 'Static Pages')

@section('content')
<div class="container-fluid mt-4">
     <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Static Page Index</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->
    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">All Static Pages</h2>
            <a href="{{ route('admin.static_pages.create') }}" class="btn btn-primary">
                + Add New Page
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Page Name</th>
                        <th>Image</th>
                        <th>Image Name</th>
                        <th>Hover Text</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>{{ $page->page_name }}</td>

                            {{-- ✅ Image column --}}
                            <td>
                                @if($page->image)
                                    <img src="{{ asset('storage/'.$page->image) }}" 
                                         alt="{{ $page->image_name }}" 
                                         class="img-thumbnail"
                                         style="width:100px; height:70px; object-fit:cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>

                            <td>{{ $page->image_name }}</td>
                            <td>{{ $page->image_hover_text }}</td>

                            {{-- ✅ View button opens modal --}}
                            <td>
                                <button type="button" 
                                        class="btn btn-info btn-sm view-description"
                                        data-bs-toggle="modal"
                                        data-bs-target="#descriptionModal"
                                        data-description="{!! htmlspecialchars($page->page_descriptions) !!}">
                                    View
                                </button>
                            </td>

                            <td>
                                <span class="badge {{ $page->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($page->status) }}
                                </span>
                            </td>

                            <td>{{ $page->created_at?->format('d M Y') }}</td>

                            <td>
                                <a href="{{ route('admin.static_pages.edit', $page->id) }}" 
                                   class="btn btn-sm btn-warning">
                                   Edit
                                </a>
                                <form action="{{ route('admin.static_pages.destroy', $page->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Delete this page?')" 
                                            class="btn btn-sm btn-danger">
                                            Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Pagination --}}
        <div class="mt-3">
            {{ $pages->links() }}
        </div>
    </div>
</div>

{{-- ✅ Modal --}}
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
@endsection
