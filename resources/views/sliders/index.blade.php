@extends('admin.layouts.app') {{-- or admin.layouts.app if your folder is named that --}}
@section('title', 'Sliders')

@section('content')
<div class="container mt-4">
     <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Slider Index</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->
    <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-3" style="margin: 20px;">
            <h2>Sliders List</h2>
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">+ Add New Slider</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th>Image</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                        <tr>
                            <td>{{ $slider->id }}</td>
                            <td>{{ $slider->title }}</td>
                            <td>{{ $slider->subtitle }}</td>
                            <td>
                                @if($slider->image)
                                    <img src="{{ asset('storage/'.$slider->image) }}" alt="Image" width="80" class="rounded">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($slider->type) }}</td>
                            <td>
                                @if($slider->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">In Active</span>
                                @endif
                            </td>
                            <td>{{ $slider->sort_order }}</td>
                            <td>
                                <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this slider?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No sliders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {!! $sliders->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>
@endsection
