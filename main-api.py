import os
import io
import base64
import shutil
import tempfile
from fastapi import FastAPI, Form, File, UploadFile
from fastapi.responses import FileResponse
from utils import get_text_from_speech, ask_bard, get_audio_from_text

tmp_dir = f"{os.getcwd()}\\temp"

if not os.path.exists(tmp_dir):
    os.mkdir(tmp_dir)

app = FastAPI()

@app.get("/")
async def root():
    return {"message": "Hello World"}

@app.get('/getLatestAudio')
async def get_latest_audio():
    files = os.listdir(tmp_dir)
    audio_files = [f for f in files if f.endswith('.wav')]
    
    if not audio_files:
        return {"message": "Tidak ada file audio yang ditemukan."}
    
    latest_audio = max(audio_files, key=lambda x: os.path.getctime(os.path.join(tmp_dir, x)))
    file_path = os.path.join(tmp_dir, latest_audio)
    
    return FileResponse(file_path, media_type='audio/wav')
 
@app.post("/upload_audio")
async def upload_audio(file: UploadFile = File(...)):
    with open(f"{tmp_dir}\\{file.filename}", "wb") as f:
        shutil.copyfileobj(file.file, f)
    return {"filename": file.filename}

@app.post('/askNusa')
async def ask_nusa(audio: UploadFile = File(...)):
    text = None
    answer = None
    
    with tempfile.NamedTemporaryFile(suffix=".wav", dir=tmp_dir, delete=False) as tmp_wav_file:
        tmp_file = tmp_wav_file.name
        split_tmp_wav_file = tmp_wav_file.name.split(".wav")
        tmp_file_answer = f"{split_tmp_wav_file[0]}_answer.wav"
    with open(tmp_file, "wb") as f:
        shutil.copyfileobj(audio.file, f)
    text = get_text_from_speech(tmp_file)
    os.remove(tmp_file)
    if text is not None:
        answer = ask_bard(text)
    if answer is not None:
        answer_audio = io.BytesIO(get_audio_from_text(answer))
        with open(tmp_file_answer, "wb") as fl:
            shutil.copyfileobj(answer_audio, fl)
    return [
        {
        "text_input": text,
        "text_answer": answer,
        "file_answer": tmp_file_answer
        }
    ]
        
@app.post('/askNusa_base64')
async def ask_nusa_base64(audio: str = Form(...)):
    text = None
    answer = None
    audio_data = io.BytesIO(base64.b64decode(audio))
    with tempfile.NamedTemporaryFile(suffix=".wav", dir=tmp_dir, delete=False) as tmp_wav_file:
        tmp_file = tmp_wav_file.name
    with open(tmp_file, "wb") as f:
        shutil.copyfileobj(audio_data, f)
    text = get_text_from_speech(tmp_file)
    os.remove(tmp_file)
    if text is not None:
        answer = ask_bard(text)
    if answer is not None:
        answer_audio = get_audio_from_text(answer)
        base64_audio_base64 = base64.b64encode(answer_audio)
    return [
        {
        "text_input": text,
        "text_answer": answer,
        "base64_answer": base64_audio_base64
        }
    ]