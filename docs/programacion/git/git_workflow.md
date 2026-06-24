# Flujo de Trabajo Git: Desarrollo Híbrido (Casa / Trabajo)

Este documento define el flujo de trabajo oficial para el desarrollo del proyecto entre diferentes ubicaciones físicas (Casa y Oficina), garantizando que el código esté siempre sincronizado, la rama principal sea estable y se eviten pérdidas de progreso.

## 📋 Principios Fundamentales
1. **La rama `main` es sagrada:** Nunca se hace commit directo en `main`. Solo contiene código 100% estable y probado.
2. **Sincronización diaria (WIP):** Al finalizar la jornada en una ubicación, el progreso se sube obligatoriamente al repositorio remoto, incluso si el código está a medias.
3. **Aislamiento de Entornos:** Los entornos virtuales de Python (`.venv`) **nunca** se suben al repositorio. La sincronización de dependencias se gestiona exclusivamente mediante `requirements.txt`.

---

## 🔄 Diagrama del Ciclo de Trabajo

```text
 [Oficina]                                          [Casa]
    │                                                 │
    ├─► (Crear rama feature/*)                         │
    ├─► (Escribir código)                             │
    └─► git push origin feature/* ──(Remoto)──►     ├─► git fetch & checkout
                                                      ├─► (Continuar código)
                                                      ├─► Terminar tarea
    ┌─► git pull origin main     ◄──(Remoto)──────────┘
    ├─► git merge feature/*
    └─► git push origin main