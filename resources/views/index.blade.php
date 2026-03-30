<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">

  <title>MANTRA</title>
  <link rel="icon" href="{{ asset('images/mantra.png') }}" type="image/png">


  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">


  <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('css/mantra.css') }}">
  <link rel="stylesheet" href="{{ asset('css/owl.css') }}">
  <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">

</head> --

<body>


  <!--header-->
  <header class="main-header clearfix" role="header">
    <div class="logo">
      <a href="#" style="display: flex; align-items: center;">
        <img src="{{ asset('images/mantra.png') }}" alt="Mantra Logo" style="height: 50px; margin-right: 15px;">
        <div style="text-align: left;">
          <em>MANTRA</em>
          <span class="tagline">Study. Focus. Achieve</span>
        </div>
      </a>
    </div>
    <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>
    <nav id="menu" class="main-nav" role="navigation">
      <ul class="main-menu">
        <li><a href="#section1">Home</a></li>
        <li class="has-submenu"><a href="#section2">About Us</a>
          <ul class="sub-menu">
            <li><a href="#section2">Why Mantra?</a></li>
            <li><a href="#section3">Join Us!</a></li>
            <li><a href="#section6">Feedback</a></li>
          </ul>
        </li>

        <li><a href="#section4">Courses</a></li>

        <!-- AUTH BASED MENU -->
        @auth
          <li><a href="{{ route('dashboard.intro') }}">Dashboard</a></li>
          <li>
            <a href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              Logout
            </a>
          </li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        @else
          <li><a href="{{ route('login') }}">Login</a></li>
          <li><a href="{{ route('register') }}">Register</a></li>
        @endauth
      </ul>
    </nav>
  </header>

  <!-- ***** Main Banner Area Start ***** -->
  <section class="section main-banner" id="top" data-section="section1">
    <video autoplay muted loop id="bg-video">
      <source src="images/course-video.mp4" type="video/mp4" />
    </video>

    <div class="video-overlay header-text">
      <div class="caption">
        <h6>Manage your School!</h6>
        <h2><em>MANTRA</em> Your Classroom</h2>
        <div class="main-button">
          <div class="scroll-to-section"><a href="#section5">Discover more</a></div>
        </div>
      </div>
    </div>
  </section>
  <!-- ***** Main Banner Area End ***** -->


  <section class="features">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-12">
          <div class="features-post">
            <div class="features-content">
              <div class="content-show">
                <h4><i class="fa fa-pencil"></i>what you do?</h4>
              </div>
              <div class="content-hide">
                <p>Add your courses, upload your textbooks or notes, highlight and study your way. Mood off? MANTRA
                  has your back — it picks the perfect topic or video for you, so learning always flows with your vibe.
                </p>
                <p class="hidden-sm">Add your courses, upload your textbooks or notes, highlight and study your way.
                  Mood off? MANTRA has your back — it picks the perfect topic or video for you, so learning always flows
                  with your vibe.
                </p>
                <div class="scroll-to-section"><a href="#section2">More Info.</a></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-12">
          <div class="features-post second-features">
            <div class="features-content">
              <div class="content-show">
                <h4><i class="fa fa-graduation-cap"></i>Virtual Class</h4>
              </div>
              <div class="content-hide">
                <p>MANTRA is a virtual classroom that puts you in control of your learning. With MANTRA, you can
                  study at your own pace, on your own schedule, and in the comfort of your own home.</p>
                <p class="hidden-sm">MANTRA is a virtual classroom that puts you in control of your learning. With
                  MANTRA, you can
                  study at your own pace, on your own schedule, and in the comfort of your own home.</p>
                <div class="scroll-to-section"><a href="#section3">Details</a></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-12">
          <div class="features-post third-features">
            <div class="features-content">
              <div class="content-show">
                <h4><i class="fa fa-book"></i>Study Time </h4>
              </div>
              <div class="content-hide">
                <p>Set your study time — even 5 minutes counts! Play games, explore mind maps, and make learning fun.
                  MANTRA turns every study session into a focus-powered, mood-friendly experience.</p>
                <p class="hidden-sm">Set your study time — even 5 minutes counts! Play games, explore mind maps, and
                  make learning fun. MANTRA turns every study session into a focus-powered, mood-friendly experience.
                </p>
                <div class="scroll-to-section"><a href="#section4">Read More</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section why-us" data-section="section2">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="section-heading">
            <h2>Why choose MANTRA?</h2>
          </div>
        </div>
        <div class="col-md-12">
          <div id='tabs'>
            <ul>
              <li><a href='#tabs-1'>Your Way</a></li>
              <li><a href='#tabs-2'>Your Time</a></li>
              <li><a href='#tabs-3'>Your Fun</a></li>
            </ul>
            <section class='tabs-content'>
              <article id='tabs-1'>
                <div class="row">
                  <div class="col-md-6">
                    <img src="images/choose-us-image-01.png" alt="">
                  </div>
                  <div class="col-md-6">
                    <h4>Your Way</h4>
                    <p>MANTRA puts you in control of your learning. With MANTRA, you can study at your own pace, on your
                      own schedule, and in the comfort of your own home.</p>
                  </div>
                </div>
              </article>
              <article id='tabs-2'>
                <div class="row">
                  <div class="col-md-6">
                    <img src="images/choose-us-image-02.png" alt="">
                  </div>
                  <div class="col-md-6">
                    <h4>Your Time</h4>
                    <p>Take control of your study time. With MANTRA, even 5 minutes can be powerful — play games,
                      explore mind maps, and learn at your own pace, whenever and wherever you want.</p>
                  </div>
                </div>
              </article>
              <article id='tabs-3'>
                <div class="row">
                  <div class="col-md-6">
                    <img src="images/choose-us-image-03.png" alt="">
                  </div>
                  <div class="col-md-6">
                    <h4>Your Fun</h4>
                    <p>MANTRA turns every study session into a focus-powered, mood-friendly experience. Play games,
                      explore mind maps, and make learning fun.</p>
                  </div>
                </div>
              </article>
            </section>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section coming-soon" data-section="section3">
    <div class="container">
      <div class="row">
        <div class="col-md-7 col-xs-12">
          <div class="continer centerIt">
            <div>
              <h4>Add your <em>course</em> and start learning!</h4>
              <div class="counter">

                <div class="days">
                  <div class="value">00</div>
                  <span>Days</span>
                </div>

                <div class="hours">
                  <div class="value">00</div>
                  <span>Hours</span>
                </div>

                <div class="minutes">
                  <div class="value">00</div>
                  <span>Minutes</span>
                </div>

                <div class="seconds">
                  <div class="value">00</div>
                  <span>Seconds</span>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="right-content">
            <div class="top-content">
              <h6>Register your free account and <em>get immediate</em> access to your classroom!</h6>
            </div>
            <form id="registration-form" action="{{ route('register.post') }}#registration-form" method="POST">
              @csrf
              <div class="row">
                <div class="col-md-12">
                  <fieldset>
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                      id="reg-name" placeholder="User ID (must be unique)" required="" value="{{ old('name') }}">
                    @error('name')
                      <span class="text-danger"
                        style="font-size: 12px; margin-top: 5px; display: block; text-align: left;">{{ $message }}</span>
                    @enderror
                  </fieldset>
                </div>
                <div class="col-md-12">
                  <fieldset>
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                      id="reg-email" placeholder="Your Email" required="" value="{{ old('email') }}">
                    @error('email')
                      <span class="text-danger"
                        style="font-size: 12px; margin-top: 5px; display: block; text-align: left;">{{ $message }}</span>
                    @enderror
                  </fieldset>
                </div>
                <div class="col-md-12">
                  <fieldset>
                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror"
                      id="reg-password" placeholder="Password" required="">
                    @error('password')
                      <span class="text-danger"
                        style="font-size: 12px; margin-top: 5px; display: block; text-align: left;">{{ $message }}</span>
                    @enderror
                  </fieldset>
                </div>
                <div class="col-md-12">
                  <fieldset>
                    <input name="password_confirmation" type="password" class="form-control" id="reg-password-confirm"
                      placeholder="Confirm Password" required="">
                  </fieldset>
                </div>
                <div class="col-md-12">
                  <fieldset>
                    <button type="submit" id="form-submit" class="button">REGISTER NOW</button>
                  </fieldset>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section courses" data-section="section4" id="section4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="section-heading">
            <h2>Start journey</h2>
          </div>
        </div>
        <div class="owl-carousel owl-theme course-slider">
          <div class="item">
            <img src="https://avatars.githubusercontent.com/u/111867189?s=200&v=4" alt="Bro Code"
              style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Bro Code</h4>
              <p>Coding tutorials on Python, Java, C#, C++, JavaScript, HTML, CSS, React, and more. Simple and easy to
                understand.</p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img
              src="https://raw.githubusercontent.com/freeCodeCamp/design-style-guide/main/downloads/fcc_primary_small.png"
              alt="FreeCodeCamp" style="height: 250px; object-fit: contain; background: #0a0a23; padding: 20px;">
            <div class="down-content">
              <h4>freeCodeCamp.org</h4>
              <p>Learn to code for free. Build projects. Earn certifications. One of the best resources for web
                development.</p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/crashcourse" alt="CrashCourse"
              style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>CrashCourse</h4>
              <p>Tons of awesome courses in one awesome channel! History, Science, Literature, Psychology, and more.</p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/khanacademy" alt="Khan Academy"
              style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Khan Academy</h4>
              <p>A nonprofit with the mission to provide a free, world-class education for anyone, anywhere. Math,
                science, and more.</p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/github/bradtraversy" alt="Traversy Media"
              style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Traversy Media</h4>
              <p>Web development tutorials for all latest web technologies including React, Vue, Angular, Node.js and
                more.</p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/coursera" alt="Coursera" style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Coursera</h4>
              <p>Learn from the world's best universities and companies. Expand your knowledge and advance your career.
              </p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/edurekaIN" alt="Edureka" style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Edureka!</h4>
              <p>Instructor-led live online training on trending technologies like DevOps, Big Data, Data Science, and
                more.</p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/cs50" alt="CS50" style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>CS50</h4>
              <p>Harvard University's introduction to the intellectual enterprises of computer science and the art of
                programming.</p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/TEDEd" alt="Ted-Ed" style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>TED-Ed</h4>
              <p>TED's youth and education initiative. Short, award-winning animated lessons on various curious topics.
              </p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/PhysicsWallah" alt="Physics Wallah"
              style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Physics Wallah</h4>
              <p>One of India's most loved education platforms. Best for 10th, 12th Boards, JEE, and NEET preparation.
              </p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/unacademy" alt="Unacademy" style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Unacademy</h4>
              <p>Comprehensive exam preparation for CBSE Class 6-12, JEE, NEET, UPSC, and more with top educators.
              </p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/Vedantu" alt="Vedantu" style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>Vedantu</h4>
              <p>Interactive online classes for Grades 6-12, JEE, NEET. Known for LIVE interactive classes and
                specialized content.
              </p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
          <div class="item">
            <img src="https://unavatar.io/youtube/Learnohub" alt="LearnOHub" style="height: 250px; object-fit: cover;">
            <div class="down-content">
              <h4>LearnOHub - Class 11 & 12</h4>
              <p>Free education revolution (formerly ExamFear). Math, Physics, Chemistry, Biology concepts made
                ridiculously simple.
              </p>
              <div class="text-button-free">
                <a href="{{ route('watch.redirect') }}">Watch <i class="fa fa-angle-double-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <section class="section video" data-section="section5" id="section5">
    <div class="container">
      <div class="row">
        <div class="col-md-6 align-self-center">
          <div class="left-content">
            <span>our presentation is for you</span>
            <h4>Watch the video to learn more <em>about Mantra</em></h4>
            <p>Mantra is a platform that helps you to manage your studies.
              You may <a rel="nofollow" href="https://www.linkedin.com/in/aneeta-peter-1499a3364/?originalSubdomain=in"
                target="_blank">contact Mantra</a>
              for details.
              <br><br>For more information, please visit our <a
                href="https://www.linkedin.com/in/aneeta-peter-1499a3364/?originalSubdomain=in"
                target="_blank">website</a>.
            </p>
            <div class="main-button"><a rel="nofollow"
                href="https://www.linkedin.com/in/aneeta-peter-1499a3364/?originalSubdomain=in" target="_blank">External
                URL</a></div>
          </div>
        </div>
        <div class="col-md-6">
          <article class="video-item">
            <div class="video-caption">
              <h4><a href="https://www.youtube.com/watch?v=w_zUv4MlrOk" class="play"
                  style="color: black; text-decoration: none;">Mantra</a></h4>
            </div>
            <figure>
              <a href="https://www.youtube.com/watch?v=w_zUv4MlrOk" class="play"><img src="images/main-thumb.png"></a>
            </figure>
          </article>
        </div>
      </div>
    </div>
  </section>

  <section class="section contact" data-section="section6">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="section-heading">
            <h2>Contact Mantra</h2>
          </div>
        </div>
        <div class="col-md-6">

          <form id="contact" action="{{ route('contact.send') }}#contact" method="post">
            @csrf

            @if(session('contact_success'))
              <div class="alert alert-success"
                style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                {{ session('contact_success') }}
              </div>
            @endif

            @if(session('contact_error'))
              <div class="alert alert-danger"
                style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                {{ session('contact_error') }}
              </div>
            @endif

            <div class="row">
              <div class="col-md-6">
                <fieldset>
                  <input name="name" type="text" class="form-control" id="name" placeholder="Your Name" required=""
                    value="{{ old('name') }}">
                  @error('name')
                    <span class="text-danger"
                      style="font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                  @enderror
                </fieldset>
              </div>
              <div class="col-md-6">
                <fieldset>
                  <input name="email" type="text" class="form-control" id="email" placeholder="Your Email" required=""
                    value="{{ old('email') }}">
                  @error('email')
                    <span class="text-danger"
                      style="font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                  @enderror
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                  <textarea name="message" rows="6" class="form-control" id="message" placeholder="Your message..."
                    required="">{{ old('message') }}</textarea>
                  @error('message')
                    <span class="text-danger"
                      style="font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                  @enderror
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="button">Send Message Now</button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <div id="map">
            <img src="images/main-thumb.png" alt="Mantra Location"
              style="width: 100%; height: 422px; object-fit: cover; border-radius: 5px;">
          </div>
        </div>
      </div>
    </div>
  </section>




  <!-- Profile Sidebar -->
  <div id="profile-sidebar" class="sidebar-panel">
    <div class="sidebar-header">
      <h3>Mantra Profile</h3>
      <button id="close-sidebar" style="background:none; border:none; font-size: 24px; color:white;">&times;</button>
    </div>
    <div class="sidebar-content" style="padding: 20px;">
      <p style="color: white; margin-bottom: 10px;">What are you looking for from Mantra?</p>
      <textarea id="user-goals" placeholder="I want to improve my focus in..."
        style="width: 100%; height: 100px; margin-bottom: 20px; border-radius: 5px; padding: 10px;"></textarea>

      <div class="auth-form-sidebar">
        <h5 style="color:white; margin-bottom:15px;">Login to Dashboard</h5>
        <form id="sidebar-login-form">
          <input type="email" id="sidebar-email" class="form-control" placeholder="Email" required
            style="margin-bottom: 10px; background: rgba(255,255,255,0.1); border:none; color:white;">
          <input type="password" id="sidebar-password" class="form-control" placeholder="Password" required
            style="margin-bottom: 15px; background: rgba(255,255,255,0.1); border:none; color:white;">
          <button type="submit" class="btn btn-warning btn-block">Login</button>
        </form>
        <p style="color: rgba(255,255,255,0.5); font-size: 12px; margin-top: 10px; text-align: center;">
          New here? <a href="register.html" style="color: #f5a425;">Register</a>
        </p>
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <p><i class="fa fa-copyright"></i> by Mantra

            | Design: <a href="https://templatemo.com" rel="sponsored" target="_parent">Aneeta peter</a><br>
            Distributed By: <a href="https://themewagon.com" rel="sponsored" target="_blank">Rajagiri College of Social
              Science (Autonomous)</a>

          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

  <script src="{{ asset('js/isotope.min.js') }}"></script>
  <script src="{{ asset('js/owl-carousel.js') }}"></script>
  <script src="{{ asset('js/lightbox.js') }}"></script>
  <script src="{{ asset('js/tabs.js') }}"></script>
  <script src="{{ asset('js/video.js') }}"></script>
  <script src="{{ asset('js/slick-slider.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  <script src="{{ asset('js/auth.js') }}"></script>


  <!--We must allow submenu clicks to scroll.-->
  <script>
    $('.sub-menu a').on('click', function (e) {
      e.preventDefault();
      const target = $(this).attr('href');
      $('html, body').animate({
        scrollTop: $('.section[data-section="' + target.replace('#', '') + '"]').offset().top
      }, 800);
    });
  </script>
  <script>
    $(document).ready(function () {
      $('.has-submenu > a').on('click', function (e) {
        e.preventDefault(); // stop jumping
        $(this).next('.sub-menu').slideToggle();
      });
    });
  </script>
  <!--//-------------------------------------------------------------------------
   Fade-in on scroll script -->
  <script>
    $(document).ready(function () {

      // Add fade-in class to sections
      $('.section, .features-post, .item, .left-content, .right-content').addClass('fade-in');

      function revealOnScroll() {
        $('.fade-in').each(function () {
          const top = $(this).offset().top;
          const scroll = $(window).scrollTop();
          const windowHeight = $(window).height();

          if (scroll + windowHeight - 100 > top) {
            $(this).addClass('show');
          }
        });
      }

      revealOnScroll();
      $(window).on('scroll', revealOnScroll);

    });
  </script>
  <script>
    $(document).ready(function () {

      $('.course-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        center: true,
        autoplay: true,
        autoplayTimeout: 1500,
        autoplayHoverPause: true,
        dots: true,
        responsive: {
          0: { items: 1 },
          600: { items: 2 },
          1000: { items: 3 }
        }
      });

    });
  </script>

  <!--==============================================================-->
  <script>
    $(document).ready(function () {

      $('.owl-carousel .item').on('mousemove', function (e) {
        const card = $(this).find('.down-content')[0];

        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = ((y - centerY) / centerY) * 8;   // up-down tilt
        const rotateY = ((x - centerX) / centerX) * -8;  // left-right tilt

        card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
      });

      $('.owl-carousel .item').on('mouseleave', function () {
        const card = $(this).find('.down-content')[0];
        card.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)';
      });

    });
  </script>


  <!-- ═══════════════════════════════════════════════════════
       PREMIUM ANIMATION LAYER — does not change any content
  ═══════════════════════════════════════════════════════ -->
  <style>
    /* ── Entrance animation base ── */
    [data-ani] {
      opacity: 0;
      transition: opacity 0.7s ease, transform 0.7s cubic-bezier(.22, 1, .36, 1);
      will-change: opacity, transform;
    }

    [data-ani="up"] {
      transform: translateY(36px);
    }

    [data-ani="down"] {
      transform: translateY(-24px);
    }

    [data-ani="left"] {
      transform: translateX(-36px);
    }

    [data-ani="right"] {
      transform: translateX(36px);
    }

    [data-ani="scale"] {
      transform: scale(0.88);
    }

    [data-ani="fade"] {
      transform: none;
    }

    [data-ani].ani-done {
      opacity: 1;
      transform: translate(0) scale(1);
    }

    /* ── Stagger children ── */
    [data-stagger]>* {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.55s ease, transform 0.55s cubic-bezier(.22, 1, .36, 1);
    }

    [data-stagger].ani-done>* {
      opacity: 1;
      transform: none;
    }

    /* ── Hero text typewriter cursor ── */
    .mantra-cursor {
      display: inline-block;
      width: 2px;
      height: 1em;
      background: #f5a425;
      vertical-align: middle;
      margin-left: 3px;
      animation: cursorBlink 0.85s step-end infinite;
    }

    @keyframes cursorBlink {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0;
      }
    }

    /* ── Counter roll up ── */
    .counter-value {
      display: inline-block;
    }

    /* ── Section heading underline draw ── */
    .section-heading h2 {
      position: relative;
      display: inline-block;
    }

    .section-heading h2::after {
      content: '';
      position: absolute;
      left: 50%;
      bottom: -6px;
      transform: translateX(-50%);
      width: 0;
      height: 2px;
      background: #f5a425;
      transition: width 0.8s 0.3s cubic-bezier(.22, 1, .36, 1);
    }

    .section-heading.ani-done h2::after {
      width: 60%;
    }

    /* ── Feature card hover lift ── */
    .features-post {
      transition: transform 0.35s ease, box-shadow 0.35s ease !important;
    }

    .features-post:hover {
      transform: translateY(-6px) !important;
      box-shadow: 0 16px 40px rgba(245, 164, 37, 0.12) !important;
    }

    /* ── Carousel item entrance polish ── */
    .item {
      transition: transform 0.3s ease !important;
    }

    /* ── Nav link hover underline ── */
    .main-nav .main-menu>li>a {
      position: relative;
      overflow: hidden;
    }

    .main-nav .main-menu>li>a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 1px;
      background: #f5a425;
      transition: width 0.3s ease;
    }

    .main-nav .main-menu>li>a:hover::after {
      width: 100%;
    }

    /* ── Smooth logo entrance ── */
    .main-header .logo {
      animation: headerLogoIn 0.8s 0.1s cubic-bezier(.22, 1, .36, 1) both;
    }

    @keyframes headerLogoIn {
      from {
        opacity: 0;
        transform: translateX(-24px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* ── Nav links stagger in ── */
    .main-nav .main-menu>li {
      opacity: 0;
      animation: navItemIn 0.5s cubic-bezier(.22, 1, .36, 1) forwards;
    }

    .main-nav .main-menu>li:nth-child(1) {
      animation-delay: 0.2s;
    }

    .main-nav .main-menu>li:nth-child(2) {
      animation-delay: 0.3s;
    }

    .main-nav .main-menu>li:nth-child(3) {
      animation-delay: 0.4s;
    }

    .main-nav .main-menu>li:nth-child(4) {
      animation-delay: 0.5s;
    }

    .main-nav .main-menu>li:nth-child(5) {
      animation-delay: 0.6s;
    }

    @keyframes navItemIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ── Hero banner text ── */
    .video-overlay .caption h6 {
      animation: heroTextIn 0.7s 0.4s ease both;
    }

    .video-overlay .caption h2 {
      animation: heroTextIn 0.7s 0.65s ease both;
    }

    .video-overlay .caption .main-button {
      animation: heroTextIn 0.7s 0.9s ease both;
    }

    @keyframes heroTextIn {
      from {
        opacity: 0;
        transform: translateY(28px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {

      /* ── Intersection Observer for scroll reveals ── */
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            var el = entry.target;
            var delay = parseFloat(el.dataset.delay || 0);
            setTimeout(function () {
              el.classList.add('ani-done');
            }, delay * 1000);
            io.unobserve(el);

            // If stagger, stagger children
            if (el.hasAttribute('data-stagger')) {
              var children = el.children;
              Array.from(children).forEach(function (child, i) {
                setTimeout(function () {
                  child.style.transition = 'opacity 0.55s ease ' + (i * 0.1 + delay) + 's, transform 0.55s cubic-bezier(.22,1,.36,1) ' + (i * 0.1 + delay) + 's';
                }, 0);
              });
            }
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

      /* ── Tag elements automatically ── */
      document.querySelectorAll('.section-heading').forEach(function (el) {
        el.setAttribute('data-ani', 'up');
        io.observe(el);
      });

      document.querySelectorAll('.features-post').forEach(function (el, i) {
        el.setAttribute('data-ani', 'up');
        el.setAttribute('data-delay', (i * 0.15).toString());
        io.observe(el);
      });

      document.querySelectorAll('.left-content, .right-content').forEach(function (el, i) {
        el.setAttribute('data-ani', i % 2 === 0 ? 'left' : 'right');
        io.observe(el);
      });

      document.querySelectorAll('.top-content').forEach(function (el) {
        el.setAttribute('data-ani', 'up');
        io.observe(el);
      });

      /* ── Section headings with underline ── */
      document.querySelectorAll('.section-heading').forEach(function (el) {
        io.observe(el);
      });

      /* ── Counter roll-up for countdown values ── */
      function animateCounter(el, target, duration) {
        var start = 0;
        var step = target / (duration / 16);
        function tick() {
          start = Math.min(start + step, target);
          el.textContent = Math.round(start);
          if (start < target) requestAnimationFrame(tick);
        }
        tick();
      }

      var counterDone = false;
      var counterSection = document.querySelector('.section.coming-soon');
      if (counterSection) {
        var counterIo = new IntersectionObserver(function (entries) {
          if (entries[0].isIntersecting && !counterDone) {
            counterDone = true;
            document.querySelectorAll('.counter .value').forEach(function (el) {
              var val = parseInt(el.textContent) || 0;
              el.textContent = '0';
              animateCounter(el, val, 1500);
            });
          }
        }, { threshold: 0.3 });
        counterIo.observe(counterSection);
      }

      /* ── Contact section ── */
      var contactSection = document.querySelector('.section.contact');
      if (contactSection) {
        contactSection.setAttribute('data-ani', 'up');
        io.observe(contactSection);
      }

      /* ── Video section ── */
      var videoSection = document.querySelector('.section.video');
      if (videoSection) {
        videoSection.setAttribute('data-ani', 'fade');
        io.observe(videoSection);
      }

    });
  </script>

</body>

</html>