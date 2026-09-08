import os

def generate_readme(directory, depth):
  arrayfile = []
  #f = open(os.path.join(directory, 'README.md'), 'w')
  arrayfile.append('# Tabla de Contenidos')
  for root, dirs, files in sorted(os.walk(directory)):
    rel_path = os.path.relpath(root, directory)
    depth_level = rel_path.count(os.sep)
    # Verifico que el directorio no empieza por "."
    if(rel_path[0] != "."):
      # Verifico la profundidad que no supere la máxima
      if depth_level <= depth:
        tab = "  " * depth_level
        tabfile = "  " * depth_level + "  "
        #print(f"{tab} - {os.path.basename(root)} <dir>")
        if depth_level > 0:
          arrayfile.append(f"\n{tab}* **{os.path.basename(root)}**")
        else:
          arrayfile.append(f"\n**{os.path.basename(root)}**")
        for file in sorted(files):
          if file.endswith('.md'):
            with open(os.path.join(root, file), 'r') as md_file:
              first_line = md_file.readline()
              if first_line.startswith('# '):
                title = first_line.strip('#').strip()
                arrayfile.append(f"{tabfile}* [{title}](./{os.path.join(rel_path, file)})")
              else:
                arrayfile.append(f"{tabfile}* [{file}](./{os.path.join(rel_path, file)})")

  return arrayfile

PATH = './'
file = open(f"{PATH}index.md", 'w')
for line in generate_readme(PATH, depth=3):
  file.write(line + '\n')
  print(line)
file.close()