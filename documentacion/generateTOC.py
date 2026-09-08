import os

def generate_readme(directory, depth=2, indent='-'):
    """
    Genera un README.md con la estructura de directorios ordenada alfabéticamente.

    Args:
        directory: Directorio raíz desde el que se comenzará a recorrer.
        depth: Profundidad máxima a la que se recorrerán los directorios.
        indent: Carácter utilizado para indicar la profundidad.
    """

    with open('README.md', 'w') as f:
        f.write('# Estructura de Directorios\n')
        for root, dirs, files in os.walk(directory):
            rel_path = os.path.relpath(root, directory)
            depth_level = rel_path.count(os.sep)
            if depth_level <= depth:
                indent_str = indent * depth_level
                f.write(f"{indent_str}* {os.path.basename(root)}\n")
                for file in sorted(files):
                    if file.endswith('.md'):
                        relative_path = os.path.relpath(os.path.join(root, file), directory)
                        #f.write(f"* [{title}]({relative_path})\n")
                        f.write(f"{indent_str}  - [{title}]({relative_path})\n")

# Personaliza el directorio raíz y la profundidad
generate_readme('.', depth=2)  # Recorre hasta una profundidad de 3 niveles
