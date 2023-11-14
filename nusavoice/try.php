<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Recorder</title>
</head>
<body>

<button id="recordButton" onclick="toggleRecording()">Record</button>
<audio id="audioPlayer" controls></audio>
<audio id="audioResult" controls>
    <source src="http://127.0.0.1:8000/getLatestAudio" type="audio/wav">
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

        const data = await response.json();
        console.log('Response:', data);

        // Do something with the data received from the server
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        const resultElement = document.getElementById('result');
    }
}

async function toggleRecording() {
    const audioPlayer = document.getElementById('audioPlayer');

    if (!isRecording) {
        // Start recording
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
    
        // Setelah audio rekaman selesai diputar, panggil fungsi playLatestAudio
        audioPlayer.onended = () => {
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

        const audioBlob = await response.blob();
        console.log('Received audio blob:', audioBlob);

        const audioSource = document.getElementById('audioSource');
        audioSource.src = URL.createObjectURL(audioBlob);

        const audioResult = document.getElementById('audioResult');
        audioResult.load(); // Reload audio player to update source
        audioResult.play(); // Play the audio
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        const resultElement = document.getElementById('result');
    }
}

// Function to play latest audio when the page loads
//window.onload = function() {
//    playLatestAudio();
//};

</script>
</audio> 
</audio>
</body>
</html>
