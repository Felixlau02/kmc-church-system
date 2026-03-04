<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KingFisher Methodist Church (KMC)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            overflow-x: hidden;
            background: #f8f9fa;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .church-logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #5e72e4;
            box-shadow: 0 3px 15px rgba(94, 114, 228, 0.3);
        }

        .brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Georgia', serif;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 1rem;
            font-family: 'Segoe UI', sans-serif;
        }

        .nav-links a:hover {
            color: #5e72e4;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-nav {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
            font-family: 'Segoe UI', sans-serif;
        }

        .btn-login {
            background: transparent;
            color: #5e72e4;
            border: 2px solid #5e72e4;
        }

        .btn-login:hover {
            background: #5e72e4;
            color: white;
        }

        .btn-register {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            color: white;
            border: 2px solid transparent;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #4c5fd4 0%, #6f4dc4 100%);
        }

        /* Hero Section */
        .hero {
            margin-top: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 6rem 5% 7rem;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-verse {
            font-size: 1.1rem;
            font-style: italic;
            margin-bottom: 2rem;
            opacity: 0.95;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 10px rgba(0,0,0,0.3);
            font-family: 'Georgia', serif;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
            opacity: 0.95;
            font-family: 'Georgia', serif;
        }

        /* Welcome Section */
        .welcome-section {
            padding: 5rem 5%;
            background: white;
            text-align: center;
        }

        .section-title {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            font-family: 'Georgia', serif;
            font-weight: 700;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: #555;
            max-width: 900px;
            margin: 0 auto 3rem;
            line-height: 1.8;
        }

        .church-image-main {
            max-width: 800px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(94, 114, 228, 0.2);
        }

        .church-image-main img {
            width: 100%;
            border-radius: 10px;
        }

        /* Service Times Section */
        .service-times {
            padding: 5rem 5%;
            background: linear-gradient(135deg, #f0f3ff 0%, #e8ecff 100%);
        }

        .service-times-container {
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }

        .service-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .service-card {
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(94, 114, 228, 0.15);
            transition: all 0.3s;
            border: 2px solid rgba(94, 114, 228, 0.1);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(94, 114, 228, 0.25);
            border-color: #5e72e4;
        }

        .service-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .service-card h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
            font-family: 'Georgia', serif;
        }

        .service-card p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
        }

        /* Mission Section */
        .mission-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 5rem 5%;
            color: white;
        }

        .mission-container {
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
        }

        .mission-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            font-family: 'Georgia', serif;
        }

        .mission-text {
            font-size: 1.3rem;
            line-height: 2;
            max-width: 900px;
            margin: 0 auto 3rem;
        }

        .core-values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .value-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s;
        }

        .value-box:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }

        .value-box i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }

        .value-box h3 {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
        }

        .value-box p {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Ministries Section */
        .ministries-section {
            padding: 5rem 5%;
            background: white;
        }

        .ministries-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 3rem auto 0;
        }

        .ministry-card {
            background: linear-gradient(135deg, #f0f3ff 0%, #e8ecff 100%);
            padding: 2.5rem;
            border-radius: 10px;
            transition: all 0.3s;
            border: 2px solid rgba(94, 114, 228, 0.2);
        }

        .ministry-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(94, 114, 228, 0.25);
            border-color: #5e72e4;
        }

        .ministry-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            display: block;
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .ministry-card h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #333;
            font-family: 'Georgia', serif;
        }

        .ministry-card p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        /* Contact Section */
        .contact-section {
            padding: 5rem 5%;
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
            color: white;
        }

        .contact-container {
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-item {
            background: rgba(255, 255, 255, 0.15);
            padding: 2rem;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s;
        }

        .contact-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }

        .contact-item i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }

        .contact-item h3 {
            font-size: 1.3rem;
            margin-bottom: 0.8rem;
        }

        .contact-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* WhatsApp Float Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            color: white;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.3rem;
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
            cursor: pointer;
            transition: all 0.3s;
            z-index: 999;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 35px rgba(37, 211, 102, 0.7);
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        footer p {
            opacity: 0.9;
            margin: 0.5rem 0;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .church-logo {
                width: 55px;
                height: 55px;
            }

            .brand-text {
                font-size: 1.1rem;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-brand">
            <img src="{{ asset('images/church.jpg') }}" class="church-logo" alt="KMC Logo">
            <span class="brand-text">⛪ KingFisher Methodist Church</span>
        </div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#ministries">Ministries</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="btn-nav btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn-nav btn-register">Register</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <p class="hero-verse">"For where two or three gather in my name, there am I with them." - Matthew 18:20</p>
            <h1 class="hero-title">Welcome to KingFisher Methodist Church</h1>
            <p class="hero-subtitle">A community of faith, hope, and love. Join us as we worship together and grow in Christ.</p>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="welcome-section" id="about">
        <h2 class="section-title">Welcome Home</h2>
        <p class="section-subtitle">
            At KingFisher Methodist Church, we believe in creating a warm and welcoming community where everyone can experience 
            God's love and grace. Whether you're seeking spiritual growth, community connection, or a place to belong, 
            we invite you to join our church family.
        </p>
        <div class="church-image-main">
            <img src="{{ asset('images/church.jpg') }}" alt="KMC Church Building">
        </div>
    </section>

    <!-- Service Times -->
    <section class="service-times" id="services">
        <div class="service-times-container">
            <h2 class="section-title">Join Us for Worship</h2>
            <p class="section-subtitle">We gather together to praise God, study His Word, and fellowship with one another</p>
            
            <div class="service-cards">
                <div class="service-card">
                    <i class="fas fa-sun service-icon"></i>
                    <h3>Sunday Service</h3>
                    <p>8:00 AM - 9:30 AM<br>Traditional Worship</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-moon service-icon"></i>
                    <h3>Cell Meeting</h3>
                    <p>Saturday 7:30 PM<br>Fellowship Group</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-praying-hands service-icon"></i>
                    <h3>Prayer Meeting</h3>
                    <p>Thursday 7:45 PM<br>Corporate Prayer</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Core Values -->
    <section class="mission-section">
        <div class="mission-container">
            <h2 class="mission-title">Our Mission & Values</h2>
            <p class="mission-text">
                To glorify God by making disciples of Jesus Christ who worship passionately, 
                love sacrificially, and serve joyfully in our community and beyond.
            </p>

            <div class="core-values">
                <div class="value-box">
                    <i class="fas fa-heart"></i>
                    <h3>Love</h3>
                    <p>Showing Christ's love through our actions and words</p>
                </div>
                <div class="value-box">
                    <i class="fas fa-hands-praying"></i>
                    <h3>Worship</h3>
                    <p>Praising God with all our heart, mind, and soul</p>
                </div>
                <div class="value-box">
                    <i class="fas fa-users"></i>
                    <h3>Community</h3>
                    <p>Building meaningful relationships in Christ</p>
                </div>
                <div class="value-box">
                    <i class="fas fa-hands-helping"></i>
                    <h3>Service</h3>
                    <p>Serving others as Jesus served us</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ministries Section -->
    <section class="ministries-section" id="ministries">
        <div style="text-align: center;">
            <h2 class="section-title">Our Ministries</h2>
            <p class="section-subtitle">Discover opportunities to connect, grow, and serve</p>
        </div>

        <div class="ministries-grid">
            <div class="ministry-card">
                <span class="ministry-icon"><i class="fas fa-users"></i></span>
                <h3>Youth Ministry</h3>
                <p>Empowering young people to live out their faith through fellowship, service, and spiritual growth</p>
            </div>
            <div class="ministry-card">
                <span class="ministry-icon"><i class="fas fa-child"></i></span>
                <h3>Children's Ministry</h3>
                <p>Teaching children about God's love through engaging lessons, activities, and worship</p>
            </div>
            <div class="ministry-card">
                <span class="ministry-icon"><i class="fas fa-music"></i></span>
                <h3>Worship Ministry</h3>
                <p>Leading our congregation in heartfelt worship and praise to glorify God</p>
            </div>
            <div class="ministry-card">
                <span class="ministry-icon"><i class="fas fa-hands-helping"></i></span>
                <h3>Outreach Ministry</h3>
                <p>Serving our community and sharing the love of Christ with those in need</p>
            </div>
            <div class="ministry-card">
                <span class="ministry-icon"><i class="fas fa-book-bible"></i></span>
                <h3>Small Groups</h3>
                <p>Growing together through Bible study, prayer, and authentic Christian fellowship</p>
            </div>
            <div class="ministry-card">
                <span class="ministry-icon"><i class="fas fa-heart"></i></span>
                <h3>Care Ministry</h3>
                <p>Providing prayer, support, and encouragement to those going through difficult times</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="contact-container">
            <h2 class="section-title" style="color: white;">Get in Touch</h2>
            <p class="section-subtitle" style="color: rgba(255,255,255,0.9);">
                We'd love to hear from you! Whether you have questions or would like to visit us, please reach out.
            </p>

            <div class="contact-grid">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Visit Us</h3>
                    <p>First & Second Floor, <br>Lot 14-15, Lorong Sungai MAS Plaza (River Side), <br>Kota Kinabalu, Sabah</p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <h3>Call Us</h3>
                    <p>+60 10-960-2422</p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <h3>Email Us</h3>
                    <p>kmc@gmail.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/60109602422" target="_blank" class="whatsapp-float" title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 KingFisher Methodist Church (KMC). All rights reserved.</p>
        <p>Built with faith, hope, and love ❤️</p>
    </footer>
</body>
</html>