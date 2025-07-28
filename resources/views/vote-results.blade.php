@include('components.header')
<body>
    @include('components.mobile-nav')
    @include('components.nav-link')

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Vote <strong>Results</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->
<!---Start of Contest banner--->
<!-- <section class="discount mt-3 mb-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="discount__pic">
                    <img src="{{ asset('https://res.cloudinary.com/di2ci6rz8/image/upload/v1748966428/notifications/xvt7rfxgvrx1frlxljjb.jpg') }}" style="height: 400px;" alt="Giveaway">
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="discount__text">
                    <div class="discount__text__title">
                        <span>Public Notice</span>
                        <h2>Giveaway Contest 2025</h2>
                        <h5><span>Join</span> & Win Big!</h5>
                    </div>
                    <p>
                        The 2025 Giveaway is live! Register now to compete and stand a chance to win exciting prizes 
                        like a fridge, iron, or toaster. Limited slots available – only 50 contestants allowed!
                    </p>
                    <div class="mt-4 d-flex">
                        <a href="#results"><button href="{{ url('results') }}" class="site-btn me-2">Live Results</button></a>
                         <a href="{{ route('dashboard') }}"><button  class="site-btn">Register</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!----End of contest banner---->

<!-- Vote + Results Section Begin -->
<section class="contact spad m-2" id="results">
    <div class="container">
        <div class="row justify-content-center">
            <!-- RESULTS TABLE -->
            <div class="col-lg-8">
                <div class="contact__content">
                    <div class="contact__form">
                        <h3 class="mb-4 text-center">🔥 Live Voting Results</h3>
                        <table class="table table-hover table-striped table-bordered rounded shadow-sm">
                            <thead class="table-dark" style="background: #053262;">
                                <tr class="text-center">
                                    <th>Contestant</th>
                                    <th>Votes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allContestants->sortByDesc('votes_count') as $person)
                                    <tr class="align-middle text-center">
                                        <td>
                                            <strong>{{ $person->name }}</strong>
                                            @if($loop->first)
                                                <span class="badge ms-2" style="background: #053262; color: white;">Leading</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $person->votes_count }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ url('vote/' . $person->unique_link) }}" class="site-btn">
                                                Vote
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <p class="text-muted text-center mt-3">Updated live as users vote.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Vote + Results Section End -->

    @include('components.footer')
</body>
</html>
