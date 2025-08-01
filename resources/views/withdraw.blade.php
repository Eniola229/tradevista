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

<body class="g-sidenav-show  bg-gray-100">
@include('components.sidenav')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('components.nav')

              @if(session('message'))
              <div class="alert alert-success alert-dismissible fade show" role="alert" style="color: white;">
                  <strong>Success:</strong> {{ session('message') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

              @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert" style="color: white;">
                  <strong>Error:</strong> {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

              @if($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert" style="color: white;">
                  <strong>Error:</strong> Please fix the following issues:
                  <ul>
                      @foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              @endif

    <!-- End Navbar -->
    <div class="main">
      <main class="content">
        <div class="container-fluid p-0">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-header text-center">
                <h4>Withdraw Funds</h4>
            </div>
            <div class="card-body">
                <p><strong>Total Balance:</strong> ₦<span id="balance">{{ $balance->balance ?? 0 }}</span></p>
                <form id="withdrawForm">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Withdrawal Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Select Bank</label>
                        <select id="bank_name" name="bank_name" class="form-control" required>
                            <option value="">Select Bank</option>
                            <!-- Commercial Banks -->
                            <option value="Access Bank Plc">Access Bank Plc</option>
                            <option value="Citibank Nigeria Ltd">Citibank Nigeria Ltd</option>
                            <option value="Ecobank Nigeria Plc">Ecobank Nigeria Plc</option>
                            <option value="Fidelity Bank Plc">Fidelity Bank Plc</option>
                            <option value="First Bank of Nigeria Ltd">First Bank of Nigeria Ltd</option>
                            <option value="First City Monument Bank Plc">First City Monument Bank Plc</option>
                            <option value="Globus Bank Ltd">Globus Bank Ltd</option>
                            <option value="Guaranty Trust Bank Plc">Guaranty Trust Bank Plc</option>
                            <option value="Keystone Bank Ltd">Keystone Bank Ltd</option>
                            <option value="Parallex Bank Ltd">Parallex Bank Ltd</option>
                            <option value="Polaris Bank Plc">Polaris Bank Plc</option>
                            <option value="Premium Trust Bank">Premium Trust Bank</option>
                            <option value="Providus Bank Ltd">Providus Bank Ltd</option>
                            <option value="Stanbic IBTC Bank Plc">Stanbic IBTC Bank Plc</option>
                            <option value="Standard Chartered Bank Nigeria Ltd">Standard Chartered Bank Nigeria Ltd</option>
                            <option value="Sterling Bank Plc">Sterling Bank Plc</option>
                            <option value="SunTrust Bank Nigeria Ltd">SunTrust Bank Nigeria Ltd</option>
                            <option value="Titan Trust Bank Ltd">Titan Trust Bank Ltd</option>
                            <option value="Union Bank of Nigeria Plc">Union Bank of Nigeria Plc</option>
                            <option value="United Bank for Africa Plc">United Bank for Africa Plc</option>
                            <option value="Unity Bank Plc">Unity Bank Plc</option>
                            <option value="Wema Bank Plc">Wema Bank Plc</option>
                            <option value="Zenith Bank Plc">Zenith Bank Plc</option>
                            <!-- Digital Banks -->
                            <option value="Kuda Bank">Kuda Bank</option>
                            <option value="PalmPay">PalmPay</option>
                            <option value="FairMoney Microfinance Bank">FairMoney Microfinance Bank</option>
                            <option value="Sparkle Bank">Sparkle Bank</option>
                            <option value="Moniepoint Microfinance Bank">Moniepoint Microfinance Bank</option>
                            <option value="Opay">Opay</option>
                            <option value="Rubies Bank">Rubies Bank</option>
                            <option value="VFD Microfinance Bank">VFD Microfinance Bank</option>
                            <option value="Mint Finex MFB">Mint Finex MFB</option>
                            <option value="Mkobo MFB">Mkobo MFB</option>
                            <option value="Raven Bank">Raven Bank</option>
                            <option value="Rex Microfinance Bank">Rex Microfinance Bank</option>
                            <option value="CashX">CashX</option>
                            <!-- Microfinance Banks -->
                            <option value="AB Microfinance Bank Limited">AB Microfinance Bank Limited</option>
                            <option value="Accion Microfinance Bank">Accion Microfinance Bank</option>
                            <option value="LAPO Microfinance Bank">LAPO Microfinance Bank</option>
                            <option value="Mutual Trust Microfinance Bank">Mutual Trust Microfinance Bank</option>
                            <option value="Rephidim Microfinance Bank">Rephidim Microfinance Bank</option>
                            <option value="Empire Trust Microfinance Bank">Empire Trust Microfinance Bank</option>
                            <option value="Finca Microfinance Bank Limited">Finca Microfinance Bank Limited</option>
                            <option value="Moneyfield Microfinance Bank">Moneyfield Microfinance Bank</option>
                            <option value="Peace Microfinance Bank">Peace Microfinance Bank</option>
                            <option value="Infinity Microfinance Bank">Infinity Microfinance Bank</option>
                            <option value="Covenant Microfinance Bank Ltd">Covenant Microfinance Bank Ltd</option>
                            <option value="Solid Allianze Microfinance Bank">Solid Allianze Microfinance Bank</option>
                            <option value="Advans La Fayette Microfinance Bank">Advans La Fayette Microfinance Bank</option>
                            <!-- Add more banks as needed -->
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" id="account_number" name="account_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="account_name" class="form-label">Account Name</label>
                        <input type="text" id="account_name" name="account_name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="withdrawButton">
                        <span class="default-text">Request Withdrawal</span>
                        <span class="loading-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Processing...
                        </span>
                    </button>
                </form>
            </div>
        </div>


<div class="mt-5">
    <h4 class="fw-bold text-primary">Your Withdrawal History</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Amount</th>
                    <th>Bank Name</th>
                    <th>Account Number</th>
                    <th>Account Name</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $withdrawal)
                <tr>
                    <td class="fw-bold text-dark">₦{{ number_format($withdrawal->amount, 2) }}</td>
                    <td class="fw-bold text-dark">{{ ($withdrawal->bank_name) }}</td>
                    <td class="fw-bold text-dark">{{ ($withdrawal->account_number) }}</td>
                    <td class="fw-bold text-dark">{{ ($withdrawal->account_name) }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $withdrawal->status === 'ACCEPTED' ? 'success' : 
                            ($withdrawal->status === 'REJECTED' ? 'danger' : 'warning') 
                        }}">
                            {{ ucfirst(strtolower($withdrawal->status)) }}
                        </span>
                    </td>
                    <td class="fw-bold text-dark" style="max-width: 500px; min-width: 350px; word-wrap: break-word; white-space: normal;">{{ ($withdrawal->note) }}</td>
                    <td>
                        @if($withdrawal->receipt)
                            <div class="d-flex gap-2">
                                <a href="{{ $withdrawal->receipt }}" target="_blank" class="btn btn-sm btn-outline-success">
                                    🔍 View
                                </a>
                                <a href="{{ $withdrawal->receipt }}" download class="btn btn-sm btn-outline-secondary">
                                    ⬇️ Download
                                </a>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    </div>
         </div>
    </div>
  </main>
 
  <!--   Core JS Files   -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#withdrawForm').submit(function(e) {
            e.preventDefault();

            let amount = $('#amount').val();
            let bankName = $('#bank_name').val();
            let accountNumber = $('#account_number').val();
            let accountName = $('#account_name').val();
            let button = $('#withdrawButton');

            button.prop('disabled', true);
            button.find('.default-text').addClass('d-none');
            button.find('.loading-text').removeClass('d-none');

            $.ajax({
                url: "{{ route('user-withdraw') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    amount: amount,
                    bank_name: bankName,
                    account_number: accountNumber,
                    account_name: accountName
                },
                success: function(response) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON.message, 'error');
                },
                complete: function() {
                    button.prop('disabled', false);
                    button.find('.default-text').removeClass('d-none');
                    button.find('.loading-text').addClass('d-none');
                }
            });
        });
    });
