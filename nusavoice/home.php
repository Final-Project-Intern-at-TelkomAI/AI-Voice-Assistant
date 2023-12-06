<?php
include 'config.php';
session_start();

if (isset($_SESSION["user_id"])) {
	$first_name = $_SESSION["first_name"];
} else {
	header("Location: ./login.php");
	exit;
}
?>

<!DOCTYPE html>
<html>
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://markjivko.com/dist/recorder.js"></script>
	</head>
	<body>
		<div class="desktop-frame-1440-x-1024">
			<div class="holder">
				<div data-role="controls">
					<button id="recordButton">Record</button>
				</div>
				<div data-role="recordings"></div>
				<div id="responseDisplay">
					<audio id="audioPlayer" controls></audio>
					<p id="textInput"></p>
					<audio id="audioPlayer2" controls></audio>
					<p id="textAnswer"></p>
				</div>
			</div>
		<div class="desktop-frame-1440-x-1024-child"></div>
		<div class="new-chat">
			<div class="new-chat-child"></div>
			<img class="material-symbolsadd-icon"alt=""src="./public/materialsymbolsadd.svg"/>
		<div class="new-chat1">New Chat</div>
		<div class="coba-asisten-suara">
			<div class="coba-asisten-suara-child"></div>
			<div class="coba-asisten-suara1" id="historiText">Histori 1</div>
			<img class="ant-designaudio-filled-icon" alt="" src="./public/antdesignaudiofilled.svg"/>
			<img class="vector-icon" alt="" src="./public/vector.svg" />
			<img class="vector-icon1" alt="" src="./public/vector1.svg" id="editIcon"/>
			<img class="vector-icon2" alt="" src="./public/vector2.svg" />
			<button id="saveButton" style="display: none;">Simpan</button>
		</div>
		</div>
		<div class="header">
			<div class="header-child"></div>
			<img class="iconoirprofile-circle" alt="" src="./public/iconoirprofilecircle.svg"id="iconoirprofileCircle" style="cursor: pointer;"/>
			<div class="nama-pengguna"><?php echo $first_name; ?></div>
		</div>
		</div>
		<div id="frameContainer" class="popup-overlay" style="display: none">
			<img id="logout-icon" class="log-out-icon" alt="" src="./public/logoutt.png" style="width: 150px; height: 100px; cursor: pointer;" />
		</div>

		<script>
			var newChatElement = document.querySelector(".new-chat1");
			newChatElement.addEventListener("click", function () {
				window.location.href = "newpage.php";
			});

			const historiText = document.getElementById("historiText");
			const editIcon = document.getElementById("editIcon");
			const saveButton = document.getElementById("saveButton");
			editIcon.addEventListener("click", function() {
				// Buat elemen input untuk mengedit teks
				const inputElement = document.createElement("input");
				inputElement.type = "text";
				inputElement.value = historiText.innerText;
				historiText.replaceWith(inputElement);
				saveButton.style.display = "inline";
				inputElement.focus();
				saveButton.addEventListener("click", function() {
					historiText.innerText = inputElement.value;
					inputElement.replaceWith(historiText);
					saveButton.style.display = "none";
				});
				inputElement.addEventListener("blur", function() {
					historiText.innerText = inputElement.value;
					inputElement.replaceWith(historiText);
					saveButton.style.display = "none";
				});
			});

			document.querySelector(".vector-icon2").addEventListener("click", function () {
				var cobaAsistenSuara = document.querySelector(".coba-asisten-suara");
				if (cobaAsistenSuara) {
					cobaAsistenSuara.remove();
				}
			});

			document.getElementById("logout-icon").addEventListener("click", function () {
				window.location.href = "logout.php";
			});

			var iconoirprofileCircle = document.getElementById("iconoirprofileCircle");
			if (iconoirprofileCircle) {
				iconoirprofileCircle.addEventListener("click", function () {
					var popup = document.getElementById("frameContainer");
					if (!popup) return;
					var popupStyle = popup.style;
					if (popupStyle) {
						popupStyle.display = "flex";
						popupStyle.zIndex = 100;
						popupStyle.backgroundColor = "rgba(113, 113, 113, 0.3)";
						popupStyle.alignItems = "center";
						popupStyle.justifyContent = "center";
					}
					popup.setAttribute("closable", "");
				
					var onClick =
						popup.onClick ||
						function (e) {
							if (e.target === popup && popup.hasAttribute("closable")) {
								popupStyle.display = "none";
							}
						};
					popup.addEventListener("click", onClick);
				});
			}

			let mediaRecorder;
			let recordedChunks = [];

			document.getElementById('recordButton').addEventListener('click', toggleRecording);

			function toggleRecording() {
				if (!mediaRecorder || mediaRecorder.state === 'inactive') {
					$(this).attr('data-recording', 'true');
					startRecording();
					document.getElementById('recordButton').textContent = 'Stop Recording';
				} else {
					$(this).attr('data-recording', '');
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
							.then(response => response.json())
							.then(data => {
								const audioElement = document.getElementById('audioPlayer');
								audioElement.src = URL.createObjectURL(audioBlob);
								audioElement.controls = true;
								audioElement.play();
								const audioURL =  `http://localhost/AI-Voice-Assistant/temp/${data.file_answer}`;

								audioElement.addEventListener('ended', () => {
									const textInputElement = document.getElementById('textInput');
									const textAnswerElement = document.getElementById('textAnswer');
									textInputElement.innerHTML = `Anda: ${data.text_input}`;
									textAnswerElement.innerHTML = `Nusa: ${data.text_answer}`;

									const additionalAudioElement = document.getElementById('audioPlayer2');
									additionalAudioElement.src = audioURL;
									additionalAudioElement.controls = true;

									const playAdditionalAudio = () => {
										fetch(audioURL)
											.then(response => response.blob())
											.then(blob => {
												const objectURL = URL.createObjectURL(blob);
												additionalAudioElement.src = objectURL; 
												additionalAudioElement.controls = true;
												additionalAudioElement.play(); 
											})
											.catch(error => {
												console.error('Error fetching audio:', error);
											});
									};
									playAdditionalAudio();
								});

								const responseDisplay = document.getElementById('responseDisplay');
								responseDisplay.appendChild(additionalAudioElement);
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