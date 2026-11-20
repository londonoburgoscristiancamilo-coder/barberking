# BarberKing — Manual de instalación y configuración del chatbot de WhatsApp

**Proyecto:** BarberKing  
**Entorno:** desarrollo local con Docker Compose  
**Sistema:** Laravel + PostgreSQL + Redis + Nginx + n8n + Evolution API  
**Última actualización:** 19 de noviembre de 2026

> **Alcance.** Este manual explica cómo instalar BarberKing desde un repositorio clonado y configurar **manualmente** n8n y Evolution API para activar el chatbot de WhatsApp. Laravel y sus tareas de preparación se ejecutan mediante el *entrypoint* existente del proyecto. El flujo de n8n, las Data Tables y las credenciales **no** se instalan automáticamente.
>
> **Importante:** el archivo JSON contiene el flujo, pero **no** incluye las filas de las Data Tables, los secretos de las credenciales ni la sesión de WhatsApp. Cada instalación nueva requiere configurar esos elementos.

---

## 1. Requisitos previos

Antes de comenzar, comprueba que el equipo tenga:

- **Git**, para clonar el repositorio.
- **Docker Desktop**, con Docker Engine y **Docker Compose** disponibles. En Windows, usar el motor Linux/WSL 2.
- Un editor de texto (por ejemplo, Visual Studio Code).
- Conexión a Internet para descargar imágenes y dependencias, al menos durante el primer arranque.
- Un teléfono con **WhatsApp Business** disponible para escanear el código QR de Evolution API.
- El archivo `.env` correctamente configurado a partir de `.env.example` y las claves que correspondan al entorno.

No es necesario instalar PHP, Composer, PostgreSQL, Node.js o n8n directamente en Windows si el `compose.yaml` del repositorio proporciona los contenedores correspondientes.

> Las versiones exactas de los servicios dependen del `compose.yaml`, los archivos Dockerfile y los archivos de dependencias incluidos en el repositorio. No cambies versiones de imágenes por cuenta propia durante la instalación.

## 2. Clonar el repositorio

Abre una terminal PowerShell en la carpeta en la que quieras descargar el proyecto:

```powershell
git clone <URL_DEL_REPOSITORIO>
cd BarberKing
```

Sustituye `<URL_DEL_REPOSITORIO>` por la dirección real del repositorio. Si la carpeta clonada tiene otro nombre, entra en esa carpeta.

### 2.1. Revisar la estructura mínima

Comprueba que existan estos archivos y directorios:

```text
BarberKing/
├── app/
├── database/
├── docker/
├── n8n_data/
│   └── flow/
│       └── BarberKing_Flow_n8n_WhatsApp.json
├── .env.example
├── compose.yaml
├── Dockerfile
└── manual_instalacion.md
```

La ubicación acordada para el flujo del chatbot es:

```text
n8n_data/flow/BarberKing_Flow_n8n_WhatsApp.json
```

**No confundas** ese JSON versionado con los datos persistentes internos de n8n. El JSON es una plantilla para importar el workflow, no una copia de seguridad completa de n8n.

## 3. Configurar variables de entorno

En la raíz del proyecto, crea tu archivo `.env` a partir de la plantilla:

```powershell
Copy-Item .env.example .env
```

Abre `.env` y configura los valores reales que requiere tu instalación. Como mínimo, revisa:

1. La conexión de **Laravel a PostgreSQL** (base de datos, usuario, contraseña y host de Docker).
2. El **APP_KEY** y las demás opciones de Laravel que el proyecto necesite.
3. La configuración de **Redis** si está habilitado.
4. La **API key del middleware de Laravel** que valida las peticiones del chatbot.
5. La **API key de Evolution API**, si tu `compose.yaml` la define mediante una variable.
6. Las variables específicas de n8n o Evolution API que ya estén declaradas en el `compose.yaml`.

Las variables tienen que llamarse **exactamente como aparecen en el repositorio**: los nombres pueden diferir entre proyectos. No uses variables inventadas ni cambies los nombres sin revisar el código y la configuración Docker.

