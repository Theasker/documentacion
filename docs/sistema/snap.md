# Instalar snap

    yay -S snapd
    sudo systemctl enable --now apparmor.service
    sudo systemctl enable --now snapd.apparmor.service
    sudo systemctl enable snapd
    sudo systemctl start snapd