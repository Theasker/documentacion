# Guía Paso a Paso: Crear Conexión MCP para Visual Studio Code

## ¿Qué es MCP (Model Context Protocol)?

El **Model Context Protocol (MCP)** es un protocolo abierto que permite a las aplicaciones de IA conectarse de manera segura a diferentes sistemas, datos y servicios externos. En Visual Studio Code, MCP facilita la integración de asistentes de IA con tu entorno de desarrollo, permitiendo acceso contextual a código, documentos y herramientas.

## Requisitos Previos

Antes de comenzar, asegúrate de tener:

- **Visual Studio Code** versión 1.85 o superior
- **Extensión de IA** compatible con MCP (como GitHub Copilot, Codeium, o extensiones específicas de MCP)
- **Node.js** instalado (versión 18 o superior)
- **Git** instalado (opcional, para algunos servers MCP)
- **Conocimientos básicos de JSON** para configurar conexiones

---

## Paso 1: Instalar la Extensión MCP en VS Code

1. Abre Visual Studio Code.
2. Haz clic en el icono de **Extensiones** en la barra lateral izquierda (o presiona `Ctrl+Shift+X`).
3. En la barra de búsqueda, escribe `MCP` o `Model Context Protocol`.
4. Busca la extensión oficial de MCP o una extensión compatible (ej: "MCP Client", "MCP Server").
5. Haz clic en **Instalar**.
6. Reinicia VS Code si es necesario.

---

## Paso 2: Configurar el Archivo de Configuración MCP

MCP utiliza un archivo de configuración JSON para definir conexiones. Sigue estos pasos:

### Opción A: Usar la Configuración de VS Code

1. Abre la **Paleta de Comandos** (`Ctrl+Shift+P`).
2. Escribe `MCP: Configure Connections` y presiona Enter.
3. Se abrirá el archivo `settings.json` de VS Code.

### Opción B: Crear el Archivo Manualmente

Crea un archivo llamado `mcp-config.json` en tu carpeta de proyecto o en el directorio de configuración:

```json
{
  "mcpServers": {
    "nombre-del-server": {
      "command": "node",
      "args": ["/ruta/al/server.js"],
      "env": {
        "API_KEY": "tu-api-key-aqui"
      }
    }
  }
}
```

---

## Paso 3: Configurar un Server MCP Específico

### Ejemplo: Configurar un Server de Archivos Locales

```json
{
  "mcpServers": {
    "local-files": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-filesystem", "/ruta/a/tu/proyecto"],
      "description": "Acceso a sistema de archivos local"
    }
  }
}
```

### Ejemplo: Configurar un Server de Base de Datos

```json
{
  "mcpServers": {
    "postgres-db": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-postgres", "postgresql://usuario:password@localhost:5432/basedatos"],
      "description": "Conexión a base de datos PostgreSQL"
    }
  }
}
```

---

## Paso 4: Activar la Conexión MCP

1. Abre la **Paleta de Comandos** (`Ctrl+Shift+P`).
2. Escribe `MCP: Reload Servers` y presiona Enter.
3. Verifica en la barra de estado que los servers MCP estén conectados.
4. Los servers configurados ahora están disponibles para las extensiones de IA.

---

## Paso 5: Verificar la Conexión

Para verificar que la conexión funciona correctamente:

1. Abre la **Paleta de Comandos** (`Ctrl+Shift+P`).
2. Escribe `MCP: List Servers` para ver todos los servers configurados.
3. Busca tu server en la lista y verifica que el estado sea "connected".
4. Prueba una función del server desde la paleta de comandos.

---

## Paso 6: Usar MCP con Asistentes de IA

### Con GitHub Copilot:

1. Configura tu server MCP como se mostró anteriormente.
2. En VS Code, abre un archivo de código.
3. Usa `Ctrl+I` para abrir el chat de Copilot.
4. Menciona el server MCP en tu prompt, ej: `"Analiza este código usando el server de bases de datos"`.

### Con Extensiones de IA Personalizadas:

```javascript
// Ejemplo de cómo una extensión puede consumir MCP
const mcpClient = new MCPClient({
  serverUrl: 'ws://localhost:3000/mcp',
  auth: {
    type: 'bearer',
    token: 'tu-token-de-acceso'
  }
});

await mcpClient.connect();
const result = await mcpClient.callTool('nombre-herramienta', { param: 'valor' });
```

---

## Configuración Avanzada

### Variables de Entorno

Puedes usar variables de entorno en tu configuración:

```json
{
  "mcpServers": {
    "api-service": {
      "command": "node",
      "args": ["server.js"],
      "env": {
        "API_URL": "${env:API_URL}",
        "API_KEY": "${env:API_KEY}"
      }
    }
  }
}
```

### Múltiples Servers

Puedes configurar múltiples servers simultáneamente:

```json
{
  "mcpServers": {
    "files": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-filesystem", "/home/usuario/proyecto"]
    },
    "git": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-git"]
    },
    "memory": {
      "command": "npx",
      "args": ["-y", "@modelcontextprotocol/server-memory"]
    }
  }
}
```

---

## Solución de Problemas Comunes

### Problema 1: Server no aparece en la lista

**Solución:**

- Verifica que el archivo de configuración esté en la ubicación correcta.
- Reinicia VS Code completamente.
- Verifica que la extensión MCP esté instalada y activada.

### Problema 2: Error de conexión "Connection refused"

**Solución:**

- Verifica que el server MCP esté ejecutándose.
- Comprueba que el puerto configurado sea accesible.
- Revisa los logs del server para errores específicos.

### Problema 3: Problemas de permisos

**Solución:**

- Asegúrate de que VS Code tenga permisos para acceder a los recursos.
- En sistemas Unix, verifica los permisos de los directorios.
- En Windows, ejecuta VS Code como administrador si es necesario.

### Problema 4: Timeout de conexión

**Solución:**

```json
{
  "mcpServers": {
    "slow-server": {
      "command": "node",
      "args": ["server.js"],
      "timeout": 30000
    }
  }
}
```

---

## Recursos Adicionales

- **Documentación Oficial de MCP**: https://modelcontextprotocol.io
- **Repositorio GitHub**: https://github.com/modelcontextprotocol
- **Ejemplos de Servers**: https://github.com/modelcontextprotocol/servers
- **Comunidad Discord**: https://discord.gg/modelcontextprotocol

---

## Conclusión

Configurar una conexión MCP en Visual Studio Code te permite expandir las capacidades de tus asistentes de IA con acceso a datos externos, bases de datos, y servicios personalizados. La configuración es flexible y puede adaptarse a diferentes casos de uso, desde acceso a sistemas de archivo hasta conexiones con APIs complejas.

Recuerda mantener tus configuraciones seguras, especialmente al manejar credenciales y datos sensibles. Usa variables de entorno y evita hardcodear información confidencial en tus archivos de configuración.

---

*Última actualización: 30 de Abril, 2026*