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
                        <span>Vote for <strong>{{ $contestant->name }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Vote + Results Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <!-- VOTE FORM -->
                <div class="col-lg-6 col-md-6">
                    <div class="contact__content">
                        <div class="contact__form">
                            @if(session('status'))
                                <div class="alert alert-success text-green-800 bg-green-200 p-4 rounded mb-4">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-error text-red-800 bg-red-200 p-4 rounded mb-4">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger text-red-800 bg-red-200 p-4 rounded mb-4">
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <h2 class="mb-4">Vote for <strong>{{ $contestant->name }}</strong> to win!</h2>
                            <form method="POST" action="{{ route('vote.cast', $contestant->unique_link) }}">
                                @csrf
                                <button type="submit" class="site-btn">Vote Now</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- RESULTS TABLE -->
                <div class="col-lg-6 col-md-6">
                    <div class="contact__content">
                        <div class="contact__form">
                            <h4 class="mb-3">Live Voting Results</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Contestant</th>
                                        <th>Votes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allContestants as $person)
                                        <tr @if($person->id == $contestant->id) style="background-color: #f0f8ff;" @endif>
                                            <td>{{ $person->name }}</td>
                                            <td><strong>{{ $person->votes_count }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p class="text-muted">Updated live as users vote.</p>
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
