<?php
// Check if the request is a GET request and if the 'url' parameter is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['url'])) {
    $m3u8Link = $_GET['url'];

    // Validate the URL
    if (filter_var($m3u8Link, FILTER_VALIDATE_URL)) {
        // Create a unique output file name in the tmp directory
        $outputFile = 'tmp/' . uniqid('video_', true) . '.mp4';
        $outputPath = __DIR__ . '/' . $outputFile;

        // Run FFmpeg to convert the M3U8 to MP4
        $command = "ffmpeg -i '$m3u8Link' -c copy -bsf:a aac_adtstoasc '$outputPath' 2>&1";
        exec($command, $output, $return_var);

        // Check if the conversion was successful
        if ($return_var === 0) {
            // Generate the download link
            $downloadLink = "https://m3u8-converter.onrender.com/{$outputFile}";

            // Return a JSON response with the download link
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'url' => $downloadLink
            ]);
        } else {
            // Return a JSON response with the error message
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Conversion failed. Error: ' . implode("\n", $output)
            ]);
        }
    } else {
        // Return a JSON response for invalid URL
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid URL provided.'
        ]);
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $m3u8Link = $_POST['m3u8_link'];
      if (filter_var($m3u8Link, FILTER_VALIDATE_URL)) {
          $outputFile = 'tmp/' . uniqid('video_', true) . '.mp4';
          $command = "ffmpeg -i '$m3u8Link' -c copy -bsf:a aac_adtstoasc '$outputFile' 2>&1";
          exec($command, $output, $return_var);
          if ($return_var === 0) {
              $downloadLink = "https://m3u8-converter.onrender.com/{$outputFile}";
              echo "Conversion successful! Download your file <a href='{$downloadLink}'>here</a>.";
          } else {
              echo "Conversion failed. Error: " . implode("\n", $output);
          }
      } else {
          echo "Invalid URL provided.";
      }
  }
    
elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
 echo'<!DOCTYPE html>
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
  </html>'
      }

    ?>
