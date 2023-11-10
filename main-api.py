import os
import io
import shutil
import tempfile
from fastapi import FastAPI, File, UploadFile
from utils import get_text_from_speech, ask_bard, get_audio_from_text

tmp_dir = f"{os.getcwd()}\\temp"

if not os.path.exists(tmp_dir):
    os.mkdir(tmp_dir)

app = FastAPI()

@app.get("/")
async def root():
    return {"message": "Hello World"}
 
@app.post("/upload_audio")
async def upload_audio(file: UploadFile = File(...)):
    with open(f"{tmp_dir}\\{file.filename}", "wb") as f:
        shutil.copyfileobj(file.file, f)
    return {"filename": file.filename}

@app.post('/askNusa')
async def ask_nusa(file: UploadFile = File(...)):
    text = None
    answer = None
    
    with tempfile.NamedTemporaryFile(suffix=".wav", dir=tmp_dir, delete=False) as tmp_wav_file:
        tmp_file = tmp_wav_file.name
        split_tmp_wav_file = tmp_wav_file.name.split(".wav")
        tmp_file_answer = f"{split_tmp_wav_file[0]}_answer.wav"
    with open(tmp_file, "wb") as f:
        shutil.copyfileobj(file.file, f)
    text = get_text_from_speech(tmp_file)
    os.remove(tmp_file)
    if text is not None:
        answer = ask_bard(text)
    if answer is not None:
        answer_audio = io.BytesIO(get_audio_from_text(answer))
        with open(tmp_file_answer, "wb") as fl:
            shutil.copyfileobj(answer_audio, fl)
    return text, answer, tmp_file_answer