import streamlit as st
import os
import json
import requests
from bardapi import BardCookies  # Import BardCookies
import tempfile
import sounddevice as sd
import wavio
import time

# Variables to control recording
recording = False
audio_data = None
duration = 10

# Function to start/stop recording
def toggle_recording():
    global recording, audio_data, start_time, end_time, duration
    start_time = time.time()
    if not recording:
        st.write("Recording...")
        #audio_data = record_audio(duration)
        #if st.button("save") :
        #    end_time = time.time()
        #    duration = end_time - start_time
        audio_data = record_audio(duration)
        recording = not recording

# Function to stop recording
#def stop_recording():
    #global audio_data
    #audio_data = record_audio(duration)
    #sd.wait()
    #return audio_data
    # Reset the audio_data variable

# Function to record audio
def record_audio(duration):
    audio_data = sd.rec(int(duration * 44100), samplerate=44100, channels=2, dtype='int16')
    sd.wait()
    return audio_data

# Function to play audio
def play_audio(audio_data):
    sd.play(audio_data, 44100)
    sd.wait()

# Function to save audio to a temporary .wav file
custom_save_directory = "C:\\Users\\Lenovo\\Music"

def save_audio_to_file(audio_data):
    global saved_audio_file_path
    with tempfile.NamedTemporaryFile(suffix=".wav", dir=custom_save_directory, delete=False) as tmp_wav_file:
        wavio.write(tmp_wav_file.name, audio_data, 44100, sampwidth=2)
        saved_audio_file_path = tmp_wav_file.name
        st.write("Audio saved as:", tmp_wav_file.name)
        
        
# Define a function to get text from speech
def get_text_from_speech(audio_file):
    url = "https://telkom-bac-api.apilogy.id/Speech_To_Text_Service/1.0.0/stt_inference"
    payload = {'lang': 'indonesian'}
    with open(saved_audio_file_path, 'rb') as audio_file:
        files = [('audio', ('file', audio_file, 'application/octet-stream'))]
        
        headers = {
            'accept': 'application/json',
            'x-api-key': 'XwUzo2MEl39V9LI6mhLmfwYEaYXrNA3v'
        }
    #response = requests.request("POST", url, headers=headers, data=payload, files=files)
    #json_return = json.loads(response.text)
    #return json_return['data']['all_text'].strip()
        try:
                    response = requests.post(url, headers=headers, data=payload, files=files)
                    response.raise_for_status()  # Raise an exception for HTTP errors
                    
                    if response.status_code == 200:
                        try:
                            json_return = response.json()
                            if 'data' in json_return and 'all_text' in json_return['data']:
                                return json_return['data']['all_text'].strip()
                            else:
                                return "API response does not contain the expected JSON structure."
                        except json.JSONDecodeError as e:
                            return f"Failed to decode JSON response: {e}"
                    else:
                        return f"API request failed with status code: {response.status_code}"
        except requests.exceptions.RequestException as e:
            return f"Request error: {e}"
    return None


# Define a function to get audio from text using TTS service
def get_audio_from_text(text):
    url = "https://telkom-bac-api.apilogy.id/tts/1.0.0/v1"
    payload = f"<?xml version=\"1.0\" encoding=\"UTF-8\"?><speak version=\"1.0\" xml:lang=\"id-ID\"><voice name=\"id-ID-ArdiNeural\" xml:lang=\"id-ID\" xml:gender=\"Male\">{text}</voice></speak>"
    headers = {
        'accept': '*/*',
        'x-api-key': 'LW4O1XQgpIuTtNjsFg9DonTbTU3TElrp',
        'Content-Type': 'application/ssml+xml'
    }
    response = requests.request("POST", url, headers=headers, data=payload)
    return response.content

# Define a variable to store the file path of the saved audio
#saved_audio_file_path = None

# Create a Streamlit app
def main():
    global recording, audio_data
    st.title("Yuk Ngobrol")

    # Create a button to start/stop recording audio
    if not recording:
        if st.button("Start Recording"):
            toggle_recording()
    
    if recording:
        st.write("Recording in progress...")
        st.write()
        #text = get_text_from_speech(saved_audio_file_path)
        if audio_data is not None:
            st.write("Playing Recorded Audio...")
            play_audio(audio_data)

            # Save the audio data to a file and update saved_audio_file_path
            save_audio_to_file(audio_data)

            # Display the saved audio file path
            
            #st.write("Audio saved as:", saved_audio_file_path)
            text = get_text_from_speech("C:/Users/Lenovo/Music/tmp2bt_d6n1.wav")
            
            # Check if the saved audio file exists
            if saved_audio_file_path and os.path.exists(saved_audio_file_path):
                st.write("Processing the saved audio...")

                
            # Get text from the saved audio file using speech to text function
                st.write("Text from speech:", text)

            # Get audio from the text and pass it to get_audio_from_text function

        # Initialize BardCookies with your cookie values
            cookie_dict = {
                "__Secure-1PSID": "cQiwHKuBiVPn-RZCSE0qGpO7GvLahsE-vFeeAZ5RmA2bNyM4LUEl7i5aQ-Xd-i4fk61yvQ.",
                "__Secure-1PSIDTS": "sidts-CjEB3e41hQ2H-rbPK8y2f1k5wItKolgYfbWDpRYEgHCnMgCRa2y316KmzKP8agyPGoWJEAA",
                # Any other cookie values you want to pass to the session object.
            }
            bard = BardCookies(cookie_dict=cookie_dict)

            # Get a response from Bard
            bard_response = bard.get_answer(text)

            # Assuming bard_response is the JSON response you provided

            # Access the "choices" section
            choices = bard_response.get("choices", [])

            # Check if there are choices available and it's not empty
            if choices:
                # Access the content within the first choice
                first_choice_content = choices[0].get("content", [])[0]
                st.write("Bard response:", first_choice_content)

                # Get audio from the Bard response
                audio = get_audio_from_text(first_choice_content)

                # Play the audio for the user
                st.audio(audio, format="audio/wav", start_time=0)

            else:
                st.write("No choices available in the response.")

if __name__ == '__main__':
    main()
