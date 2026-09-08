# Aplicaciones multimedia

## Spotify

    yay -S spotify-adblock-git
    

 * GNOME Network Displays => Envía nuestro escritorio a la TV 
   ``` 
   yay -S gnome-network-displays
   ```
 * Descargar canciones de spotify con docker
   ```
   docker run --rm -v $(pwd):/music spotdl/spotify-downloader download <url_spotify>
   ```
 * https://excalidraw.com/ => Creación de esquemas y dibujos

## Youtube
 * https://github.com/yt-dlp/yt-dlp
```bash
yt-dlp --print filename --write-auto-subs -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index) %(title)s.%(ext)s" <youtube_url>
#alias yt-dlp='yt-dlp -f "bestvideo[height<=?1080]+bestaudio/best" -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
#alias yt-dlps='yt-dlp -f "bestvideo[height<=?1080]+bestaudio/best" --write-auto-subs -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
alias yt-dlp='yt-dlp --cookies-from-browser firefox -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
alias yt-dlps='yt-dlp --cookies-from-browser firefox --write-auto-subs --sub-lang en -o "%(upload_date>%Y-%m-%d)s %(channel)s - %(playlist_index)s - %(title)s.%(ext)s" '
alias yt-mp3='yt-dlp -x --audio-format mp3 '

```

## Varios
 * **PureRef**: Aplicación gratuíta para poner y administrar imágenes de referencia y tenerlas visualizadas => https://www.pureref.com/
 * **IA gratuita para generación de imágenes con Google Colab** => https://github.com/lllyasviel/Fooocus?tab=readme-ov-file
 * **DupeGuru**: Revisa y administra duplicados de ficheros => https://dupeguru.com/
 * **Songrec**: Shazam para linux. Usa los servidores de shazam para reconocer la canción que está sonando por altavoces o por micrófono.