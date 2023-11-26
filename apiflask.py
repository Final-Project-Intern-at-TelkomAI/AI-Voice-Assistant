from flask import Flask, request, jsonify, send_file
from werkzeug.utils import secure_filename
import tempfile
import shutil
import os
import io
from utils import get_text_from_speech, ask_bard, get_audio_from_text
from flask_restx import Api, Resource, fields
from flask_cors import CORS

app = Flask(__name__)
CORS(app)
api = Api(app)


tmp_dir = "C:/xampp/htdocs/AI-Voice-Assistant/temp"  # Ganti dengan direktori temp Anda
tmp_file_answer = None  # Definisikan sebagai variabel global

answer_model = api.model('Answer', {
    'text_input': fields.String(description='Text input from audio'),
    'text_answer': fields.String(description='Textual answer'),
    'file_answer': fields.String(description='Path to the answer audio file')
})

@api.route('/askNusa', doc={"description": "Upload audio and get the response"})
class AskNusa(Resource):
    @api.response(200, 'Success', answer_model)  # Gunakan model yang telah didaftarkan
    @api.expect(api.parser().add_argument('audio', type=str, location='files'))
    def post(self):
        global tmp_file_answer  # Gunakan variabel global tmp_file_answer
        text = None
        answer = None

        if 'audio' not in request.files:
            return jsonify({"message": "No audio file uploaded"}), 400

        audio_file = request.files['audio']
        if audio_file.filename == '':
            return jsonify({"message": "No selected file"}), 400

        with tempfile.NamedTemporaryFile(suffix=".wav", dir=tmp_dir, delete=False) as tmp_wav_file:
            tmp_file = tmp_wav_file.name
            split_tmp_wav_file = tmp_wav_file.name.split(".wav")
            tmp_file_answer = f"{split_tmp_wav_file[0]}_answer.wav"
            split_tmp_wav_file_response = tmp_file_answer.split("\\")
            tmp_file_answer_response = split_tmp_wav_file_response[len(split_tmp_wav_file_response)-1]

        audio_file.save(tmp_file)
        text = get_text_from_speech(tmp_file)
        os.remove(tmp_file)
        
        if text is not None:
            answer = ask_bard(text)
        
        if answer is not None:
            answer_audio = io.BytesIO(get_audio_from_text(answer))
            with open(tmp_file_answer, "wb") as fl:
                shutil.copyfileobj(answer_audio, fl)

        return {
            "text_input": text,
            "text_answer": answer,
            "file_answer": tmp_file_answer_response
        }

@app.route('/getAnswerAudio', methods=['GET'])
def get_answer_audio():
    global tmp_file_answer  # Gunakan variabel global tmp_file_answer
    if tmp_file_answer is None or not os.path.exists(tmp_file_answer):
        return jsonify({"message": "File not found"}), 404

    # Ganti content_type dengan tipe audio yang Anda perlukan
    return send_file(tmp_file_answer, mimetype='audio/wav')

if __name__ == "__main__":
    app.run(debug=True) 
    