> **Seguridad:** nunca subas `.env`, contraseñas, claves API, sesiones de WhatsApp ni exportaciones con datos reales a GitHub. El repositorio debe conservar solamente `.env.example` con valores de ejemplo y sin secretos.

Comprueba que Compose interprete el archivo:

```powershell
docker compose config --quiet
```

Este comando verifica la configuración, pero no garantiza que todas las credenciales sean correctas.

## 4. Construir e iniciar BarberKing

Con Docker Desktop abierto y desde la raíz del proyecto, ejecuta:

```powershell
docker compose up --build
```

Espera a que se descarguen las imágenes, se construyan los servicios y finalicen las tareas de inicio. Comprueba los contenedores:

```powershell
docker compose ps
```

Si necesitas revisar un servicio:

```powershell
docker compose logs --tail=100 app
docker compose logs --tail=100 webserver
docker compose logs --tail=100 db
docker compose logs --tail=100 n8n
docker compose logs --tail=100 evolution-api
```

> **Entrypoint existente.** BarberKing ya dispone de una rutina de arranque para preparar Laravel. No vuelvas a ejecutar manualmente seeders o migraciones sin revisar primero el estado de la base de datos; hacerlo innecesariamente podría duplicar registros o provocar conflictos.

### 4.1. Acceso a las aplicaciones

Con la configuración de puertos utilizada por BarberKing, las direcciones locales habituales son:

| Servicio | Dirección |
|---|---|
| BarberKing (Laravel/Nginx) | `http://localhost:8000` |
| n8n | `http://localhost:5678` |
| Evolution API | `http://localhost:8080` |
| Evolution Manager | `http://localhost:8080/manager` (si está habilitado en la versión instalada) |

Si un enlace no funciona, comprueba la sección `ports` del servicio correspondiente en `compose.yaml`. **Las direcciones anteriores aplican únicamente si los puertos se mantienen como en la configuración conocida de BarberKing.**

### 4.2. Comprobar Laravel

1. Visita `http://localhost:8000`.
2. Verifica que se visualice la aplicación y puedas iniciar sesión o registrarte según los datos disponibles.
3. Confirma que PostgreSQL y los servicios necesarios estén activos mediante `docker compose ps`.
4. Si existe un error de Laravel, revisa `docker compose logs --tail=100 app` y `docker compose logs --tail=100 webserver`.

## 5. Configurar n8n manualmente

1. Abre `http://localhost:5678`.
2. Si es la primera ejecución, completa el registro/configuración del usuario administrador que solicite n8n.
3. Ingresa a n8n y localiza tu proyecto de trabajo. **Crea la Data Table y las credenciales dentro del proyecto al que asociarás el workflow**, o asegúrate de que el workflow tenga acceso a ellas.
4. Antes de publicar el flujo, completa **las secciones 6, 7 y 8**.

> El flujo importado puede conservar referencias a IDs de la instancia en la que se exportó. En una instalación nueva es normal que haya que seleccionar nuevamente la tabla y las credenciales.

## 6. Crear la Data Table `barberking_conversaciones`

Esta tabla almacena el estado de las conversaciones del chatbot: menú actual, cliente, datos temporales de agendamiento y datos de gestión/cancelación de citas.

En n8n:

1. Abre el proyecto en el que vas a usar BarberKing.
2. Entra a **Data tables**.
3. Selecciona **Create Data table** y **From scratch**.
4. Introduce el nombre exacto:

   ```text
   barberking_conversaciones
   ```

5. Crea las columnas indicadas a continuación, **respetando mayúsculas, minúsculas y guiones bajos**.
6. La tabla puede comenzar **sin registros**; el flujo se encargará de crear y actualizar filas cuando lleguen los mensajes.

### 6.1. Columnas de la tabla

