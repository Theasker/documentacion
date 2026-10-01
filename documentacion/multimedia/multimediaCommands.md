# Comandos varios sobre multimedia

## Mezclar un fichero de video con otro de audio con ffmpeg
  ffmpeg -i video.mp4 -i audio.wav -c:v copy -c:a aac output.mp4

## `yt-dlp`
 * https://github.com/yt-dlp/yt-dlp
```bash
yt-dlp --print filename --write-auto-subs -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index) %(title)s.%(ext)s" <youtube_url>
#alias yt-dlp='yt-dlp -f "bestvideo[height<=?1080]+bestaudio/best" -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
#alias yt-dlps='yt-dlp -f "bestvideo[height<=?1080]+bestaudio/best" --write-auto-subs -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
alias yt-dlp='yt-dlp --cookies-from-browser firefox -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
alias yt-dlps='yt-dlp --cookies-from-browser firefox --write-auto-subs --sub-lang en -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
alias yt-mp3='yt-dlp -x --audio-format mp3 '
```

### Descargar música desde windows en mp3 con yt-dlp

Hay que tener instalado **ffmpeg** por lo que primero hay que descargarlo desde https://github.com/yt-dlp/FFmpeg-Builds/releases/download/latest/ffmpeg-master-latest-win64-gpl.zip, luego descomprimirlo y copiar la ruta del directorio bin donde está el ejecutable.

El comando para descargar con la mejor calidad de audio posible, sería:
```bash
yt-dlp --ffmpeg-location "$HOME/.local/bin" -x --audio-format mp3 --audio-quality 0 <url>
# Con información de la canción
yt-dlp --ffmpeg-location "$HOME/.local/bin" -x --audio-format mp3 --audio-quality 0 -o "%(artist,uploader)s-%(album)s-%(track_number,playlist_index)s-%(title)s.%(ext)s" <url>

