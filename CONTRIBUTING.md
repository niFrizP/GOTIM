# Guía de Contribución – Proyecto GOTIM

Este documento establece las reglas y buenas prácticas que deben seguir los miembros del equipo al contribuir al desarrollo de GOTIM.

---

## ✅ ¿Quién puede contribuir?

Solo miembros autorizados del equipo de desarrollo.  
**Contribuciones externas no están permitidas.**

---

## 🧠 Antes de empezar

1. **Leé el README.md** para entender el estado actual del proyecto.
2. Revisa los **issues activos y el tablero de tareas** (Kanban o similar).
3. Verifica que tu tarea esté asignada y alineada con el Sprint actual.

---

## 🌿 Branching (Ramificación)

- `main`: estable y listo para producción.
- `feature-nombre_corto`: para cada nueva funcionalidad.
- `fix-descripcion`: para correcciones de bugs.
- `hotfix-urgente`: solo si algo explota en producción.

**Ejemplo:**  
`feature-gestion_categorias`  
`fix-login_token_expirado`

---

## 🧪 Tests

- Si tu módulo lo requiere, agrega pruebas mínimas para validarlo.
- No rompas pruebas existentes (¡dale run a los tests antes de hacer push!).

---

## 🚀 Pull Requests

1. Asigna al menos a un compañero para revisión.
2. Agrega una descripción clara: qué hiciste, por qué, y si hay algo que revisar especial.
3. Espera aprobación antes de mergear.

---

## 🧼 Estilo de código

- Usamos [ESLint](https://eslint.org/) y/o formateador automático (como Prettier).
- Comenta funciones complejas.
- Nombres claros. Nada de `x1`, `data2`, o `finalFinalRevisado.js`.

---

## 🆘 ¿Dudas?

- Pregunta en el grupo interno antes de tomar decisiones arriesgadas.
- Coordina con el responsable del módulo si vas a hacer un cambio transversal.

---

¡Gracias por contribuir con calidad y criterio! 💪  
El código lo hacemos entre todos, pero mantenemos el estándar como si lo hiciera un robot suizo.


