<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shazam Style - Audio Recognizer</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        :root {
            --shazam-blue: #08a3ff;
            --shazam-dark: #0056b3;
            --bg-gradient: radial-gradient(circle, #1e90ff 0%, #002f6c 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--bg-gradient);
            color: white;
            overflow: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        h1 {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 60px;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* Le bouton principal style Shazam */
        .shazam-outer {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 250px;
            height: 250px;
        }

        .record-btn {
            position: relative;
            z-index: 10;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: white;
            color: var(--shazam-blue);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            outline: none;
        }

        .record-btn i {
            font-size: 80px;
            transition: 0.3s;
        }

        /* Animation de pulsation (Ripple) */
        .pulse-ring {
            position: absolute;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            animation: pulse-shazam 2.5s infinite;
            opacity: 0;
        }

        .pulse-ring:nth-child(2) {
            animation-delay: 0.8s;
        }

        .pulse-ring:nth-child(3) {
            animation-delay: 1.6s;
        }

        @keyframes pulse-shazam {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }

        /* État quand on enregistre */
        .recording .record-btn {
            transform: scale(0.9);
            background: var(--shazam-blue);
            color: white;
        }

        .status {
            margin-top: 60px;
            font-size: 1.1rem;
            font-weight: 300;
            height: 30px;
            opacity: 0.9;
        }

        /* Audio Player caché par défaut, stylisé */
        audio {
            margin-top: 30px;
            filter: invert(100%) hue-rotate(180deg) brightness(1.5);
            height: 35px;
            opacity: 0;
            transition: 0.5s;
        }

        audio.show {
            opacity: 1;
        }

        /* Masquer les anneaux quand on ne filme pas */
        .pulse-ring {
            display: none;
        }

        .active-recording .pulse-ring {
            display: block;
        }
    </style>
</head>

<body>

    <div class="container" id="mainContainer">
        <h1>SHAZAM CLONE</h1>

        <div class="shazam-outer">
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>

            <button class="record-btn" id="recordBtn">
                <i class="fa-solid fa-music" id="mainIcon"></i>
            </button>
        </div>

        <div class="status" id="status">Touchez pour identifier</div>

        <audio id="audioPlayback" controls></audio>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
    <!-- <script>
        let mediaRecorder;
        let audioChunks = [];
        let isRecording = false;

        const recordBtn = document.getElementById("recordBtn");
        const mainContainer = document.getElementById("mainContainer");
        const statusText = document.getElementById("status");
        const audioPlayback = document.getElementById("audioPlayback");
        const mainIcon = document.getElementById("mainIcon");

        recordBtn.addEventListener("click", async () => {

            if (!isRecording) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.start();

                    isRecording = true;
                    mainContainer.classList.add("active-recording");
                    mainContainer.classList.add("recording");
                    mainIcon.classList.replace("fa-music", "fa-microphone");
                    statusText.textContent = "Écoute en cours...";
                    audioPlayback.classList.remove("show");

                    mediaRecorder.ondataavailable = e => {
                        audioChunks.push(e.data);
                    };

                    mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(audioChunks, {
                            type: "audio/webm"
                        });
                        const audioUrl = URL.createObjectURL(audioBlob);
                        audioPlayback.src = audioUrl;
                        audioPlayback.classList.add("show");
                        audioChunks = [];
                    };
                } catch (err) {
                    statusText.textContent = "Accès micro refusé";
                }

            } else {
                mediaRecorder.stop();
                isRecording = false;
                mainContainer.classList.remove("active-recording");
                mainContainer.classList.remove("recording");
                mainIcon.classList.replace("fa-microphone", "fa-music");
                statusText.textContent = "Analyse terminée";
            }
        });
    </script> -->
    <script>
        let mediaRecorder;
        let isRecording = false;

        const recordBtn = document.getElementById("recordBtn");
        const mainContainer = document.getElementById("mainContainer");
        const statusText = document.getElementById("status");
        const mainIcon = document.getElementById("mainIcon");

        recordBtn.addEventListener("click", async () => {

            if (isRecording) return; // empêcher double clic

            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });

                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: "audio/webm"
                });

                isRecording = true;

                mainContainer.classList.add("active-recording");
                mainContainer.classList.add("recording");
                mainIcon.classList.replace("fa-music", "fa-microphone");
                statusText.textContent = "Écoute en cours...";

                // ⚡ Envoi des chunks toutes les 10 secondes
                mediaRecorder.start(10000);

                mediaRecorder.ondataavailable = async (event) => {
                    if (event.data.size > 0) {
                        const formData = new FormData();
                        formData.append("audio", event.data);

                        try {
                            const response = await fetch("/n8n-webhook", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": document
                                        .querySelector('meta[name="csrf-token"]')
                                        .getAttribute("content")
                                },
                                body: formData
                            });

                            const result = await response.json();
                            console.log("Réponse Laravel -> n8n:", result);
                            if (result.status == 'success') {
                                // ✅ Redirection vers resultat.html
                                window.location.href = "{{ url('/search/result') }}";
                            }

                        } catch (error) {
                            console.error("Erreur upload:", error);
                            statusText.textContent = "Erreur analyse";
                        }
                    }
                };



                // 🛑 Stop automatique après 15 secondes
                setTimeout(() => {
                    if (mediaRecorder && mediaRecorder.state !== "inactive") {
                        mediaRecorder.stop();
                        isRecording = false;
                        mainContainer.classList.remove("active-recording");
                        mainContainer.classList.remove("recording");
                        mainIcon.classList.replace("fa-microphone", "fa-music");
                        statusText.textContent = "Réessayer...";
                    }
                }, 15000);

            } catch (err) {
                statusText.textContent = "Accès micro refusé";
            }
        });
    </script>
</body>

</html>
