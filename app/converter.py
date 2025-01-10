import os
import subprocess
import threading
from flask import Flask, request, jsonify

app = Flask(__name__)

OUTPUT_DIR = "/app/output"
os.makedirs(OUTPUT_DIR, exist_ok=True)

def convert_m3u8_to_mp4(input_url, output_filename):
    output_path = os.path.join(OUTPUT_DIR, output_filename)
    
    # Optimized FFmpeg command for speed
    command = [
        "ffmpeg",
        "-hwaccel", "auto",  # Hardware acceleration (if available)
        "-i", input_url,     # Input M3U8 file
        "-c:v", "libx264",   # H.264 encoding for best compression and speed
        "-preset", "fast",   # Fast preset for speed
        "-threads", "4",     # Multi-threading (adjust based on CPU)
        "-y", output_path    # Overwrite existing files
    ]
    
    process = subprocess.run(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    
    if process.returncode == 0:
        return output_path
    else:
        return None

def run_conversion(input_url, output_filename):
    output_file = convert_m3u8_to_mp4(input_url, output_filename)
    if output_file:
        print(f"Conversion complete: {output_file}")
    else:
        print("Conversion failed")

@app.route('/convert', methods=['GET', 'POST'])
def convert():
    if request.method == 'POST':
        data = request.json
        input_url = data.get('url')
        output_filename = data.get('output', 'output.mp4')
    else:  # GET request
        input_url = request.args.get('url')
        output_filename = request.args.get('output', 'output.mp4')

    if not input_url:
        return jsonify({"error": "No URL provided"}), 400
    
    # Run in a separate thread for fast API response
    threading.Thread(target=run_conversion, args=(input_url, output_filename)).start()
    
    return jsonify({
        "message": "Conversion started",
        "output_file": f"{OUTPUT_DIR}/{output_filename}"
    })

if __name__ == '__main__':
    app.run(host="0.0.0.0", port=5000)
  