| N.º | Columna | Tipo recomendado | Observación |
|---:|---|---|---|
| 1 | `id` | Automático de n8n | Identificador de fila; **no duplicarlo** si n8n lo crea por defecto. |
| 2 | `cliente_id` | String | Identificador del contacto/conversación de WhatsApp. |
| 3 | `estado` | String | Estado actual del diálogo. |
| 4 | `nombre` | String | Nombre mostrado/almacenado. |
| 5 | `ultima_opcion` | String | Última opción del menú. |
| 6 | `servicio_id` | String | Servicio seleccionado. |
| 7 | `barbero_id` | String | Barbero seleccionado. |
| 8 | `fecha_consulta` | String | Fecha en formato de texto. |
| 9 | `cita_cliente_id` | String | ID de cliente durante el agendamiento. |
| 10 | `cita_documento` | String | Documento aportado al agendar. |
| 11 | `cita_nombres` | String | Nombres del cliente. |
| 12 | `cita_apellidos` | String | Apellidos del cliente. |
| 13 | `cita_telefono` | String | Celular indicado en el registro. |
| 14 | `cita_opciones` | String | Opciones serializadas como texto/JSON. |
| 15 | `cita_hora` | String | Hora elegida. |
| 16 | `cita_servicio_nombre` | String | Nombre del servicio. |
| 17 | `cita_barbero_nombre` | String | Nombre del barbero. |
| 18 | `gestion_documento` | String | Documento para gestionar citas. |
| 19 | `gestion_cliente_id` | String | ID validado para gestionar citas. |
| 20 | `gestion_citas` | String | Lista de citas serializada como texto/JSON. |
| 21 | `gestion_cita_id` | String | Cita seleccionada para cancelación. |
| 22 | `gestion_telefono` | String | Celular usado en la verificación de identidad. |
| 23 | `createdAt` | Automático de n8n | Fecha de creación; **no duplicarla** si n8n la crea por defecto. |
| 24 | `updatedAt` | Automático de n8n | Fecha de actualización; **no duplicarla** si n8n la crea por defecto. |

**Importante:** en las versiones actuales de n8n, las tablas pueden incorporar automáticamente `id`, `createdAt` y `updatedAt`. Si esos campos ya existen, **no crees otros con el mismo nombre**. Para los 21 campos de negocio, usa **String**: incluso los IDs y fechas se guardan como texto en el estado conversacional de este workflow.

### 6.2. Verificación

Comprueba que:

- La tabla se llame exactamente `barberking_conversaciones`.
- Estén presentes todas las columnas de negocio indicadas.
- No haya columnas duplicadas para `id`, `createdAt` ni `updatedAt`.
- La tabla esté disponible en el mismo proyecto que el workflow o sea accesible desde él.

## 7. Crear las dos credenciales de n8n

El chatbot necesita **dos credenciales HTTP Header Auth** distintas. No uses el tipo *Bearer Auth* para estos dos casos: el nombre del encabezado debe ser exactamente el configurado aquí.

En n8n, abre **Credentials** → **Add credential** → **Header Auth** (puede aparecer como **HTTP Header Auth**, según la interfaz).

### 7.1. Credencial de Evolution API

**Nombre exacto de la credencial:**

```text
BarberKing - Evolution API
```

**Tipo:** HTTP Header Auth.

| Campo | Valor |
|---|---|
| Name | `apikey` |
| Value | `poner_la_api_key_de_evolution` |

Reemplaza el texto de ejemplo por la **clave API real de Evolution API** que corresponde a tu instalación. Guarda la credencial.

### 7.2. Credencial de Laravel Bot API

**Nombre exacto de la credencial:**

```text
BarberKing - Laravel Bot API
```

**Tipo:** HTTP Header Auth.

| Campo | Valor |
|---|---|
| Name | `X-Bot-Api-Key` |
| Value | `poner_api_key_middleware_laravel_contraseña_en_.env` |

Reemplaza el texto de ejemplo por **el mismo secreto** que usa el middleware de Laravel para autenticar las solicitudes del bot y que está configurado en `.env`. Guarda la credencial.

> **No intercambies las claves:** la de Evolution autentica los envíos de mensajes a WhatsApp y la de Laravel protege las rutas internas de la API del bot. Son propósitos diferentes.

### 7.3. Asociar las credenciales al workflow

Después de importar el JSON, inspecciona los nodos **HTTP Request**:

- Los nodos que llaman a `http://evolution-api:8080/...` deben utilizar **BarberKing - Evolution API**.
- Los nodos que llaman a `http://webserver/api/bot/...` deben utilizar **BarberKing - Laravel Bot API**.

