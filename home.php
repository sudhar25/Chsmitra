<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CHS Mitra - Welcome</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      transition: all 0.3s ease;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(to right, #dbe9f4, #e6f2ff);
      color: #003366;
      opacity: 0;
      animation: fadeInBody 1.4s ease-in-out forwards;
    }

    @keyframes fadeInBody {
      to {
        opacity: 1;
      }
    }

    .hero {
      position: relative;
      height: 90vh;
      background: url('https://source.unsplash.com/1600x900/?society,building') no-repeat center center/cover;
      background-attachment: fixed;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding-top: 160px;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(6px);
      z-index: 1;
    }

    .navbar {
      position: absolute;
      top: 0;
      width: 100%;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      padding: 20px 40px;
      background: rgba(255, 255, 255, 0.85);
      z-index: 2;
      box-shadow: 0 2px 10px rgba(0, 0, 80, 0.1);
    }

    .navbar .brand {
      display: flex;
      align-items: center;
    }

    .navbar .brand img {
      height: 60px;
      width: 60px;
      border-radius: 50%;
      margin-right: 15px;
    }

    .navbar .brand span {
      font-size: 2rem;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      color: #003366;
    }

    .content {
      z-index: 2;
      max-width: 800px;
      background: rgba(255, 255, 255, 0.88);
      padding: 40px 40px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 6px 20px rgba(0, 0, 80, 0.15);
      margin-top: 20px;
    }

    .content h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      color: #003366;
      margin-bottom: 20px;
    }

    .content p {
      font-size: 1rem;
      color: #003366;
      line-height: 1.8;
      margin-bottom: 30px;
    }

    .content h2 {
      font-size: 1.6rem;
      font-weight: 500;
      color: #0056b3;
      margin-bottom: 20px;
    }

    .btn {
      display: inline-block;
      margin-top: 10px;
      padding: 14px 40px;
      font-size: 1rem;
      border: none;
      border-radius: 50px;
      background: linear-gradient(135deg, #00a0ea, #000056);
      color: white;
      text-decoration: none;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(0, 0, 100, 0.2);
    }

    .btn:hover {
      background: linear-gradient(135deg, #000056, #003366);
      transform: translateY(-2px) scale(1.05);
    }

    .features {
      padding: 80px 20px;
      background: #f0f8ff;
      text-align: center;
    }

    .features h2 {
      font-size: 2.5rem;
      margin-bottom: 40px;
      color: #003366;
    }

    .feature-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    .feature {
      background: white;
      padding: 30px;
      border-radius: 15px;
      width: 300px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0, 0, 100, 0.1);
      transition: transform 0.3s;
    }

    .feature:hover {
      transform: translateY(-5px);
    }

    .feature h3 {
      margin-bottom: 10px;
      color: #0056b3;
    }

    .feature p {
      font-size: 0.95rem;
      color: #003366;
    }

    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        gap: 10px;
      }
      .content h1 {
        font-size: 2rem;
      }
      .btn {
        width: 80%;
      }
    }

    /* iOS-style View Features Button */
    .scroll-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 14px 24px;
      font-size: 0.95rem;
      background: #0056b3;
      color: white;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      opacity: 1;
      transform: translateY(0);
      visibility: visible;
      transition: all 0.5s cubic-bezier(0.4, 0.0, 0.2, 1);
    }

    /* Hide button with smooth transition */
    .scroll-btn.hide {
      opacity: 0;
      transform: translateY(20px);
      visibility: hidden;
    }
  </style>
</head>
<body>
  <div class="hero">
    <div class="overlay"></div>
    <div class="navbar">
      <div class="brand">
        <img src="https://via.placeholder.com/60" alt="Logo" />
        <span>CHS Mitra</span>
      </div>
    </div>
    <div class="content" data-aos="fade-up">
      <h1>Welcome to CHS Mitra</h1>
      <p>CHS Mitra is a smart society management platform designed to enhance community living through digital innovation. Whether you're a resident or an administrator, CHS Mitra empowers you with tools for secure communication, complaint resolution, visitor tracking, and multi-society management — all in one streamlined interface.<br><br>
      <strong>CHS Mitra — Making Communities Smarter, Safer & Seamless.</strong></p>
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
        <h3>📊 Transparent Communication</h3>
        <p>Keep residents informed through real-time updates, issue tracking, and centralized communication systems.</p>
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
