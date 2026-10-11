<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Organizer Guide · Campus Event</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body { font-family: 'Nunito', sans-serif; }
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #0a4fc4 100%); }
        .icon-circle { width: 3rem; height: 3rem; flex-shrink: 0; }
        html { scroll-behavior: smooth; }
        section[id] { scroll-margin-top: 5rem; }
    </style>
</head>
<body class="bg-light text-dark">



{{-- Hero --}}
<header class="hero text-white py-5">
    <div class="container py-lg-3 text-center">
        <span class="badge bg-white text-primary mb-3">Organizer guide</span>
        <h1 class="display-5 fw-bold mb-3">Share your event with the whole campus</h1>
        <p class="lead mb-4 mx-auto" style="max-width: 44rem; opacity: .9;">
            A step-by-step guide for student unions, clubs, faculties, and committees on how to publish
            events on Campus Event and get students to register.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            @auth
                <a href="" class="btn btn-light btn-lg text-primary fw-semibold">Go to dashboard</a>
                {{-- <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg text-primary fw-semibold">Go to dashboard</a> --}}
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg text-primary fw-semibold">Register your organization</a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Log in</a>
            @endauth
        </div>
    </div>
</header>

<main class="container py-5">
    <div class="row g-5">

        {{-- Side navigation (desktop) --}}
        <aside class="col-lg-3 d-none d-lg-block">
            <div class="position-sticky" style="top: 5.5rem;">
                <h2 class="h6 text-uppercase text-secondary fw-bold mb-3">On this page</h2>
                <div class="list-group list-group-flush">
                    <a href="#steps" class="list-group-item list-group-item-action border-0 px-0 bg-transparent">Getting started</a>
                    <a href="#prepare" class="list-group-item list-group-item-action border-0 px-0 bg-transparent">What to prepare</a>
                    <a href="#fields" class="list-group-item list-group-item-action border-0 px-0 bg-transparent">Event information</a>
                    <a href="#tips" class="list-group-item list-group-item-action border-0 px-0 bg-transparent">Tips for better reach</a>
                    <a href="#faq" class="list-group-item list-group-item-action border-0 px-0 bg-transparent">FAQ</a>
                </div>
            </div>
        </aside>

        <div class="col-lg-9">

            {{-- Steps --}}
            <section id="steps" class="mb-5">
                <h2 class="h3 fw-bold mb-1">Getting started</h2>
                <p class="text-secondary mb-4">Publishing an event takes four steps.</p>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4 d-flex gap-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">1</div>
                        <div>
                            <h3 class="h5 fw-semibold">Create an organizer account</h3>
                            <p class="text-secondary mb-0">
                                Click <strong>Register</strong> and sign up with your organization's name and an email address
                                that your team can access. If you already have an account, simply log in.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4 d-flex gap-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">2</div>
                        <div>
                            <h3 class="h5 fw-semibold">Open your dashboard</h3>
                            <p class="text-secondary mb-0">
                                After logging in, go to the <strong>Dashboard</strong>. This is where you add new events
                                and manage the ones you have already published.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4 d-flex gap-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">3</div>
                        <div>
                            <h3 class="h5 fw-semibold">Add your event details</h3>
                            <p class="text-secondary mb-0">
                                Choose <strong>Add event</strong> and complete the form: title, category, date and time,
                                location, description, and your registration link. See the table below for what each field is for.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 d-flex gap-3">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold">4</div>
                        <div>
                            <h3 class="h5 fw-semibold">Publish and share</h3>
                            <p class="text-secondary mb-0">
                                Once saved, your event shows up under <strong>Happening Now</strong> or
                                <strong>Upcoming Events</strong> and can be found through search and category filters.
                                Students will use your registration link to sign up.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- What to prepare --}}
            <section id="prepare" class="mb-5">
                <h2 class="h3 fw-bold mb-1">What to prepare</h2>
                <p class="text-secondary mb-4">Have these ready before you start so the form takes just a few minutes.</p>

                <div class="card border-0 shadow-sm">
                    <ul class="list-group list-group-flush">
                        @foreach ([
                            'A clear event title that says what the event is',
                            'The category that fits best (for example Seminar, Workshop, or Competition)',
                            'Start and end date, plus the time',
                            'The venue or meeting point on campus',
                            'A short description: what happens, who it is for, and what to bring',
                            'A working registration link (such as a form or ticketing page)',
                            'The name of your organization as you want it shown',
                        ] as $item)
                            <li class="list-group-item d-flex align-items-start gap-3 p-3">
                                <svg class="text-success mt-1" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>

            {{-- Fields --}}
            <section id="fields" class="mb-5">
                <h2 class="h3 fw-bold mb-1">Event information</h2>
                <p class="text-secondary mb-4">This is what students see on your event card and details page.</p>

                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="p-3">Field</th>
                                    <th scope="col" class="p-3">What to enter</th>
                                    <th scope="col" class="p-3">Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" class="p-3">Title</th>
                                    <td class="p-3">A short, descriptive name.</td>
                                    <td class="p-3 text-secondary">Intro to Web Development Workshop</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="p-3">Category</th>
                                    <td class="p-3">Pick the closest match so students can filter for it.</td>
                                    <td class="p-3 text-secondary">Workshop</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="p-3">Date &amp; time</th>
                                    <td class="p-3">When the event starts and ends.</td>
                                    <td class="p-3 text-secondary">22 Oct 2026, 09:00 – 12:00</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="p-3">Location</th>
                                    <td class="p-3">Building and room, or a clear meeting point.</td>
                                    <td class="p-3 text-secondary">Computer Lab 2</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="p-3">Description</th>
                                    <td class="p-3">What the event is about, who can join, and anything to bring.</td>
                                    <td class="p-3 text-secondary">Hands-on beginner workshop…</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="p-3">Registration link</th>
                                    <td class="p-3">The full URL students should open to sign up.</td>
                                    <td class="p-3 text-secondary">https://example.com/register/…</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- Tips --}}
            <section id="tips" class="mb-5">
                <h2 class="h3 fw-bold mb-1">Tips for better reach</h2>
                <p class="text-secondary mb-4">Small details make events easier to find and more likely to fill up.</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h3 class="h6 fw-bold">Publish early</h3>
                                <p class="text-secondary small mb-0">Add your event at least one to two weeks ahead so students can plan around it.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h3 class="h6 fw-bold">Lead with the key facts</h3>
                                <p class="text-secondary small mb-0">Put what, who, and why in the first sentence of the description. Cards show only a short preview.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h3 class="h6 fw-bold">Test your registration link</h3>
                                <p class="text-secondary small mb-0">Open it in a private window to confirm students can sign up without logging in to anything extra.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h3 class="h6 fw-bold">Keep details up to date</h3>
                                <p class="text-secondary small mb-0">If the time or location changes, update the event from your dashboard so students see the latest info.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- FAQ --}}
            <section id="faq" class="mb-5">
                <h2 class="h3 fw-bold mb-4">Frequently asked questions</h2>

                <div class="accordion shadow-sm" id="faqAccordion">
                    <div class="accordion-item border-0 border-bottom">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                Does Campus Event handle registration?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                No. Campus Event is where students discover your event. Registration happens through the
                                link you provide, so you keep full control of your sign-up process.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                Can I edit an event after publishing it?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Yes. Open your dashboard, choose the event, and update any detail, including the date,
                                location, or registration link.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                Can more than one person manage my organization's events?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Use one shared organization account with an email your team can access, so anyone responsible
                                for publicity can log in and manage events.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                What if my event is cancelled or finished?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Update or remove it from your dashboard so students are not directed to an event that is no
                                longer happening.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Final CTA --}}
            <section class="mb-2">
                <div class="hero text-white rounded-4 shadow p-4 p-lg-5 text-center">
                    <h2 class="fw-bold mb-2">Ready to share your event?</h2>
                    <p class="mb-4" style="opacity: .9;">It only takes a few minutes to reach students across campus.</p>
                    @auth
                        <a href="" class="btn btn-light btn-lg text-primary fw-semibold">Go to dashboard</a>
                        {{-- <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg text-primary fw-semibold">Go to dashboard</a> --}}
                    @else
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg text-primary fw-semibold me-1">Register your organization</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Log in</a>
                    @endauth
                </div>
            </section>

        </div>
    </div>
</main>

<footer class="bg-white border-top py-4 text-center small text-secondary">
    <div class="container">
        <p class="mb-0">Campus Event &copy; {{ date('Y') }} &middot; All campus activities, one place.</p>
    </div>
</footer>

</body>
</html>