En cada nodo, revisa **Authentication → Generic Credential Type → Header Auth** y selecciona la credencial correspondiente. Comprueba especialmente los nodos de envío de mensajes, consulta de servicios, horarios, clientes, agendamiento y gestión de citas.

> **Atención:** crear credenciales con el mismo nombre **no garantiza** que el JSON las conecte automáticamente. Si los nodos muestran credenciales inexistentes o desconectadas, selecciónalas manualmente.

## 8. Importar y configurar el workflow de BarberKing

### 8.1. Importar archivo JSON

1. Abre n8n: `http://localhost:5678`.
2. Crea un workflow nuevo dentro del proyecto elegido.
3. En el menú de opciones del editor (tres puntos), selecciona **Import from File**.
4. Navega a la ruta del repositorio:

   ```text
   n8n_data/flow/BarberKing_Flow_n8n_WhatsApp.json
   ```

5. Confirma la importación. El workflow debe aparecer con el nombre **BarberKing Flow** (o el nombre incluido en el JSON).
6. Guarda el workflow cuando sea necesario y **no lo publiques todavía**.

### 8.2. Conectar todos los nodos Data Table

Revisa los nodos de tipo **Data Table** dentro del workflow (buscar conversaciones, registrar, actualizar y restablecer estados, etc.).

En **cada nodo Data Table**:

1. Localiza el campo **Data table**.
2. Si aparece una tabla inexistente, un identificador antiguo o un error de referencia, selecciona la tabla nueva **`barberking_conversaciones`** desde el selector.
3. Conserva la operación prevista del nodo: *Get*, *Insert*, *Update*, etc.; **no modifiques las condiciones ni el mapeo de campos** salvo que la interfaz indique que están desactualizados.
4. Verifica que las columnas referenciadas existan en la tabla.
5. Guarda los cambios.

**Este paso es indispensable.** La tabla nueva tendrá su propio ID interno de n8n, posiblemente diferente del ID del entorno que exportó el flujo.

### 8.3. Revisar las URLs internas

Los contenedores se comunican mediante la red de Docker, **no** mediante `localhost` entre sí. En el workflow deben conservarse, cuando corresponda, las siguientes rutas internas:

```text
http://webserver/api/bot/...
http://evolution-api:8080/...
```

Comprueba que los nombres de servicio `webserver` y `evolution-api` coincidan exactamente con los definidos en `compose.yaml`, y que los contenedores compartan una red Docker.

> `http://localhost:8000` y `http://localhost:8080` son direcciones para acceder desde el **navegador del equipo**. Desde un contenedor, `localhost` apunta al propio contenedor y normalmente no sirve para comunicarse con otro servicio.

### 8.4. Revisar las referencias entre nodos

Mantén intactos los nombres y conexiones que vienen en el JSON: los nodos **Code**, **Edit Fields**, **If**, **Switch** y **HTTP Request** pueden referenciar otros nodos por nombre.

Comprueba que el workflow no muestre errores de configuración; arregla las credenciales y las Data Tables antes de iniciar las pruebas.

## 9. Configurar Evolution API y vincular WhatsApp Business

### 9.1. Ingresar a Evolution Manager

1. Abre `http://localhost:8080/manager` si está disponible.
2. Si la interfaz solicita autenticación, utiliza la API key que hayas definido para Evolution API.
3. Comprueba que el servicio responda. Si no abre, revisa:

   ```powershell
   docker compose logs --tail=100 evolution-api
   ```

### 9.2. Crear la instancia

1. Desde Evolution Manager, crea una instancia llamada **`BarberKing`**.
2. Usa el proveedor/integración de WhatsApp Web que permita la versión de Evolution API incluida en el proyecto.
3. Configura la instancia con las opciones requeridas por esa versión.
4. Confirma que el nombre sea el esperado por el workflow.

**Importante:** en el JSON de BarberKing, los nodos de envío utilizan una ruta como:

```text
http://evolution-api:8080/message/sendText/BarberKing
```

El nombre de la instancia y el segmento final de esa URL **deben coincidir**, respetando la capitalización donde aplique. Si la instancia se crea con otro nombre, tendrás que actualizar **todas** las referencias correspondientes del workflow.

