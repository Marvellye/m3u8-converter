FROM jrottenberg/ffmpeg:4.4-alpine

WORKDIR /app

COPY converter.py /app/converter.py
COPY requirements.txt /app/requirements.txt

RUN pip install -r requirements.txt

ENTRYPOINT ["python3", "/app/converter.py"]
  
