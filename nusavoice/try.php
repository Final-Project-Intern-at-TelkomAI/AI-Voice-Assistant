<!DOCTYPE html>
<html lang="en">
<title>NusaTalk</title>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: auto;
        }
    </style>
    <link rel="icon" type="image/ico" href="public/assets--website---mini-project-8-2@2x.png">
    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./home.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito Sans:wght@400;700&display=swap"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
    <script src="https://markjivko.com/dist/recorder.js"></script>
  </head>
<body>

<button id="recordButton" onclick="toggleRecording()">Record</button>
<audio id="audioPlayer" controls></audio>
<audio id="jawaban" controls>
    <source src="http://127.0.0.1:8000/getLatestAudio" type="audio/wav">
</audio>
<div id="result"></div>

<script>
let isRecording = false;
let mediaRecorder;
let audioChunks = [];

async function sendAudioToEndpoint(formData, endpoint) {
    try {
        const response = await fetch(`http://127.0.0.1:8000/askNusa`, {
            method: 'POST',
            body: formData,
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }

        const audioBlob = await response.blob();
        console.log('Received audio blob:', audioBlob);

        const audioPlayer = document.getElementById('audioResult');
        audioResult.src = URL.createObjectURL(audioBlob);
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        const resultElement = document.getElementById('result');
    }
}

async function toggleRecording() {
    const audioPlayer = document.getElementById('audioPlayer');

    if (!isRecording) {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        
        mediaRecorder.ondataavailable = (event) => {
            if (event.data.size > 0) {
                audioChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = async () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            const formData = new FormData();
            formData.append('audio', audioBlob);

            await sendAudioToEndpoint(formData, 'askNusa');

            audioPlayer.src = URL.createObjectURL(audioBlob);
            audioPlayer.play();
            audioPlayer.onended = async () => {
                playLatestAudio();
            };
        };

        mediaRecorder.start();
        document.getElementById('recordButton').textContent = 'Stop';
    } else {
        // Stop recording
        mediaRecorder.stop();
        document.getElementById('recordButton').textContent = 'Record';
    }

    isRecording = !isRecording;
}

async function playLatestAudio() {
    try {
        const response = await fetch('http://127.0.0.1:8000/getLatestAudio', {
            method: 'GET',
        });

        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }

        const audioBlob = await response.blob();
        console.log('Received audio blob:', audioBlob);

        //const audioResult = document.getElementById('audioResult');
        //audioResult.src = URL.createObjectURL(audioBlob);
        //audioResult.play(); // Memulai pemutaran audio
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        const resultElement = document.getElementById('result');
    }
}

function refreshLatestAudio() {
    const audioPlayer = document.getElementById('jawaban');
        audioPlayer.src = URL.createObjectURL(audioBlob);
        audioPlayer.play();
}

window.onload = function() {
    setInterval(refreshLatestAudio, 3000);
}


</script>
</body>
</html>