### 9.3. Conectar el número de WhatsApp

1. En Evolution Manager, abre la instancia **BarberKing**.
2. Selecciona la opción para conectar WhatsApp y visualizar el código QR.
3. Desde el teléfono con WhatsApp Business, entra a **Dispositivos vinculados → Vincular dispositivo**.
4. Escanea el código QR.
5. Espera a que Evolution confirme que la sesión está conectada.

**El escaneo del QR es manual y obligatorio** en una instalación nueva sin una sesión válida previamente guardada.

### 9.4. Configurar el webhook hacia n8n

Evolution debe notificar a n8n cuando llegue un mensaje del cliente:

1. Abre en n8n el nodo **`Webhook - Evolution API Reenvía Todos los Mensajes que Llegan`**.
2. Copia su **Production URL** (URL de producción), no la URL temporal de pruebas.
3. Si n8n está configurado dentro de la misma red Docker que Evolution, utiliza como destino interno la ruta equivalente con el host **`n8n`** y puerto **`5678`**, conservando **exactamente el path del webhook** que muestra n8n:

   ```text
   http://n8n:5678/webhook/<RUTA_REAL_DEL_WEBHOOK>
   ```

4. En Evolution Manager, localiza las opciones de **Webhook** de la instancia **BarberKing**.
5. Activa el webhook, introduce la URL y selecciona el evento que recibe mensajes entrantes (habitualmente `MESSAGES_UPSERT`, según la versión de Evolution API).
6. Guarda la configuración y confirma que Evolution envíe eventos a n8n.

> **No copies literalmente** `<RUTA_REAL_DEL_WEBHOOK>`. Debes reemplazarlo por el path concreto del nodo Webhook importado. Si el nodo usa una ruta distinta, conserva la que figure en él. La configuración visual y el nombre del evento pueden variar por versión de Evolution API.

### 9.5. Publicar el workflow

1. Regresa a n8n.
2. Verifica que **todas** las credenciales HTTP y referencias a Data Tables estén resueltas.
3. Guarda los cambios.
4. Pulsa **Publish** para publicar la versión lista para producción.
5. Asegúrate de que el workflow quede disponible para recibir eventos de la **Production URL**.

## 10. Pruebas de funcionamiento del chatbot

Con n8n publicado y WhatsApp vinculado, envía mensajes desde **otro número de WhatsApp** al número de BarberKing.

| Prueba | Acción | Resultado esperado |
|---|---|---|
| 1. Inicio | Enviar un saludo o mensaje inicial | El chatbot recibe el evento y presenta bienvenida/menú. |
| 2. Servicios | Seleccionar la opción de servicios del menú | Lista de servicios reales proporcionados por Laravel. |
| 3. Agendamiento | Seleccionar `1` | Solicita documento y continúa el registro/agenda. |
| 4. Documento nuevo | Ingresar un documento no registrado al agendar | Ofrece registrar al cliente. |
| 5. Celular inválido | Introducir `00000000` durante registro | Rechaza el valor: debe comenzar por `3` y tener exactamente 10 dígitos. |
| 6. Celular válido | Introducir un celular de prueba con formato `3XXXXXXXXX` | Permite continuar con el registro (si Laravel acepta el resto de datos). |
| 7. Disponibilidad | Consultar barbero, servicio y fecha | Muestra horarios disponibles según Laravel. |
| 8. Crear cita | Confirmar una cita con datos válidos | Guarda la cita y confirma por WhatsApp. |
| 9. Gestionar citas | Seleccionar `4` | Solicita el documento de identidad. |
| 10. Documento inexistente | Ingresar un documento que no existe al gestionar citas | Informa que no está registrado y **no** solicita celular. |
| 11. Verificar identidad | Ingresar documento existente y después el celular correcto | Permite consultar las citas del cliente. |
| 12. Identidad incorrecta | Ingresar un celular distinto del registrado | Rechaza la verificación y no muestra las citas. |
| 13. Cancelar cita | Seleccionar una cita futura cancelable y confirmar | Laravel actualiza el estado y el bot confirma la cancelación. |
| 14. Cancelar navegación | Enviar `0` cuando corresponda | Regresa al menú sin completar la operación. |

