<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Streaming Audio Record and Upload</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <button id="recordButton">Start Recording</button>

    <div id="responseDisplay">
        <p id="textInput"></p>
        <p id="textAnswer"></p>
        <audio id="audioPlayer" controls></audio>
        <audio id="audioPlayer2" controls></audio>
    </div>

    <script>
        let mediaRecorder;
        let recordedChunks = [];

        document.getElementById('recordButton').addEventListener('click', toggleRecording);

        function toggleRecording() {
            if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                startRecording();
                document.getElementById('recordButton').textContent = 'Stop Recording';
            } else {
                stopRecording();
                document.getElementById('recordButton').textContent = 'Start Recording';
            }
        }

        function startRecording() {
            navigator.mediaDevices.getUserMedia({ audio: true })
        .then(function (stream) {
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.ondataavailable = function (e) {
                recordedChunks.push(e.data);
            };
            mediaRecorder.onstop = function () {
                const audioBlob = new Blob(recordedChunks, { type: 'audio/wav' });
                const formData = new FormData();
                formData.append('audio', audioBlob, 'audio.wav');

                fetch('http://127.0.0.1:5000/askNusa', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Access-Control-Allow-Origin': '*' 
                    },
                })
                .then(response => {
                    const audioElement = document.getElementById('audioPlayer');
                    audioElement.src = URL.createObjectURL(audioBlob);
                    audioElement.controls = true;
                    audioElement.play();

                    audioElement.addEventListener('ended', () => {
                    const additionalAudioElement = document.getElementById('audioPlayer2');
                    const audioURL = "http://localhost/AI-Voice-Assistant/temp/audio_answer.wav";
                    additionalAudioElement.src = audioURL;
                    additionalAudioElement.controls = true;
                    additionalAudioElement.play();

                    const responseDisplay = document.getElementById('responseDisplay');
                    responseDisplay.appendChild(additionalAudioElement);
                    });

                    audioElement.play(); 
                })
                .catch(error => {
                    console.error('There was a problem with the fetch request:', error);
                });
            };
            recordedChunks = [];
            mediaRecorder.start();
        })
        .catch(function (err) {
            console.error('Error accessing the microphone:', err);
        });
}

        function stopRecording() {
            if (mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
            }
        }
    </script>
</body>

</html>
