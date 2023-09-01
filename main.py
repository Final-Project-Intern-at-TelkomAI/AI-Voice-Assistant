import streamlit as st
import numpy as np
import sounddevice as sd
import tempfile
import wave
import os

# Variabel global untuk mengontrol rekaman
recording = False
audio_frames = []

# Konfigurasi rekaman audio
sample_rate = 44100  # Frekuensi sampel (Hz)
chunk_size = 1024  # Ukuran chunk (jumlah sampel per frame)

# Tempat penyimpanan sementara hasil rekaman
temp_audio_file = tempfile.NamedTemporaryFile(delete=False, suffix=".wav")

# Fungsi untuk memulai atau menghentikan rekaman
def toggle_recording():
    global recording
    if not recording:
        try:
            start_recording()
        except Exception as e:
            st.error(f"Error saat memulai rekaman: {str(e)}")
    else:
        try:
            stop_recording()
        except Exception as e:
            st.error(f"Error saat menghentikan rekaman: {str(e)}")

# Fungsi untuk memulai rekaman
def start_recording():
    global recording, audio_frames
    audio_frames = []  # Bersihkan frame audio sebelumnya
    recording = True  # Ubah status rekaman menjadi True saat rekaman dimulai
    st.text("Mulai merekam...")

# Fungsi untuk menghentikan rekaman
def stop_recording():
    global recording
    recording = False  # Ubah status rekaman menjadi False saat rekaman dihentikan
    st.text("rekaman telah dihentikan")  # Ubah teks saat rekaman telah dihentikan

    # Simpan hasil rekaman ke file sementara
    audio_data = np.concatenate(audio_frames, axis=None)
    with wave.open(temp_audio_file.name, 'wb') as wf:
        wf.setnchannels(1)
        wf.setsampwidth(2)
        wf.setframerate(sample_rate)
        wf.writeframes(audio_data.tobytes())

# Judul aplikasi
st.title("YUK NGOBROL")

# Tombol untuk mengendalikan rekaman
if not recording:
    record_button = st.button("Tekan untuk Berbicara")
else:
    record_button = st.button("Hentikan")

if record_button:
    toggle_recording()

# Tombol "Hentikan" hanya akan muncul jika sedang dalam rekaman
if recording:
    st.button("Hentikan")
