<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $result['artist'] }} | {{ $result['title'] }}</title>
    <meta name="description"
        content="Découvrez notre application de reconnaissance musicale inspirée de Shazam. Enregistrez un extrait sonore et obtenez instantanément le titre, l'artiste et les liens pour écouter sur Spotify ou Apple Music. Essayez dès maintenant !">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('logo.png') }}" rel="icon">
    <link href="{{ asset('logo.png') }}" rel="apple-touch-icon">
    <style>
        :root {
            --primary-purple: #6a5acd;
            --dark-bg: #121212;
            --glass: rgba(255, 255, 255, 0.1);
        }

        body,
        html {
            margin: 0;
            padding: 0;
            background-color: var(--dark-bg);
            color: white;
            font-family: 'Segoe UI', Roboto, Helvetica, sans-serif;
            height: 100%;
            overflow-y: auto;
        }

        /* Header Image avec dégradé */
        .hero-section {
            position: relative;
            width: 100%;
            height: 45vh;
            background: url({{ $result['img'] }}) center/cover no-repeat;
        }

        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(to bottom, transparent 0%, var(--dark-bg) 95%);
        }

        /* Barre supérieure */
        .top-nav {
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 10;
        }

        /* Contenu Principal */
        .content {
            padding: 0 20px 40px;
            margin-top: -20px;
            position: relative;
        }

        .main-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .title-area h1 {
            font-size: 2.2rem;
            margin: 0;
            font-weight: 700;
        }

        .title-area p {
            font-size: 1.1rem;
            color: #b3b3b3;
            margin: 5px 0 0;
        }

        /* Bouton Play Shazam Style */
        .play-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: none;
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .playing .play-btn {
            background: white;
            color: black;
        }

        /* Boutons d'action */
        .action-row {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            align-items: center;
        }

        .shazam-count {
            color: #b3b3b3;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .open-in-btn {
            background: #2a2a2a;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            border: 1px solid #444;
        }

        .share-btn {
            background: var(--primary-purple);
            color: white;
            width: 100% !important;
            padding: 15px;
            border-radius: 12px;
            border: none;
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 40px;
            cursor: pointer;
        }

        /* Section Concerts */
        .section-title {
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            color: #b3b3b3;
            margin-bottom: 15px;
        }

        .concert-card {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .concert-date {
            color: var(--primary-purple);
            font-size: 0.9rem;
            font-weight: bold;
        }

        .concert-venue {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .concert-city {
            color: #b3b3b3;
        }

        .waves-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            display: none;
        }

        .playing .waves-container {
            display: block;
        }

        .wave {
            position: absolute;
            border: 2px solid var(--primary-purple);
            border-radius: 50%;
            width: 100px;
            height: 100px;
            left: -50px;
            top: -50px;
            opacity: 0;
            animation: wave-anim 2s infinite;
        }

        @keyframes wave-anim {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }

            100% {
                transform: scale(3);
                opacity: 0;
            }
        }

        /* Plateformes */
        .links {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .btn-link {
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 1.0rem;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }

        .btn-link.sp:hover {
            background: var(--spotify);
            border-color: var(--spotify);
        }

        .btn-link.ap:hover {
            background: var(--apple);
            border-color: var(--apple);
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div class="top-nav">
            <a href="{{ url('/') }}" title="Retour à l'accueil"><i class="fas fa-arrow-left"></i></a>
            <div style="display:flex; gap: 20px; align-items: center;" title="indisponible pour le moment">
                <span style="font-size: 0.8rem; font-weight: bold;"><i class="fas fa-list"></i> LYRICS</span>
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </div>

    <div class="content" id="mainWrapper">
        <div class="main-info">
            <div class="title-area">
                <h1>{{ $result['title'] }}</h1>
                <p>{{ $result['artist'] }}</p>
            </div>
            <button class="play-btn" id="masterPlay">
                <i class="fas fa-play"></i>
                <div class="waves-container">
                    <div class="wave"></div>
                    <div class="wave" style="animation-delay: 0.5s"></div>
                </div>
            </button>
        </div>

        <div class="links">
            <a href="{{ $result['spotify_link'] }}" class="btn-link sp" target="_blank">
                <i class="fab fa-spotify"></i> Spotify
            </a>
            <a href="{{ $result['apple_link'] }}" class="btn-link ap" target="_blank">
                <i class="fab fa-apple"></i> Apple Music
            </a>
        </div>
        <br>
        <a href="{{ $result['song_link'] }}" class="share-btn" style="width: 100% !important;">Share song</a>

    </div>

    <audio id="audioElement">
        <source src="{{ $result['preview'] }}" type="audio/mpeg">
    </audio>

    <script>
        const audio = document.getElementById('audioElement');
        const playBtn = document.getElementById('masterPlay');
        const icon = playBtn.querySelector('i');
        const wrapper = document.getElementById('mainWrapper');

        function updateUI(isPlaying) {
            if (isPlaying) {
                icon.classList.replace('fa-play', 'fa-pause');
                document.body.classList.add('playing');
            } else {
                icon.classList.replace('fa-pause', 'fa-play');
                document.body.classList.remove('playing');
            }
        }

        // Tenter l'autoplay au clic n'importe où (contrainte navigateur)
        document.body.addEventListener('click', () => {
            if (audio.paused && !document.body.classList.contains('manual-stop')) {
                audio.play();
                updateUI(true);
            }
        }, {
            once: true
        });

        playBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (audio.paused) {
                audio.play();
                updateUI(true);
            } else {
                audio.pause();
                updateUI(false);
                document.body.classList.add('manual-stop');
            }
        });

        audio.onended = () => updateUI(false);
    </script>
</body>

</html>
