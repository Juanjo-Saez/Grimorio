# Diagramas - Grimorio

## 1. Diagrama de Gantt (03/03/26 - 20/05/26)

```mermaid
gantt
    title Grimorio - Cronograma del Proyecto (Orden Lógico)
    dateFormat YYYY-MM-DD
    
    section FASE 1: MVP Base
    Análisis y Diseño :done, phase1_1, 2026-03-03, 15d
    Arquitectura y BD :done, phase1_2, 2026-03-18, 10d
    CRUD Notas (Crear/Ver/Editar) :done, phase1_3, 2026-03-28, 12d
    Búsqueda con AND/OR :done, phase1_4, 2026-04-09, 8d
    Tags y Filtros :done, phase1_5, 2026-04-17, 5d
    
    section FASE 2: Autenticación
    Diseño Sistema Auth :done, phase2_1, 2026-04-22, 5d
    Login y Registro :done, phase2_2, 2026-04-27, 10d
    Sesiones y Middleware :done, phase2_3, 2026-05-07, 5d
    
    section FASE 3: Shared Links
    Diseño Links Compartidos :done, phase3_1, 2026-05-12, 3d
    Backend (Token + BD) :done, phase3_2, 2026-05-15, 5d
    Vista Pública sin Login :done, phase3_3, 2026-05-20, 2d
    
    section TESTING
    Tests Unitarios (MVP) :done, test1, 2026-04-22, 8d
    E2E Tests (Cypress) :done, test2, 2026-05-17, 4d
    
    section INFRA & DEPLOY
    Oracle Cloud Servidor :done, infra1, 2026-04-15, 25d
    Nginx + PHP + MySQL :done, infra2, 2026-05-01, 20d
    GitHub Actions (Deploy) :done, infra3, 2026-05-08, 8d
    
    section ENTREGA
    Documentación Final :crit, doc1, 2026-05-18, 3d
    Revisión y Cleanup :crit, doc2, 2026-05-20, 1d
    Entrega PFC :crit, milestone, 2026-05-21, 1d
```

**Explicación de tareas:**

| Fase | Tarea | Qué Significa |
|------|-------|---------------|
| **MVP** | Análisis y Diseño | Planificación, diseño de BD, requisitos |
| | CRUD Notas | Crear, leer, editar, eliminar notas |
| | Búsqueda con AND/OR | Buscar "Laravel AND deployment" |
| | Tags y Filtros | Sistema de etiquetado de notas |
| | Tests Unitarios | Pruebas de funciones individuales |
| **Auth** | Autenticación y Login | Registro y login de usuarios |
| | Sistema de Sesiones | Mantener usuarios logueados |
| **Shared Links** | Diseño Links Compartidos | Planificación del sistema de compartición |
| | Backend (Token + BD) | Crear tokens únicos, guardar en BD |
| | Vista Pública sin Login | Permitir ver notas compartidas sin estar registrado |
| | E2E Tests | Pruebas completas (crear → compartir → ver) |
| **Infra** | Oracle Cloud Servidor | Máquina virtual en la nube |
| | Nginx + PHP + MySQL | Instalar servidores web y BD |
| | GitHub Actions | Despliegue automático en cada push |
| **Entrega** | Documentación Final | Memoria, diagramas, guías |
| | Revisión y Cleanup | Revisar código, limpiar logs |
| | Entrega PFC | Fecha final de presentación |

**Cómo verlo:** Copia el código anterior y pégalo en https://mermaid.live/

---

## 2. Diagrama Entidad-Relación

```mermaid
erDiagram
    USERS ||--o{ NOTES : "creates"
    USERS ||--o{ SHARED_LINKS : "owns"
    USERS ||--o{ SHARED_LINKS : "receives"
    NOTES ||--o{ SHARED_LINKS : "shared"
    NOTES ||--o{ NOTE_TAG : "has"
    TAGS ||--o{ NOTE_TAG : "has"
    
    USERS {
        int id PK
        string email UK
        string password
        string name
        string remember_token
        timestamp created_at
    }
    
    NOTES {
        int id PK
        int user_id FK
        string title
        text description
        text content
        timestamp created_at
        timestamp updated_at
    }
    
    TAGS {
        int id PK
        int user_id FK
        string name
        timestamp created_at
    }
    
    NOTE_TAG {
        int id PK
        int note_id FK
        int tag_id FK
        timestamp created_at
    }
    
    SHARED_LINKS {
        int id PK
        int note_id FK
        int owner_id FK
        int recipient_id FK "nullable"
        string recipient_email "nullable"
        string token UK "↓ generar URL"
        enum access_level "read, edit"
        timestamp created_at
    }
```

**Nota sobre SharedLinks y URLs:**

El URL compartido **NO se almacena en la BD**. En su lugar, almacenamos el `token` (una cadena única de 64 caracteres hexadecimales). El URL se construye dinámicamente en la aplicación:

