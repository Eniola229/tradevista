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

    <!-- Vote + Results Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
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
                                        <tr>
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
