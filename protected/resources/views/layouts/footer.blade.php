
<!-- Footer
================================================== -->
<div id="footer">
  
  <!-- Footer Top Section -->
  <div class="footer-top-section">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">

          <!-- Footer Rows Container -->
          <div class="footer-rows-container">
            
            <!-- Left Side -->
            <div class="footer-rows-left">
              <div class="footer-row">
                <div class="footer-row-inner footer-logo">
                  <img src="images/logo2.png" alt="">
                </div>
              </div>
            </div>
            
            <!-- Right Side -->
            <div class="footer-rows-right">

              <!-- Social Icons -->
              <div class="footer-row">
                <div class="footer-row-inner">
                  <ul class="footer-social-links">
                    <li>
                      <a href="#" title="Facebook" data-tippy-placement="bottom" data-tippy-theme="light">
                        <i class="icon-brand-facebook-f"></i>
                      </a>
                    </li>
                    <li>
                      <a href="#" title="Twitter" data-tippy-placement="bottom" data-tippy-theme="light">
                        <i class="icon-brand-twitter"></i>
                      </a>
                    </li>
                    <li>
                      <a href="#" title="Google Plus" data-tippy-placement="bottom" data-tippy-theme="light">
                        <i class="icon-brand-google-plus-g"></i>
                      </a>
                    </li>
                    <li>
                      <a href="#" title="LinkedIn" data-tippy-placement="bottom" data-tippy-theme="light">
                        <i class="icon-brand-linkedin-in"></i>
                      </a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
              </div>
              
              <!-- Language Switcher -->
              <div class="footer-row">
                <div class="footer-row-inner">
                  <select class="selectpicker language-switcher" data-selected-text-format="count" data-size="5">
                    <option selected>English</option>
                    <option>Français</option>
                    <option>Español</option>
                    <option>Deutsch</option>
                  </select>
                </div>
              </div>
            </div>

          </div>
          <!-- Footer Rows Container / End -->
        </div>
      </div>
    </div>
  </div>
  <!-- Footer Top Section / End -->

  <!-- Footer Middle Section -->
  <div class="footer-middle-section">
    <div class="container">
      <div class="row">

        <!-- Links -->
        <div class="col-xl-2 col-lg-2 col-md-3">
          <div class="footer-links">
            <h3>For Candidates</h3>
            <ul>
              <li><a href="#"><span>Browse Jobs</span></a></li>
              <li><a href="#"><span>Add Resume</span></a></li>
              <li><a href="#"><span>Job Alerts</span></a></li>
              <li><a href="#"><span>My Bookmarks</span></a></li>
            </ul>
          </div>
        </div>

        <!-- Links -->
        <div class="col-xl-2 col-lg-2 col-md-3">
          <div class="footer-links">
            <h3>For Employers</h3>
            <ul>
              <li><a href="#"><span>Browse Candidates</span></a></li>
              <li><a href="#"><span>Post a Job</span></a></li>
              <li><a href="#"><span>Post a Task</span></a></li>
              <li><a href="#"><span>Plans & Pricing</span></a></li>
            </ul>
          </div>
        </div>

        <!-- Links -->
        <div class="col-xl-2 col-lg-2 col-md-3">
          <div class="footer-links">
            <h3>Helpful Links</h3>
            <ul>
              <li><a href="#"><span>Contact</span></a></li>
              <li><a href="#"><span>Privacy Policy</span></a></li>
              <li><a href="#"><span>Terms of Use</span></a></li>
            </ul>
          </div>
        </div>

        <!-- Links -->
        <div class="col-xl-2 col-lg-2 col-md-3">
          <div class="footer-links">
            <h3>Account</h3>
            <ul>
              <li><a href="#"><span>Log In</span></a></li>
              <li><a href="#"><span>My Account</span></a></li>
            </ul>
          </div>
        </div>

        <!-- Newsletter -->
        <div class="col-xl-4 col-lg-4 col-md-12">
          <h3><i class="icon-feather-mail"></i> Sign Up For a Newsletter</h3>
          <p>Weekly breaking news, analysis and cutting edge advices on job searching.</p>
          <form action="#" method="get" class="newsletter">
            <input type="text" name="fname" placeholder="Enter your email address">
            <button type="submit"><i class="icon-feather-arrow-right"></i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer Middle Section / End -->
  
  <!-- Footer Copyrights -->
  <div class="footer-bottom-section">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          © 2018 <strong>Collvoy</strong>. All Rights Reserved.
        </div>
      </div>
    </div>
  </div>
  <!-- Footer Copyrights / End -->

