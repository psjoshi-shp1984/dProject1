@extends('admin.layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="container mt-4">
     <!-- ✅ Breadcrumb Start -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Contact Us Page</li>
        </ol>
    </nav>
    <!-- ✅ Breadcrumb End -->
    <div class="card p-4 shadow-sm">
        <h3>Contact Us Enquiries</h3>
        <table class="table table-bordered table-hover mt-3">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Message</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->type ?? '-' }}</td>
                    <td>{{ $contact->name ?? '-' }}</td>
                    <td>{{ $contact->company_name ?? '-' }}</td>
                    <td>{{ $contact->mail ?? '-' }}</td>
                    <td>{{ $contact->mobile_no ?? '-' }}</td>
                    <td>{{ $contact->country ?? '-' }}</td>
                    <td>{{ $contact->city ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $contact->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($contact->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm view-message"
                                data-bs-toggle="modal"
                                data-bs-target="#messageModal"
                                data-message="{{ $contact->message }}">
                            View
                        </button>
                    </td>
                    <td>{{ $contact->created_at?->format('d M Y, h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">No records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- 🧾 Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel">Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="messageContent"></div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".view-message").forEach(btn => {
        btn.addEventListener("click", function() {
            const msg = this.getAttribute("data-message") || "No message available.";
            document.getElementById("messageContent").textContent = msg;
        });
    });
});
</script>
@endsection
