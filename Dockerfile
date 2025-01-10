# Use lightweight FFmpeg + Python image
FROM jrottenberg/ffmpeg:4.4-alpine

# Set work directory
WORKDIR /app

# Copy files
COPY . /app

# Install dependencies
RUN pip install -r requirements.txt

# Expose Flask port
EXPOSE 5000

# Start the server using Gunicorn
CMD ["gunicorn", "-w", "4", "-b", "0.0.0.0:5000", "wsgi:app"]