</div>
<!-- Footer / End -->


<!-- Sign In Popup
================================================== -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

  <!--Tabs -->
  <div class="sign-in-form">

    <ul class="popup-tabs-nav">
      <li><a href="#login">Log In</a></li>
      <li><a href="#register">Register</a></li>
    </ul>

    <div class="popup-tabs-container">

      <!-- Login -->
      <div class="popup-tab-content" id="login">
        
        <!-- Welcome Text -->
        <div class="welcome-text">
          <h3>We're glad to see you again!</h3>
          <span>Don't have an account? <a href="#" class="register-tab">Sign Up!</a></span>
        </div>
        <!-- Form -->
       <form method="post" id="login-form" action="{{ URL::to('login') }}">
          @csrf
          <div class="input-with-icon-left">
            <i class="icon-material-baseline-mail-outline"></i>
            <input type="text" class="input-text with-border" name="username_email" value="" id="emailaddress" placeholder="Email Address" required/>
          </div>

          <div class="input-with-icon-left">
            <i class="icon-material-outline-lock"></i>
            <input type="password" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
          </div>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </form> 

        <!-- Button -->
        <button class="button full-width button-sliding-icon ripple-effect" type="submit" form="login-form">Log In <i class="icon-material-outline-arrow-right-alt"></i></button>
        
        <!-- Social Login -->
        <div class="social-login-separator"><span>or</span></div>
        <div class="social-login-buttons">
          <button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Log In via Facebook</button>
          <button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log In via Google+</button>
        </div>

      </div>

      <!-- Register -->
      <div class="popup-tab-content" id="register">
        
        <!-- Welcome Text -->
        <div class="welcome-text">
          <h3>Let's create your account!</h3>
        </div>

        <!-- Account Type -->
   
          
        <!-- Form -->
        <form method="POST" action="{{ URL::to('join-normal') }}" id="register-account-form">
         @csrf
           <div class="account-type">
          <div>
            <input type="radio" name="role" id="freelancer-radio" class="account-type-radio" value="customer" checked/>
            <label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Freelancer</label>
          </div>

          <div>
            <input type="radio" name="role" id="employer-radio" class="account-type-radio" value="provider" />
            <label for="employer-radio" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Employer</label>
          </div>
        </div>
             <div class="input-with-icon-left">
            <i class="icon-material-baseline-mail-outline"></i>
            <input type="text" class="input-text with-border" name="name" id="emailaddress-register" placeholder="Name" required/>
          </div>
          <div class="input-with-icon-left">
            <i class="icon-material-baseline-mail-outline"></i>
            <input type="email" class="input-text with-border" name="email" id="emailaddress-register" placeholder="Email Address" required/>
          </div>

          <div class="input-with-icon-left" title="Should be at least 8 characters long" data-tippy-placement="bottom">
            <i class="icon-material-outline-lock"></i>
            <input type="password" class="input-text with-border" name="password" id="password-register" placeholder="Password" required/>
          </div>

          <div class="input-with-icon-left">
            <i class="icon-material-outline-lock"></i>
            <input type="password" class="input-text with-border" name="password_confirmation" id="password-repeat-register" placeholder="Repeat Password" required/>
          </div>

        <button class="margin-top-10 button full-width button-sliding-icon ripple-effect" type="submit" form="register-account-form">Register <i class="icon-material-outline-arrow-right-alt"></i></button>
        </form> 
        
        <!-- Social Login -->
        <div class="social-login-separator"><span>or</span></div>
        <div class="social-login-buttons">
          <button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Register via Facebook</button>
          <button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Register via Google+</button>
        </div>

      </div>

    </div>
  </div>
