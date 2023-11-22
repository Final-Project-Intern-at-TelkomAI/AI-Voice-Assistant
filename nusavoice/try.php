<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Audio Upload</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <input type="file" id="audioFileInput">
    <button id="uploadButton">Upload Audio</button>

    <div id="responseDisplay">
        <p id="textInput"></p>
        <p id="textAnswer"></p>
        <audio id="audioPlayer" controls></audio>
    </div>

    <script>
        document.getElementById('uploadButton').addEventListener('click', function() {
            const audioFileInput = document.getElementById('audioFileInput');
            const file = audioFileInput.files[0];

            if (!file) {
                alert('No file selected.');
                return;
            }

            const formData = new FormData();
            formData.append('audio', file, 'audio.wav');

            $.ajax({
                url: 'http://127.0.0.1:5000/askNusa',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                processData: false,
                headers: {
                    'Access-Control-Allow-Origin': '*' // Change * to your specific origin if possible
                },
                            success: function(response) {
                    const audioURL = "http://localhost/AI-Voice-Assistant/temp/audio_answer.wav";

                    const audioElement = document.getElementById('audioPlayer');
                    audioElement.src = audioURL;
                    audioElement.controls = true;
                    audioElement.play();
                },
                error: function(error) {
                    console.error('There was a problem with the AJAX request:', error);
                }
            });
        });
    </script>
</body>

</html>
