<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Launching MANTRA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Optional font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            width: 100vw;
            background: #0f0f1a;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* VIDEO LAYER (Background) */
        .intro-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .intro-video video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <!-- LEFT: VIDEO -->
    <div class="intro-video">
        <video autoplay muted playsinline>
            <source src="{{ asset('videos/a.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>



    <script>
        // Redirect after video ends
        const video = document.querySelector('video');
        video.onended = function () {
            sessionStorage.setItem('skip_loader', '1');
            window.location.href = "{{ route('dashboard') }}";
        };
        // Fallback just in case video doesn't autoplay or stalls
        setTimeout(() => {
            sessionStorage.setItem('skip_loader', '1');
            window.location.href = "{{ route('dashboard') }}";
        }, 10000); 
    </script>

</body>

</html>