```
URL compartido = http://51.170.49.16/shared/{token}

Ejemplo:
- token en BD: "a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0"
- URL generado: "http://51.170.49.16/shared/a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0"
```

**Ventajas de esta arquitectura:**
- El URL es único porque el `token` es único (clave única UK)
- El token es criptográficamente seguro (imposible adivinar)
- Se puede revocar el acceso simplemente eliminando la fila de `shared_links`
- No hay rutas "amigables" (no se necesitan mapeos extra en la BD)
- Un mismo token = un único link compartido

---

## 3. Diagrama de Clases

```mermaid
classDiagram
    class User {
        -int id
        -string email
        -string password
        -string name
        +getFullName() string
        +hasNotes() boolean
    }
    
    class Note {
        -int id
        -int user_id
        -string title
        -string description
        -text content
        -timestamp created_at
        +getShortContent() string
        +hasAccess(User) boolean
    }
    
    class Tag {
        -int id
        -int user_id
        -string name
        +getNotes() Collection
    }
    
    class SharedLink {
        -int id
        -int note_id
        -int owner_id
        -int recipient_id
        -string recipient_email
        -string token
        -enum access_level
        +isPublic() boolean
        +canEdit() boolean
        +revoke() void
    }
    
    class NoteService {
        +create(User, data) Note
        +update(User, Note, data) Note
        +delete(User, Note) void
        +search(User, query) Collection
        +getByTag(User, Tag) Collection
    }
    
    class SearchService {
        -array operators
        +parse(query) ParsedQuery
        +execute(User, ParsedQuery) Collection
        -parseAnd(terms) Collection
        -parseOr(terms) Collection
    }
    
    class SharedLinkService {
        +createPublicLink(User, Note, level) SharedLink
        +createShare(User, Note, email, level) SharedLink
        +validateAccess(token, User) SharedLink
        +revokeShare(User, SharedLink) void
        +getSharedWithMe(User) Collection
    }
    
    class NoteController {
        -NoteService noteService
        +index() View
        +create() View
        +store(Request) Response
        +show(Note) View
        +edit(Note) View
        +update(Request, Note) Response
        +destroy(Note) Response
    }
    
    class SharedLinkController {
        -SharedLinkService service
        +store(Request, Note) Response
        +viewShared(token) View
        +destroy(SharedLink) Response
    }
    
    User "1" -- "*" Note : owns
    User "1" -- "*" SharedLink : owns as owner
    User "1" -- "*" SharedLink : receives as recipient
    Note "1" -- "*" SharedLink : shared via
    Note "*" -- "*" Tag : tagged with
    
    NoteController -- NoteService
    SharedLinkController -- SharedLinkService
    NoteService -- NoteRepository
    SearchService -- NoteRepository
```

**Explicación del Diagrama de Clases:**

Este diagrama muestra la arquitectura en capas de la aplicación, separando modelos, servicios y controladores:

Modelos (Entidades de BD):
- User: Representa un usuario del sistema. Tiene email, contraseña y nombre. Métodos para obtener datos del perfil.
- Note: Representa una nota. Cada nota tiene un propietario (user_id), título, descripción y contenido. El método `hasAccess()` verifica si un usuario puede acceder a esa nota.
- Tag: Etiqueta para categorizar notas. Un usuario puede tener múltiples tags, y cada tag tiene múltiples notas.
- SharedLink: Representa un link compartido. Conecta una nota con un usuario propietario (owner_id), un receptor opcional (recipient_id), o una dirección de email. Tiene un token único y nivel de acceso (read/edit).

Servicios (Lógica de Negocio):
- NoteService: Contiene toda la lógica de operaciones con notas (crear, actualizar, eliminar, buscar por tags). Los controladores delegan aquí, no acceden directo a la BD.
- SearchService: Especializado en búsquedas. Parsea operadores como "AND" y "OR", y convierte queries del usuario en búsquedas optimizadas en la BD.
- SharedLinkService: Maneja todo lo relacionado con compartición. Crea links públicos, valida acceso mediante token, revoca permisos, etc.

Controladores (Request/Response):
- NoteController: Maneja las rutas HTTP de notas (GET /notes, POST /notes, etc.). Recibe requests, delega a `NoteService`, retorna vistas o JSON.
- SharedLinkController: Maneja rutas de compartición (POST /notes/{id}/share, GET /shared/{token}, DELETE /shared/{id}). Similar a NoteController pero para links.

Relaciones entre clases:
- Los Controllers dependen de los Services (inyección de dependencia)
- Los Services acceden a la BD mediante un Repository (interfaz abstracta)
- Los Models representan tablas de BD y definen relaciones entre ellas
- Las relaciones entre models (User → Note → Tag → SharedLink) son las mismas que en el diagrama ER