</div>
<!-- Sign In Popup / End -->

</div>
<!-- Wrapper / End -->


<!-- Scripts
================================================== -->
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/jquery-migrate-3.0.0.min.js') }}"></script>
<script src="{{ asset('js/mmenu.min.js') }}"></script>
<script src="{{ asset('js/tippy.all.min.js') }}"></script>
<script src="{{ asset('js/simplebar.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/snackbar.js') }}"></script>
<script src="{{ asset('js/clipboard.min.js') }}"></script>
<script src="{{ asset('js/counterup.min.js') }}"></script>
<script src="{{ asset('js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>



<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
// Snackbar for user status switcher
$('#snackbar-user-status label').click(function() { 
  Snackbar.show({
    text: 'Your status has been changed!',
    pos: 'bottom-center',
    showAction: false,
    actionText: "Dismiss",
    duration: 3000,
    textColor: '#fff',
    backgroundColor: '#383838'
  }); 
}); 
</script>


<!-- Google Autocomplete -->
<script>
  function initAutocomplete() {
     var options = {
      types: ['(cities)'],
      // componentRestrictions: {country: "us"}
     };

     var input = document.getElementById('autocomplete-input');
     var autocomplete = new google.maps.places.Autocomplete(input, options);
  }

  // Autocomplete adjustment for homepage
  if ($('.intro-banner-search-form')[0]) {
      setTimeout(function(){ 
          $(".pac-container").prependTo(".intro-search-field.with-autocomplete");
      }, 300);
  }

</script>



<!-- Chart.js // documentation: http://www.chartjs.org/docs/latest/ -->
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
  Chart.defaults.global.defaultFontFamily = "Nunito";
  Chart.defaults.global.defaultFontColor = '#888';
  Chart.defaults.global.defaultFontSize = '14';

  var ctx = document.getElementById('chart').getContext('2d');

  var chart = new Chart(ctx, {
    type: 'line',

    // The data for our dataset
    data: {
      labels: ["January", "February", "March", "April", "May", "June"],
      // Information about the dataset
        datasets: [{
        label: "Views",
        backgroundColor: 'rgba(42,65,232,0.08)',
        borderColor: '#2a41e8',
        borderWidth: "3",
        data: [196,132,215,362,210,252],
        pointRadius: 5,
        pointHoverRadius:5,
        pointHitRadius: 10,
        pointBackgroundColor: "#fff",
        pointHoverBackgroundColor: "#fff",
        pointBorderWidth: "2",
      }]
    },

    // Configuration options
    options: {

        layout: {
          padding: 10,
        },

      legend: { display: false },
      title:  { display: false },

      scales: {
        yAxes: [{
          scaleLabel: {
            display: false
          },
          gridLines: {
             borderDash: [6, 10],
             color: "#d8d8d8",
             lineWidth: 1,
                },
        }],
        xAxes: [{
          scaleLabel: { display: false },  
          gridLines:  { display: false },
        }],
      },

        tooltips: {
          backgroundColor: '#333',
          titleFontSize: 13,
          titleFontColor: '#fff',
          bodyFontColor: '#fff',
          bodyFontSize: 13,
          displayColors: false,
          xPadding: 10,
          yPadding: 10,
          intersect: false
        }
    },


});

</script>

<!-- Google API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgeuuDfRlweIs7D6uo4wdIHVvJ0LonQ6g&libraries=places&callback=initAutocomplete"></script>

</body>
</html>