<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>TradeVista - {{ Auth::user()->name }}</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet">

  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet">

  <!-- Nepcha Analytics -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>
<style>
    .blog-answer {
        font-family: 'Arial', sans-serif; /* Font style to resemble a blog */
        font-size: 1rem; /* Adjust size for readability */
        line-height: 1.6; /* Increase line height for readability */
        color: #333; /* Dark text color for contrast */
        margin: 20px 0; /* Space above and below */
        padding: 15px; /* Padding for the text */
        border-left: 5px solid #3498db; /* Blue left border to make it stand out */
        background-color: #f9f9f9; /* Light background color */
        border-radius: 8px; /* Rounded corners for a softer look */
    }

    .answer-label {
        font-weight: bold;
        color: #3498db; /* Blue color for the label */
    }

    .answer-content {
        font-style: italic; /* Italicize the answer content */
        color: #555; /* Lighter text color for the answer */
    }

    /* Optional: Add a shadow to the whole block to make it more prominent */
    .blog-answer {
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Optional: Hover effect to highlight when the user hovers over the answer */
    .blog-answer:hover {
        background-color: #f1f1f1;
    }
</style>

<body class="g-sidenav-show  bg-gray-100">
@include('components.sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('components.nav')
      
    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

      <div class="row my-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
          <div class="card">
              <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <h3 class="text-center fw-bold text-primary">Support Tickets</h3>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#supportTicketModal">Report Issue</button>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Issue Type</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                      @if($supports && count($supports) > 0)
                      @foreach($supports as $support)
                        <tr>
                            <td>{{ $support->ticket_id }}</td>
                            <td>{{ $support->problem_type }}</td>
                            <td><a href="{{ $support->image_url }}" target="_blank">View</a></td>
                            <td>
                                @if($support->status === 'ISSUE FIXED')
                                    <span class="badge bg-success">{{ $support->status }}</span> <!-- Green for ISSUE FIXED -->
                                @else
                                    <span class="badge bg-warning">{{ $support->status }}</span> <!-- Yellow for other statuses -->
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($support->created_at)->format('l, F j, Y g:i A') }}
                            </td>
                             <td>
                            <!-- <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewTicketModal"
                                    data-ticket-id="{{ $support->ticket_id }}"
                                    data-issue-type="{{ $support->problem_type }}"
                                    data-status="{{ $support->status }}"
                                    data-created-at="{{ \Carbon\Carbon::parse($support->created_at)->format('l, F j, Y g:i A') }}"
                                    data-message="{{ $support->message }}"
                                    data-answer="{{ $support->answer }}">View
                            </button> --><button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#replyModal{{ $support->id }}">View</button>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">NO SUPPORT TICKET FOUND</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Ticket Modal -->
    <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary" id="viewTicketModalLabel">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Ticket ID:</strong> <span id="ticket-id"></span></p>
                    <p><strong>Issue Type:</strong> <span id="issue-type"></span></p>
                    <p><strong>Status:</strong> <span id="ticket-status"></span></p>
                    <p><strong>Date Created:</strong> <span id="created-at"></span></p>
                    <p><strong>Message:</strong> <span id="ticket-message"></span></p>
                  <p class="blog-answer">
                        <strong class="answer-label">Answer:</strong> 
                        <span id="ticket-answer" class="answer-content"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@foreach ($supports as $support)
  <!-- Reply Modal -->
  <div class="modal fade" id="replyModal{{ $support->id }}" tabindex="-1" aria-labelledby="replyModalLabel{{ $support->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-primary" id="replyModalLabel{{ $support->id }}">Your Support Ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <p><strong>Ticket ID:</strong> {{ $support->ticket_id }}</p>
          <p><strong>Status:</strong> {{ $support->status }}</p>
          <p><strong>Issue Type:</strong> {{ $support->problem_type }}</p>
          <p>Attended to by: <strong>{{ $support->attendant->name ?? 'N/A' }}</strong></p>
          <hr>

          <!-- 🗨️ Conversation Thread -->
            <div 
              class="conversation-thread mb-4"
              style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa; padding: 10px; border-radius: 5px;"
              data-support-id="{{ $support->id }}"
              data-fetch-url="{{ route('support.messages.fetch', $support->id) }}"
              id="conversation-{{ $support->id }}" 
            >
              <!-- Messages will be loaded here -->
            </div>

            <!-- ✍️ Reply Form -->
            <form class="user-reply-form" data-support-id="{{ $support->id }}">
              @csrf
              <div class="mb-3">
                <label class="form-label fw-bold">Your Reply</label>
                <textarea name="message" class="form-control" rows="3" placeholder="Type your message..." required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Send Reply</button>
            </form>

        </div>
      </div>
    </div>
  </div>
@endforeach


        <!-- Support Ticket Modal -->
        <div class="modal fade" id="supportTicketModal" tabindex="-1" aria-labelledby="supportTicketModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-primary" id="supportTicketModalLabel">Report an Issue</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form id="supportTicketForm" class="p-4 border rounded bg-light" method="POST" action="{{ route('support.ticket.submit') }}" enctype="multipart/form-data">
                          @csrf
                          <div class="mb-3">
                              <label for="problemType" class="form-label fw-bold">Type of Issue</label>
                              <select class="form-select" name="problem_type" id="problemType" required>
                                  <option value="" selected disabled>Select an issue</option>
                                  <option value="billing">Billing</option>
                                  <option value="technical">Technical Issue</option>
                                  <option value="account">Account Issue</option>
                                  <option value="other">Other</option>
                              </select>
                          </div>
                          <div class="mb-3">
                              <label for="message" class="form-label fw-bold">Message</label>
                              <textarea class="form-control" name="message" id="message" rows="4" placeholder="Describe your issue" required></textarea>
                          </div>
                          <div class="mb-3">
                              <label for="image" class="form-label fw-bold">Attach Image (Optional)</label>
                              <input type="file" class="form-control" name="image" id="image" accept="image/*">
                          </div>
                          <button type="submit" id="submitBtn" class="btn btn-primary w-100 fw-bold">
                              Submit Ticket
                          </button>
                      </form>
                    </div>
                </div>
            </div>
        </div>
        
          </div>
        </div>
      </div>
    </main>

  <!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JavaScript to Populate Modal -->
