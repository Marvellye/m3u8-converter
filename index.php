<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $m3u8Link = $_POST['m3u8_link'];
      if (filter_var($m3u8Link, FILTER_VALIDATE_URL)) {
          $outputFile = 'tmp/' . uniqid('video_', true) . '.mp4';
          $command = "ffmpeg -i '$m3u8Link' -c copy -bsf:a aac_adtstoasc '$outputFile' 2>&1";
          exec($command, $output, $return_var);
          if ($return_var === 0) {
              $downloadLink = "http://localhost:8080/{$outputFile}";
              echo "Conversion successful! Download your file <a href='{$downloadLink}'>here</a>.";
          } else {
              echo "Conversion failed. Error: " . implode("\n", $output);
          }
      } else {
          echo "Invalid URL provided.";
      }
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>M3U8 to MP4 Converter</title>
  </head>
  <body>
      <h1>M3U8 to MP4 Converter</h1>
      <form method="POST">
          <label for="m3u8_link">M3U8 Link:</label>
          <input type="text" id="m3u8_link" name="m3u8_link" required>
          <button type="submit">Convert</button>
      </form>
  </body>
  </html>