> Utiliza documentos y números **de prueba** y evita crear citas reales involuntariamente. Un formato válido no equivale a verificar que un número pertenezca a una persona.

### 10.1. Si no llegan mensajes a n8n

Comprueba, en este orden:

1. La sesión de la instancia **BarberKing** está conectada en Evolution.
2. El webhook de Evolution está habilitado y apunta a la URL interna correcta de n8n.
3. El workflow de n8n está **publicado**, no solamente guardado.
4. En la pestaña **Executions** de n8n aparece una ejecución al enviar un mensaje.
5. Los nodos de envío a Evolution utilizan la credencial **BarberKing - Evolution API**.
6. Los nodos de consulta a Laravel utilizan la credencial **BarberKing - Laravel Bot API**.
7. Todos los nodos Data Table utilizan la tabla nueva `barberking_conversaciones`.
8. Los contenedores `n8n`, `evolution-api` y `webserver` siguen activos.

Para ver registros:

```powershell
docker compose logs --tail=100 n8n
docker compose logs --tail=100 evolution-api
docker compose logs --tail=100 app
```

## 11. Comandos útiles de mantenimiento

Desde la raíz del repositorio:

```powershell
# Iniciar o reconstruir el sistema
docker compose up --build

# Ver estado de los servicios
docker compose ps

# Ver logs de todos los servicios
docker compose logs --tail=100

# Detener servicios sin eliminar volúmenes
docker compose down

# Reiniciar un servicio concreto
docker compose restart n8n
```

**Precaución:** no uses `docker compose down -v` salvo que quieras eliminar deliberadamente los volúmenes. Podrías perder bases de datos, Data Tables, credenciales y sesiones persistentes. Tampoco borres los datos persistentes de Evolution si deseas conservar el vínculo con WhatsApp.

## 12. Lista de verificación final

Antes de declarar que la instalación está terminada:

- [ ] El repositorio se clonó correctamente.
- [ ] El archivo `.env` está creado y sus claves corresponden al entorno.
- [ ] `docker compose up --build` termina sin errores.
- [ ] Laravel responde en el navegador.
- [ ] PostgreSQL y los demás contenedores necesarios están activos.
- [ ] n8n está accesible y tiene usuario administrador configurado.
- [ ] Existe la Data Table `barberking_conversaciones` con sus 24 columnas, contando las tres automáticas.
- [ ] Se creó **BarberKing - Evolution API** con `apikey`.
- [ ] Se creó **BarberKing - Laravel Bot API** con `X-Bot-Api-Key`.
- [ ] Se importó `n8n_data/flow/BarberKing_Flow_n8n_WhatsApp.json`.
- [ ] Todos los nodos Data Table apuntan a la tabla de esta instalación.
- [ ] Todos los nodos HTTP Request usan sus credenciales correspondientes.
- [ ] Se creó la instancia **BarberKing** en Evolution API.
- [ ] WhatsApp Business se vinculó mediante QR.
- [ ] El webhook de Evolution está dirigido a la **Production URL** correcta de n8n.
- [ ] El workflow está guardado y **publicado**.
- [ ] El chatbot recibe y responde mensajes.
- [ ] Se comprobó la consulta de servicios, el agendamiento y la gestión de citas.

---

## 13. Referencias técnicas

- [Documentación oficial de n8n — Data Tables](https://docs.n8n.io/data/data-tables/)
- [Documentación oficial de n8n — Importar y exportar workflows](https://docs.n8n.io/workflows/export-import/)
- [Documentación oficial de n8n — Credenciales HTTP Request](https://docs.n8n.io/integrations/builtin/credentials/httprequest/)
- [Documentación oficial de Docker Compose](https://docs.docker.com/compose/)

---

**BarberKing — Sistema de gestión de barbería con chatbot de WhatsApp.**  
**Nota para desarrolladores:** Laravel se prepara desde el *entrypoint* del proyecto. La configuración inicial de **n8n**, **Data Tables**, **credenciales**, **Evolution API**, **webhook** y **vinculación del QR** sigue siendo **manual** conforme a este documento.
