Desarrollaré un ejemplo más completo de normalización con una base de datos de gestión de un centro educativo.

# Ejemplo Completo de Normalización de Base de Datos

## Base de Datos Inicial Sin Normalizar

**Tabla MATRICULAS**:
| ID_Matricula | Alumno | DNI | Dirección | Curso | Tutor | Especialidad_Tutor | Teléfono_Tutor | Nota_Matematicas | Nota_Lengua | Nota_Historia |
|--------------|--------|-----|-----------|-------|-------|-------------------|----------------|-----------------|-------------|---------------|
| 1 | Juan Pérez | 12345678A | C/ Mayor 10 | 2º ESO A | María González | Matemáticas | 666111222 | 8 | 7 | 6 |
| 2 | Ana Martínez | 87654321B | Av. Libertad 25 | 2º ESO B | Carlos Ruiz | Lengua | 666333444 | 9 | 9 | 8 |
| 3 | Juan Pérez | 12345678A | C/ Mayor 10 | 2º ESO A | María González | Matemáticas | 666111222 | 7 | 6 | 5 |

## Problemas Detectados
- Redundancia de datos
- Dependencias parciales
- Dependencias transitivas

## Paso 1: Primera Forma Normal (1FN)
- Eliminar grupos repetitivos
- Identificar clave primaria única
- Atomizar valores

**Tabla ALUMNOS**:
| ID_Alumno | Nombre | DNI | Dirección |
|-----------|--------|-----|-----------|
| 1 | Juan Pérez | 12345678A | C/ Mayor 10 |
| 2 | Ana Martínez | 87654321B | Av. Libertad 25 |

**Tabla CURSOS**:
| ID_Curso | Nombre_Curso | Nivel |
|----------|--------------|-------|
| 1 | 2º ESO A | ESO |
| 2 | 2º ESO B | ESO |

**Tabla TUTORES**:
| ID_Tutor | Nombre | Especialidad | Teléfono |
|----------|--------|--------------|----------|
| 1 | María González | Matemáticas | 666111222 |
| 2 | Carlos Ruiz | Lengua | 666333444 |

**Tabla MATRICULAS**:
| ID_Matricula | ID_Alumno | ID_Curso | ID_Tutor | Nota_Matematicas | Nota_Lengua | Nota_Historia |
|--------------|-----------|----------|----------|-----------------|-------------|---------------|
| 1 | 1 | 1 | 1 | 8 | 7 | 6 |
| 2 | 2 | 2 | 2 | 9 | 9 | 8 |
| 3 | 1 | 1 | 1 | 7 | 6 | 5 |

- Objetivo: Eliminar grupos repetitivos y definir una clave primaria única.
En la tabla inicial "MATRICULAS" había redundancia de datos como la dirección y teléfono del alumno, así como del tutor.
- Para 1FN, se separan estas entidades en tablas independientes como "ALUMNOS", "CURSOS" y "TUTORES", con claves primarias propias.
- La tabla "MATRICULAS" queda con solo los campos que dependen directamente de la clave compuesta (ID_Alumno, ID_Curso, ID_Tutor).

## Paso 2: Segunda Forma Normal (2FN)
- Eliminar dependencias parciales
- Separar información que no depende completamente de la clave primaria

**Tabla NOTAS**:
| ID_Matricula | Asignatura | Nota |
|--------------|------------|------|
| 1 | Matemáticas | 8 |
| 1 | Lengua | 7 |
| 1 | Historia | 6 |
| 2 | Matemáticas | 9 |
| 2 | Lengua | 9 |
| 2 | Historia | 8 |
| 3 | Matemáticas | 7 |
| 3 | Lengua | 6 |
| 3 | Historia | 5 |

**Tabla MATRICULAS** (modificada):
| ID_Matricula | ID_Alumno | ID_Curso | ID_Tutor |
|--------------|-----------|----------|----------|
| 1 | 1 | 1 | 1 |
| 2 | 2 | 2 | 2 |
| 3 | 1 | 1 | 1 |

- Objetivo: Eliminar dependencias parciales, es decir, campos que no dependen completamente de la clave primaria.
- En la tabla "MATRICULAS" modificada, las notas de las asignaturas no dependían de toda la clave primaria, sino solo de ID_Matricula.
- Por ello, se crea una nueva tabla "NOTAS" que contiene ID_Matricula, ID_Asignatura y la nota correspondiente.
- Esto elimina la dependencia parcial y mejora la integridad de los datos.

## Paso 3: Tercera Forma Normal (3FN)
- Eliminar dependencias transitivas
- Asegurar que los campos no clave dependan directamente de la clave primaria

**Tabla ASIGNATURAS**:
| ID_Asignatura | Nombre_Asignatura | Departamento |
|---------------|-------------------|--------------|
| 1 | Matemáticas | Científico |
| 2 | Lengua | Lingüístico |
| 3 | Historia | Humanístico |

**Tabla NOTAS** (modificada):
| ID_Matricula | ID_Asignatura | Nota |
|--------------|---------------|------|
| 1 | 1 | 8 |
| 1 | 2 | 7 |
| 1 | 3 | 6 |
| 2 | 1 | 9 |
| 2 | 2 | 9 |
| 2 | 3 | 8 |
| 3 | 1 | 7 |
| 3 | 2 | 6 |
| 3 | 3 | 5 |

- Objetivo: Eliminar dependencias transitivas, es decir, campos que dependen de otros campos no clave.
- En la tabla "NOTAS", el nombre de la asignatura dependía de ID_Asignatura, pero no formaba parte de la clave primaria.
- Se crea una nueva tabla "ASIGNATURAS" que contiene ID_Asignatura y el nombre de la asignatura.
Esto separa completamente los campos que no dependen directamente de la clave primaria.

## Beneficios de la Normalización
1. Eliminación de redundancias
2. Integridad de datos
3. Flexibilidad en consultas
4. Mejor estructura de la información

## Consideraciones Finales
- Cada forma normal resuelve problemas específicos
- La normalización es un proceso iterativo
- No siempre es necesario llegar a la 3FN

Este ejemplo muestra un proceso completo de normalización con cambios significativos en cada forma normal.