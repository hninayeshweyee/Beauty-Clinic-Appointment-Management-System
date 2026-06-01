<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Support Center - GlowWave Medical Aesthetics</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #A76545;
            --bg-light: #fdf2ed;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #444;
            line-height: 1.6;
        }
        .support-header {
            background: linear-gradient(rgba(167, 101, 69, 0.9), rgba(167, 101, 69, 0.9)), url('img/carousel-2.jpg') center center no-repeat;
            background-size: cover;
            padding: 100px 0 60px 0;
            color: white;
            text-align: center;
        }
        .support-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin-top: -50px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 80px;
        }
        .support-card {
            border: none;
            border-radius: 10px;
            background-color: var(--bg-light);
            transition: 0.3s;
            height: 100%;
        }
        .support-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(167, 101, 69, 0.2);
        }
        h3 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--bg-light);
            padding-bottom: 10px;
        }
        .faq-question {
            font-weight: 600;
            color: var(--primary-color);
            margin-top: 20px;
        }
        .contact-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        .btn-support {
            background-color: var(--primary-color);
            color: white;
            border-radius: 30px;
            padding: 10px 25px;
            transition: 0.3s;
            text-decoration: none;
        }
        .btn-support:hover {
            background-color: #8a5338;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top py-3 px-lg-5 shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="btn btn-outline-primary btn-sm rounded-pill px-4 me-3">
                <i class="fa fa-arrow-left me-2"></i>Home
            </a>
            <a href="index.php" class="navbar-brand me-auto">
                <h1 class="m-0 text-primary" style="font-size: 1.6rem; font-weight: 800;">
                    <i class="fa fa-spa me-2"></i>GlowWave
                </h1>
            </a>
        </div>
    </nav>

    <div class="support-header">
        <div class="container">
            <h1 class="display-4 fw-bold">Support & Help Center</h1>
            <p class="lead">How can we help you today? Our team is here for you.</p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 support-container">
                
                <div class="row g-4 mb-5 text-center">
                    <div class="col-md-4">
                        <div class="support-card p-4">
                            <i class="fa fa-phone contact-icon"></i>
                            <h5>Call Us</h5>
                            <p class="small text-muted">Available Mon-Sat (9am - 6pm)</p>
                            <a href="tel:+95912345678" class="text-primary fw-bold">+95 9789 345 678</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="support-card p-4">
                            <i class="fa fa-envelope contact-icon"></i>
                            <h5>Email Support</h5>
                            <p class="small text-muted">We reply within 24 hours</p>
                            <a href="mailto:support@glowwave.com" class="text-primary fw-bold">support@glowwave.com</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="support-card p-4">
                            <i class="fa fa-location-dot contact-icon"></i>
                            <h5>Visit Clinic</h5>
                            <p class="small text-muted">123 Wellness St, Yangon</p>
                            <a href="#" class="text-primary fw-bold">View on Map</a>
                        </div>
                    </div>
                </div>

                <h3><i class="fa fa-circle-question me-2"></i>Frequently Asked Questions</h3>
                
                <div class="faq-item">
                    <p class="faq-question">Q: How do I reschedule my appointment?</p>
                    <p>A: You can reschedule by calling our support line or through your "My Appointments" dashboard at least 24 hours before the scheduled time.</p>
                </div>

                <div class="faq-item">
                    <p class="faq-question">Q: Are your treatments safe for sensitive skin?</p>
                    <p>A: Yes! Our doctors conduct a skin analysis before every treatment to ensure the products used are safe for your specific skin type.</p>
                </div>

                <div class="faq-item">
                    <p class="faq-question">Q: Can I book for someone else?</p>
                    <p>A: Yes, you can enter the patient's name and phone number in the "Appointment Details" section during the booking process.</p>
                </div>

                <div class="faq-item">
                    <p class="faq-question">Q: What payment methods do you accept?</p>
                    <p>A: We accept Cash, Visa/Mastercard, and mobile payments including KBZPay and CBPay at the clinic counter.</p>
                </div>

                <div class="mt-5 p-4 bg-light rounded-3 border-start border-4 border-primary">
                    <h5>Need more specialized help?</h5>
                    <p class="mb-0 text-muted">If you have a medical emergency or specific post-treatment concern, please call our emergency hotline directly at <strong>+95 9 999 999 99</strong>.</p>
                </div>

                <div class="text-center mt-5">
                    <a href="index.php" class="btn-support">
                        <i class="fa fa-home me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-dark text-light border-top py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 GlowWave Medical Aesthetics. All Rights Reserved.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>