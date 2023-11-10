import json
import requests
import streamlit as st
from bardapi import Bard

bard_token = "cwjTYB-bxzO9VJHh4eA0igEolTA9VgYHbttiWQjKd1xHHicdxHG1Cm3BEUrpOQmUrcsykQ."

def get_text_from_speech(file_name):
    """Function to get text from audio

    Returns:
        str: text from audio
    """    
    url = "https://telkom-bac-api.apilogy.id/Speech_To_Text_Service/1.0.0/stt_inference"
    payload = {'lang': 'indonesian'}
    with open(file_name, 'rb') as audio_file:
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
