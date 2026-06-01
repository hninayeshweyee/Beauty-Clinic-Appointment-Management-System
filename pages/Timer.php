<?php
    session_start();
    // Reset the login error session so they can try again after the timer
    if (isset($_SESSION['LoginError'])) {
        unset($_SESSION['LoginError']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cooling Down</title>
    <style>
        /* Modern Dark Theme */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #1a1a2e;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
        }

        .container {
            text-align: center;
        }

        /* Circular Progress Design */
        .timer-circle {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 30px;
        }

        .timer-circle svg {
            transform: rotate(-90deg);
            width: 200px;
            height: 200px;
        }

        .timer-circle circle {
            fill: none;
            stroke-width: 10;
            stroke-linecap: round;
        }

        /* Background track */
        .track {
            stroke: #2d2d44;
        }

        /* Progress line */
        .progress {
            stroke: #A76545; /* Matches your login button color */
            stroke-dasharray: 565; /* Circumference of the circle */
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 1s linear;
        }

        .time-display {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            font-weight: bold;
        }

        h1 {
            font-size: 1.5rem;
            letter-spacing: 1px;
            color: #b0b0c0;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            font-size: 0.9rem;
        }

        /* Fade in animation */
        .container {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Please wait to try again</h1>
        
        <div class="timer-circle">
            <svg>
                <circle class="track" cx="100" cy="100" r="90"></circle>
                <circle id="progress-bar" class="progress" cx="100" cy="100" r="90"></circle>
            </svg>
            <div class="time-display" id="countdown">60</div>
        </div>

        <p>Security lock active for 60 seconds.</p>
    </div>

    <script type="text/javascript">
        var totalTime = 60;
        var timeLeft = totalTime;
        var countdownElement = document.getElementById('countdown');
        var progressBar = document.getElementById('progress-bar');
        var totalDash = 565; // Circumference

        function updateTimer() {
            // Update Text
            countdownElement.textContent = timeLeft;

            // Update Circle Progress
            var offset = totalDash - ((timeLeft / totalTime) * totalDash);
            progressBar.style.strokeDashoffset = offset;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                window.location.href = 'sign-in.php';
            }
            timeLeft--;
        }

        // Run once immediately, then every second
        updateTimer();
        var timerInterval = setInterval(updateTimer, 1000);
    </script>

</body>
</html>