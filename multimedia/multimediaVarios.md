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
```

## Varios
 * **PureRef**: Aplicación gratuíta para poner y administrar imágenes de referencia y tenerlas visualizadas => https://www.pureref.com/
 * **IA gratuita para generación de imágenes con Google Colab** => https://github.com/lllyasviel/Fooocus?tab=readme-ov-file