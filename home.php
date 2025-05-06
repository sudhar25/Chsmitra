<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CHSmitra - Welcome</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  
</head>
<body>
  <div class="hero">
    <div class="overlay"></div>
    <div class="navbar">
      <div class="brand">
        <img src="Images/logo.png" alt="logo" />
        <span>CHSmitra</span>
      </div>
    </div>
    <div class="content" data-aos="fade-up">
      <h1>Welcome to CHSmitra</h1>
      <p>CHSmitra is a smart society management platform designed to enhance community living through digital innovation. 
        Whether you're a resident or an administrator, CHS Mitra empowers you with tools for secure communication, 
        complaint resolution, visitor tracking, and multi-society management — all in one streamlined interface.<br><br>
      <strong>CHSmitra — Making Communities Smarter, Safer & Seamless.</strong></p>
      <h2>Let's Get Started!</h2>
      <a href="login.php" class="btn">Login</a>
    </div>
  </div>

  <section class="features" id="features">
    <h2 data-aos="fade-up">Key Features</h2>
    <div class="feature-grid">
      <div class="feature" data-aos="fade-up" data-aos-delay="100">
        <h3>🏠 Multi-Society Support</h3>
        <p>Manage multiple societies under a single platform with independent configurations and access controls.</p>
      </div>
      <div class="feature" data-aos="fade-up" data-aos-delay="200">
        <h3>🔐 Security Oversight</h3>
        <p>Monitor visitor entries, gate activity, and access logs in real time to ensure safety for all residents.</p>
      </div>
      <div class="feature" data-aos="fade-up" data-aos-delay="300">
        <h3>👤 Admin Control Panel</h3>
        <p>Grant administrators the ability to manage society records, track issues, and communicate updates with ease.</p>
      </div>
      <div class="feature" data-aos="fade-up" data-aos-delay="400">
        <h3>📊 Contact Us</h3>
        <p>For registering your society Contact the Admin <strong>EMAIL: achsmitra@gmail.com</strong> mail your details through this email.</p>
      </div>
    </div>
  </section>

  <!-- View Features button -->
  <button class="scroll-btn" id="viewFeaturesBtn" onclick="scrollToFeatures()">View Features</button>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });

    // Scroll to the features section
    function scrollToFeatures() {
      document.getElementById('features').scrollIntoView({ behavior: 'smooth' });
    }

    // Hide the View Features button when scrolling down
    window.addEventListener('scroll', function() {
      const btn = document.getElementById('viewFeaturesBtn');
      if (window.scrollY > 200) {
        btn.classList.add('hide');
      } else {
        btn.classList.remove('hide');
      }
    });
  </script>
</body>
</html>
