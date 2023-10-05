import streamlit as st
import os
import json
import requests
from bardapi import BardCookies  # Import BardCookies

# Define a function to get text from speech
def get_text_from_speech(file):
    url = "https://telkom-bac-api.apilogy.id/Speech_To_Text_Service/1.0.0/stt_inference"
    payload = {'lang': 'indonesian'}
    files = [
        ('audio', ('file', file, 'application/octet-stream'))
    ]
    headers = {
        'accept': 'application/json',
        'x-api-key': 'XwUzo2MEl39V9LI6mhLmfwYEaYXrNA3v'
    }
    response = requests.request("POST", url, headers=headers, data=payload, files=files)
    json_return = json.loads(response.text)
    return json_return['data']['all_text'].strip()

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

# Create a Streamlit app
def main():
    st.title("Yuk Ngobrol")

    # Create a file uploader widget
    uploaded_file = st.file_uploader("Upload a voice recording:", type=['wav'])

    # If a file is uploaded, run the speech to text function
    if uploaded_file is not None:
        text = get_text_from_speech(uploaded_file)

        # Display the text to the user
        st.write("Text from speech:", text)

        # Initialize BardCookies with your cookie values
        cookie_dict = {
            "__Secure-1PSID": "bgiwHK4yCFCqe6wsgReEtMiEOqY2EgA5vN2Hl62rdV83PLSchYMBnsAY83_V-vvai4gTHQ.",
            "__Secure-1PSIDTS": "sidts-CjEB3e41hZMbVZ6it6cDE6F-TlKuXtSNoaMcT1xuBN2ZlzdHsW5Dj71u_x75nEgiQJf6EAA",
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
            print(first_choice_content)
        # Print or use the content as needed
        else:
            print("No choices available in the response.")

        # Display the Bard response to the user
        st.write("Bard response:", bard_response)

        # Get audio from the Bard response
        audio = get_audio_from_text(first_choice_content)

        # Play the audio for the user
        st.audio(audio, format="audio/wav")

if __name__ == '__main__':
    main()
