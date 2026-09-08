# Comandos varios sobre multimedia

## Mezclar un fichero de video con otro de audio con ffmpeg
  ffmpeg -i video.mp4 -i audio.wav -c:v copy -c:a aac output.mp4
