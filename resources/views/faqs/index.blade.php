@extends('admin.layouts.app')
@section('title', 'FAQ List')

@section('content')
<div class="container-fluid mt-4">
    <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">FAQ List</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->

    <div class="card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">All FAQs</h2>
            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">+ Add FAQ</a>
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
                        <th>Question/Answer</th>
                        <th>Order No</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td>{{ $faq->id }}</td>
                            <td>{{ $faq->page_name }}</td>
                            <!-- ✅ View Answer button -->
                            <td>
                                <button type="button" 
                                        class="btn btn-info btn-sm view-answer" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#answerModal"
                                        data-question="{{ $faq->question }}"
                                        data-answer="{{ strip_tags($faq->answer) }}">
                                    View
                                </button>
                            </td>

                            <td>
                                <span class="">
                                    {{ $faq->order_no }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $faq->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($faq->status) }}
                                </span>
                            </td>

                            <td>{{ $faq->created_at?->format('d M Y') }}</td>

                            <td>
                                <a href="{{ route('admin.faq.edit', $faq->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete this FAQ?')" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No FAQs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $faqs->links() }}
        </div>
    </div>
</div>

<!-- ✅ Bootstrap Modal -->
<div class="modal fade" id="answerModal" tabindex="-1" aria-labelledby="answerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="answerModalLabel">FAQ Answer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h5 id="modalQuestion" class="fw-bold mb-3"></h5>
                <p id="modalAnswer" class="mb-0"></p>
            </div>
        </div>
    </div>
</div>

<!-- ✅ JavaScript to load answer into modal -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.view-answer').forEach(function(button) {
        button.addEventListener('click', function() {
            const question = this.getAttribute('data-question');
            const answer = this.getAttribute('data-answer');

            document.getElementById('modalQuestion').textContent = question;
            document.getElementById('modalAnswer').textContent = answer;
        });
    });
});
</script>
@endsection
