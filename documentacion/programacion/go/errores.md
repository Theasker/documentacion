# Módulo 7: Tratamiento Idiomático de Errores en Go

En Go los errores son **valores** devueltos explícitamente en el último parámetro de retorno (no existen excepciones `try/catch`).

---

## 1. Creación y Envoltura (*Wrapping*)
- `errors.New("mensaje")`: Para errores estáticos sencillos.
- `fmt.Errorf("contexto: %w", err)`: Envuelve un error existente agregando contexto gracias al verbo `%w`.

---

## 2. Comprobación de Errores (`Is` vs `As`)
- **`errors.Is(err, ErrCentinela)`**: Comprueba si el error (o cualquiera de sus capas envueltas) contiene un **valor** de error centinela específico.
- **`errors.As(err, &varEstructura)`**: Comprueba si el error coincide con un **tipo de estructura** personalizado y **extrae** sus datos en la variable proporcionada.

---

## 3. Panic y Recover
- **`panic`**: Reservado exclusivamente para fallos fatales e irrecuperables de la aplicación.
- **`recover`**: Captura un `panic` (siempre dentro de un `defer`) para hacer un cierre controlado.