Flujo típico:
1. Usuario accede a `/notes/5/edit` 
2. `NoteController::edit()` es llamado
3. Delega a `NoteService::findById(5)`
4. `NoteService` verifica permisos y accede a la BD
5. Retorna la vista con los datos de la nota

Este patrón mantiene el código limpio, testeable y reutilizable.

---

## 4. Diagrama de Secuencia - Compartir Link Público

```mermaid
sequenceDiagram
    actor User1 as Usuario A
    participant App as Grimorio App
    participant Ctrl as SharedLinkController
    participant Service as SharedLinkService
    participant DB as Database
    actor User2 as Usuario B
    
    User1->>App: Crea nota con titulo Mi Proyecto
    App->>DB: INSERT INTO notes
    DB-->>App: note_id = 5
    
    User1->>App: Click Compartir - Generar Link
    App->>Ctrl: POST /notes/5/share
    Ctrl->>Service: createPublicLink(User A, Note 5, read)
    Service->>DB: SELECT shared_links WHERE note_id=5
    alt Link ya existe
        DB-->>Service: Retorna link existente
        Service-->>Ctrl: Devuelve link con token
    else Link nuevo
        Service->>DB: INSERT INTO shared_links
        DB-->>Service: SharedLink creado
        Service-->>Ctrl: Devuelve nuevo link y token
    end
    
    Ctrl-->>App: JSON con share_link
    App-->>User1: Muestra URL en campo copiable
    
    User1->>User1: Copia link y lo comparte
    User1->>User2: Aqui esta el link compartido
    
    User2->>App: Abre link sin login
    App->>Ctrl: GET /shared/token123
    Ctrl->>Service: validateAccess(token123, null)
    Service->>DB: SELECT shared_links WHERE token
    DB-->>Service: SharedLink encontrado
    Service-->>Ctrl: Acceso permitido
    Ctrl->>DB: SELECT notes WHERE id=5
    DB-->>Ctrl: Retorna nota
    Ctrl-->>App: Vista HTML con nota
    App-->>User2: Muestra nota compartida
```

---

## 5. Diagrama de Secuencia - Busqueda con Operadores y Tags

```mermaid
sequenceDiagram
    actor User as Usuario
    participant App as Grimorio App
    participant Ctrl as NoteController
    participant SearchService as SearchService
    participant DB as Database
    
    User->>App: Busca Laravel AND deployment (con o sin tags)
    App->>Ctrl: GET /notes?search=Laravel+AND+deployment&tags=tech
    Ctrl->>SearchService: parse(Laravel AND deployment, tags=tech)
    
    alt Contiene AND
        SearchService->>SearchService: Detecta operador AND
        SearchService->>SearchService: terminos = Laravel, deployment
        SearchService-->>Ctrl: ParsedQuery tipo AND
    else Contiene OR
        SearchService->>SearchService: Detecta operador OR
        SearchService-->>Ctrl: ParsedQuery tipo OR
    end
    
    alt Tags seleccionados
        Ctrl->>DB: WHERE MATCH en titulo y contenido AND tags IN (tech)
        DB-->>Ctrl: Retorna notas coincidentes filtradas por tags
    else Sin tags (opcional)
        Ctrl->>DB: WHERE MATCH en titulo y contenido
        DB-->>Ctrl: Retorna notas coincidentes sin filtrar por tags
    end
    
    Ctrl->>App: Retorna notas encontradas
    App-->>User: Muestra resultados
```

**Explicación:**
- **Con AND (Todas las palabras):** Usuario escribe "Laravel AND deployment" → busca notas que contengan TODAS las palabras especificadas
- **Con OR (Cualquiera de las palabras):** Usuario escribe "Laravel OR Python" → busca notas que contengan CUALQUIERA de las palabras especificadas
- **Con Tags (Opcional):** Los tags actúan como filtro adicional → solo muestra notas que coincidan con el texto Y que tengan esos tags asignados. Si no selecciona tags, busca en todas las notas sin filtrar
- **Sin Tags:** Si el usuario no selecciona ningún tag, devuelve todas las notas que coincidan con el criterio de búsqueda (AND/OR)

---

## Cómo usar estos diagramas:

1. **Opción A - Visualizar online:**
   - Ve a https://mermaid.live/
   - Copia cualquier bloque de código (entre ```)
   - Pégalo en el editor
   - Haz screenshot para la memoria

2. **Opción B - Convertir a imagen:**
   - Desde mermaid.live, haz click en "Export"
   - Descarga como PNG o SVG
   - Inserta en Word

3. **Opción C - Usar extensión en VS Code:**
   - Instala "Markdown Preview Mermaid Support"
   - Abre este archivo en VS Code
   - Los diagramas se verán directamente

---

**Nota:** Los códigos están listos para usar en la memoria. Solo necesitas elegir cómo visualizarlos e insertarlos como imágenes.
