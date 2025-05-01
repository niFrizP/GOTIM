---
name: Nuevo Issue
about: Plantilla para los nuevos issues
title: "## Título sugerido: Nueva funcionalidad - Descripción"
labels: mejora
assignees: ''

---

## 📝 Descripción

Describa brevemente qué se desea desarrollar y por qué es necesario.

> Ejemplo:  
> Desarrollar funciones para el control histórico del inventario, la generación de reportes y el control de accesos según rol.

---

## ✅ Tareas

Lista de subtareas necesarias para completar esta funcionalidad.

- [ ] Crear tabla de histórico de inventario con:
  - Fecha de movimiento
  - Tipo (entrada/salida/ajuste)
  - Producto y cantidad
  - Usuario que realizó el movimiento
  - Motivo
- [ ] Generar reportes exportables (PDF/Excel)
- [ ] Filtro por fecha, producto y categoría
- [ ] Control de roles:
  - Técnicos: solo pueden consumir productos
  - Supervisores: pueden aprobar/registrar movimientos
  - Admin: control total

---

## 📌 Notas

Añadir aquí cualquier consideración técnica o aclaración importante.

> Ejemplo:  
> - Utilizar gates o policies de Laravel para validar roles  
> - Visualización de movimientos por producto

---

## 🎯 Objetivo

¿Qué problema resuelve o qué mejora aporta?

> Ejemplo:  
> Permitir trazabilidad total de productos en inventario, garantizando integridad y control por permisos.