</script>
<!-- JavaScript -->
<script>
    // Handle state change to fetch cities
    document.getElementById('state').addEventListener('change', function () {
        const stateName = this.value; // Get the selected state name
        const cityDropdown = document.getElementById('city');
        cityDropdown.innerHTML = '<option value="">Loading...</option>';

        if (stateName) {
            fetch(`/get-cities?state_name=${stateName}`)
                .then(response => response.json())
                .then(data => {
                    cityDropdown.innerHTML = '<option value="">Select a city</option>';
                    data.forEach(city => {
                        cityDropdown.innerHTML += `<option value="${city.name}">${city.name}</option>`;
                    });
                });
        } else {
            cityDropdown.innerHTML = '<option value="">Select a city</option>';
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const accountTypeSelect = document.getElementById('account_type');
        const sellerFields = document.getElementById('seller-fields');

        accountTypeSelect.addEventListener('change', function () {
            if (this.value === 'SELLER') {
                sellerFields.style.display = 'block';
                sellerFields.querySelectorAll('input, textarea').forEach(input => {
                    input.setAttribute('required', true);
                });
            } else {
                sellerFields.style.display = 'none';
                sellerFields.querySelectorAll('input, textarea').forEach(input => {
                    input.removeAttribute('required');
                });
            }
        });
    });
</script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          },
          {
            label: "Websites",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#3A416F",
            borderWidth: 3,
            backgroundColor: gradientStroke2,
            fill: true,
            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
            maxBarThickness: 6
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
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