<script>
    var viewTicketModal = document.getElementById('viewTicketModal');
    viewTicketModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var ticketId = button.getAttribute('data-ticket-id');
        var issueType = button.getAttribute('data-issue-type');
        var status = button.getAttribute('data-status');
        var createdAt = button.getAttribute('data-created-at');
        var message = button.getAttribute('data-message');
        var answer = button.getAttribute('data-answer');

        // Populate modal with ticket data
        document.getElementById('ticket-id').textContent = '#' + ticketId;
        document.getElementById('issue-type').textContent = issueType;
        document.getElementById('ticket-status').textContent = status;
        document.getElementById('created-at').textContent = createdAt;
        document.getElementById('ticket-message').textContent = message;
        document.getElementById('ticket-answer').textContent = answer;
    });
</script>
<script>
$(document).ready(function () {
    $("#supportTicketForm").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let submitBtn = $("#submitBtn");

        // Show loading state
        submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...').prop("disabled", true);

        $.ajax({
            url: "{{ route('support.ticket.submit') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Ticket Submitted",
                    text: "Your support ticket has been submitted successfully!",
                }).then(() => {
                    location.reload(); // Reload page after submission
                });
            },
            error: function (xhr) {
                let errorMessage = xhr.responseJSON?.message || "An error occurred. Please try again.";
                Swal.fire({
                    icon: "error",
                    title: "Submission Failed",
                    text: errorMessage,
                });

                // Reset button to original state
                submitBtn.html("Submit Ticket").prop("disabled", false);
            }
        });
    });
});
</script>
<script>
$(document).ready(function () {

// 1. Load messages when modal is shown
let messageInterval = null;

// Load messages (extracted to reuse inside interval)
function loadMessages(modal) {
  const conversationBox = modal.find('.conversation-thread');
  const fetchUrl = conversationBox.data('fetch-url');

  $.get(fetchUrl, function (data) {
    if (!data || !data.initial) {
      conversationBox.html('<p class="text-danger">Failed to load messages.</p>');
      return;
    }

    let output = '';

    // ✉️ Initial message
    output += `
      <div class="mb-3">
        <div>
          <strong>${data.initial.sender}</strong>
          <span class="text-muted small">– ${data.initial.time}</span>
        </div>
        <div class="border p-2 rounded bg-white">
          ${data.initial.message}
        </div>
      </div>
    `;

    // 📨 Reply messages
    if (data.messages && data.messages.length > 0) {
      data.messages.forEach(msg => {
        const label = msg.sender_type === 'admin' ? 'Support Team' : 'You';

        output += `
          <div class="mb-3">
            <div>
              <strong class="${msg.sender_type === 'user' ? 'text-primary' : 'text-dark'}">${label}</strong>
              <span class="text-muted small">– ${msg.created_at}</span>
            </div>
            <div class="border p-2 rounded bg-white">
              ${msg.message}
            </div>
          </div>
        `;
      });
    } else {
      output += `<p class="text-muted">No replies yet.</p>`;
    }

    conversationBox.html(output);

    // Auto scroll to bottom
    if (conversationBox.length && conversationBox[0]) {
      conversationBox.scrollTop(conversationBox[0].scrollHeight);
    }
  }).fail(() => {
    conversationBox.html('<p class="text-danger">Failed to load messages. Please try again.</p>');
  });
}

// When modal is shown
$('.modal').on('shown.bs.modal', function () {
  const modal = $(this);
  const conversationBox = modal.find('.conversation-thread');
  conversationBox.html('<p class="text-muted">Loading messages...</p>');

  // Load first time
  loadMessages(modal);

  // Start interval (1 second)
  messageInterval = setInterval(() => {
    loadMessages(modal);
  }, 1000);
});

// Clear interval when modal is hidden
$('.modal').on('hidden.bs.modal', function () {
  if (messageInterval) {
    clearInterval(messageInterval);
    messageInterval = null;
  }
});


  // 2. Handle reply form via AJAX
  $(document).on('submit', '.user-reply-form', function (e) {
    e.preventDefault();

    const form = $(this);
    const supportId = form.data('support-id');
    const messageBox = form.find('textarea[name="message"]');
    const message = messageBox.val();
    const conversationBox = $(`#conversation-${supportId}`);

    if (!message.trim()) return;

    form.find('button').prop('disabled', true).text('Sending...');

    $.post(`/support/${supportId}/messages/reply`, {
      message,
      _token: '{{ csrf_token() }}'
    }, function (res) {
      if (res.success) {
        messageBox.val('');
        conversationBox.append(`
          <div class="mb-3">
            <div>
              <strong>You</strong>
              <span class="text-muted small">– just now</span>
            </div>
            <div class="border p-2 rounded bg-white">
              ${message}
            </div>
          </div>
        `);
        if (conversationBox.length && conversationBox[0]) {
          conversationBox.scrollTop(conversationBox[0].scrollHeight);
        }
      } else {
        alert('Failed to send reply.');
      }
    }).fail(() => {
      alert('Server error. Please try again.');
    }).always(() => {
      form.find('button').prop('disabled', false).text('Send Reply');
    });
  });

});
</script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('assets/js/soft-ui-dashboard.min.js') }}?v={{ time() }}"></script>

</body>

</html>