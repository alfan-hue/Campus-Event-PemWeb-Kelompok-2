<section id="for-organizers" aria-label="For organizations">
    <div class="hero text-white rounded-4 shadow p-4 p-lg-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge bg-white text-primary mb-3">For organizations</span>
                <h2 class="fw-bold mb-3">Organizing an event? Share it with the whole campus.</h2>
                <p class="mb-4" style="opacity: .9;">
                    Student unions, clubs, faculties, and committees can publish their activities on Campus Event
                    so students can find them, read the details, and register through your own link.
                </p>

                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <a href="" class="btn btn-light btn-lg text-primary fw-semibold">
                            Go to dashboard to add an event
                        </a>
                        {{-- <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg text-primary fw-semibold">
                            Go to dashboard to add an event
                        </a> --}}
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg text-primary fw-semibold">
                            Register your organization
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            Log in
                        </a>
                    @endauth
                </div>
                <p class="mt-3 mb-0">
                    <a href="{{ route('organizers.guide') }}" class="link-light">Read the full organizer guide &rarr;</a>
                </p>
            </div>

            <div class="col-lg-6">
                <div class="bg-white text-dark rounded-3 p-4">
                    <h3 class="h6 fw-bold text-uppercase text-secondary mb-3">How to get started</h3>

                    <div class="d-flex gap-3 mb-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">1</div>
                        <div>
                            <h4 class="h6 fw-semibold mb-1">Create an account</h4>
                            <p class="small text-secondary mb-0">Register with your organization's name and contact details.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">2</div>
                        <div>
                            <h4 class="h6 fw-semibold mb-1">Add your event</h4>
                            <p class="small text-secondary mb-0">Fill in the title, category, schedule, location, description, and your registration link.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">3</div>
                        <div>
                            <h4 class="h6 fw-semibold mb-1">Reach students</h4>
                            <p class="small text-secondary mb-0">Once published, your event appears under Happening Now or Upcoming Events and in category searches.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>