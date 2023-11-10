import os
import json
import wave
import pyaudio
import requests
import tempfile
import streamlit as st
from bardapi import Bard

rate = 44100
chunk = 1024
channels = 1
format = pyaudio.paInt16
tmp_dir = f"{os.getcwd()}\\temp"
bard_token = "cwjTYB-bxzO9VJHh4eA0igEolTA9VgYHbttiWQjKd1xHHicdxHG1Cm3BEUrpOQmUrcsykQ."

if not os.path.exists(tmp_dir):
    os.mkdir(tmp_dir)

def start_recording(record):
    """Function to start recording audio

    Args:
        record (pyaudio.PyAudio.Stream): _description_
    """    
    while True:
        data = record.read(1024)
        st.session_state.audio_data.append(data)

def save_tmp_audio(audio):
    """Function to save recorded audio

    Args:
        audio (pyaudio.PyAudio): _description_
    """    
    with tempfile.NamedTemporaryFile(suffix=".wav", dir=tmp_dir, delete=False) as tmp_wav_file:
        wf = wave.open(tmp_wav_file.name, 'wb')
        wf.setnchannels(channels)
        wf.setsampwidth(audio.get_sample_size(format))
        wf.setframerate(rate)
        wf.writeframes(b''.join(st.session_state.audio_data))
        wf.close()
    st.session_state.tmp_audio_file = tmp_wav_file.name

def play_audio(audio):
    """Function to play audio

    Args:
        audio (pyaudio.PyAudio): _description_
    """    
    wf = wave.open(st.session_state.tmp_audio_file, 'rb')
    playback = audio.open(format = audio.get_format_from_width(wf.getsampwidth()),
                channels = wf.getnchannels(), rate = wf.getframerate(),
                output = True)
    data = wf.readframes(chunk)
    while data:
        playback.write(data)
        data = wf.readframes(chunk)
    playback.close()

def get_text_from_speech():
    """Function to get text from audio

    Returns:
        str: text from audio
    """    
    url = "https://telkom-bac-api.apilogy.id/Speech_To_Text_Service/1.0.0/stt_inference"
    payload = {'lang': 'indonesian'}
    with open(st.session_state.tmp_audio_file, 'rb') as audio_file:
        files = [('audio', ('file', audio_file, 'application/octet-stream'))]
        
        headers = {
            'accept': 'application/json',
            'x-api-key': 'XwUzo2MEl39V9LI6mhLmfwYEaYXrNA3v'
        }
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

def ask_bard(text):
    """Function to get answer from Bard AI

    Args:
        text (str): prompt/question

    Returns:
        str: answer from Bard AI
    """    
    first_choice_content = None
    bard = Bard(token=bard_token)
    bard_response = bard.get_answer(text)

    # Access the "choices" section
    choices = bard_response.get("choices", [])

    # Check if there are choices available and it's not empty
    if choices:
        # Access the content within the first choice
        first_choice_content = choices[0].get("content", [])[0]
    else:
        st.write("No choices available in the response.")
    return first_choice_content

# Define a function to get audio from text using TTS service
def get_audio_from_text(text):
    """Function to get audio from text

    Args:
        text (str): text to convert to audio

    Returns:
        bytes: audio
    """    
    url = "https://telkom-bac-api.apilogy.id/tts/1.0.0/v1"
    payload = f"<?xml version=\"1.0\" encoding=\"UTF-8\"?><speak version=\"1.0\" xml:lang=\"id-ID\"><voice name=\"id-ID-ArdiNeural\" xml:lang=\"id-ID\" xml:gender=\"Male\">{text}</voice></speak>"
    headers = {
        'accept': '*/*',
        'x-api-key': 'LW4O1XQgpIuTtNjsFg9DonTbTU3TElrp',
        'Content-Type': 'application/ssml+xml'
    }
    response = requests.request("POST", url, headers=headers, data=payload)
    return response.content

def increment_counter(increment_value=0):
    """Function to counting state

    Args:
        increment_value (int, optional): Defaults to 0.
    """    
    st.session_state.state += increment_value

def main(): 
    text = None
    answer = None
    audio = pyaudio.PyAudio()
    record = audio.open(format=format, channels=channels, rate=rate,
                        input=True, frames_per_buffer=1024)

    st.title("Yuk Ngobrol")
    if 'state' not in st.session_state:
        st.session_state.state = 0
        st.session_state.audio_data = []
        st.session_state.tmp_audio_file = ''
    st.button('Start Recording', on_click=increment_counter,
              kwargs=dict(increment_value=1))

    if st.session_state.state == 0:
        st.write("Click to start recording")
    elif st.session_state.state == 1:
        st.write("Recording... (Click again to stop recording)")
        start_recording(record)
    elif st.session_state.state == 2:
        st.write("Recording Stopped")
        record.stop_stream()
        record.close()
        save_tmp_audio(audio)
        st.write("Audio saved as:", st.session_state.tmp_audio_file)
        
        if os.path.exists(st.session_state.tmp_audio_file):
            st.write("Playing Recorded Audio...")
            play_audio(audio)
            audio.terminate()
            text = get_text_from_speech()
            st.write("Text from speech:", text)
            os.remove(st.session_state.tmp_audio_file)
        else:
            print(f"Audio file not found")
        
        if text is not None:
            answer = ask_bard(text)

        if answer is not None:
            st.write("Bard response:", answer)

            answer_audio = get_audio_from_text(answer)
            st.audio(answer_audio, format="audio/wav", start_time=0)
            st.write('Click "Start Recording" button to start over')
    else:
        st.session_state.state = 0
        st.session_state.audio_data = []
        st.session_state.tmp_audio_file = ''
        st.write("Click to start recording")

if __name__ == '__main__':    
    main()