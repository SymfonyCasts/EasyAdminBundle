La interfaz de EasyAdmin tiene un aspecto bastante bueno desde el principio. Pero, ¿qué pasa si queremos
personalizar el aspecto de algo? Por ejemplo, si quiero cambiar el fondo
de la barra lateral. ¿Cómo podemos hacerlo?

Este tipo de cosas se pueden controlar a través del método `configureAssets()`. Como
recordatorio, este es uno de esos métodos que existe dentro de nuestro controlador
y* cada controlador CRUD individual. Así que podemos controlar los activos a nivel
nivel global *o* para una sola sección.

Hagamos nuestro cambio a nivel global para poder cambiar el color de la barra lateral en
cada página.

## Hola configureAssets()

En cualquier lugar dentro de `DashboardController`, vuelve al menú "Código"->"Generar..."
menú, selecciona "Anular métodos" y anula `configureAssets()`:

[[[ code('e1bb289ec7') ]]]

Esto tiene un montón de métodos geniales. Hay algunos sencillos como `->addCssFile()`.
Si dices `->addCssFile('foo.css')`, eso incluirá una etiqueta `link` a `/foo.css`.
Siempre que tengamos `foo.css` dentro de nuestro directorio `public/`, eso funcionaría.

Lo mismo ocurre con `->addJsFile()`. Y también puedes `->addHtmlContentToBody()`
o `->addHtmlContentToHead()`. ¡Hay *tonos* de métodos interesantes!

## Crear una entrada personalizada de Admin Encore

*Nuestra* aplicación utiliza Webpack Encore. Echa un vistazo al archivo `webpack.config.js`:
es bastante estándar. Sólo tenemos una entrada llamada `app`:

[[[ code('f0d21ea764') ]]]

Se encarga de cargar todo el JavaScript *y* el CSS:

[[[ code('ffebc503b7') ]]]

e incluimos esta entrada en nuestro *frontend* para que todo se vea y funcione bien.

Probablemente te hayas dado cuenta de que, en `configureAssets()`, hay un
`addWebpackEncoreEntry()` método. Si dijéramos aquí `app`, eso haría que se introdujera el
CSS y JavaScript de nuestra entrada `app`. *Pero*.... eso hace que las cosas parezcan un poco
loco... porque no queremos que *todos* los estilos del frontend y el JavaScript
aparezcan en la sección de administración. No, sólo queremos ser capaces de añadir *un poco*
de cosas nuevas.

Así que esto es lo que haremos en su lugar. Dentro del directorio `assets/styles/`, crea un
archivo completamente nuevo llamado `admin.css`. Este será nuestro CSS únicamente para estilizar
la sección de administración. Y sólo para ver si las cosas funcionan, añadiré un
fondo del cuerpo de "lightcyan":

[[[ code('ae11b3dd02') ]]]

¡Que bonito!

En `webpack.config.js`, añade una segunda entrada para *sólo* el administrador. Pero, en este momento
ahora, como sólo tenemos un archivo CSS (no necesitamos JavaScript), diré
`.addStyleEntry()`... y apuntaré a `./assets/styles/admin.css`. También debería
cambiar `app` por `admin`... pero lo haré en un momento:

[[[ code('68fbb7de7c') ]]]

Como acabamos de modificar nuestro archivo webpack, tenemos que ir a nuestro terminal
encontrar donde estamos ejecutando encore, pulsar `Ctrl`+`C`, y luego volver a ejecutarlo:

```terminal-silent
yarn watch
```

Y... ¡explotó! ¡Eso es por mi error! Tengo que dar un nombre único a mi entrada.
Cambia `app` por `admin`:

[[[ code('811663d0f2') ]]]

Vuelve a ejecutarlo, y... ¡qué bien!

Además del material original, puedes ver que también ha volcado un
`admin.css` archivo. Gracias a esto, en nuestro `DashboardController`, decimos
`->addWebpackEncoreEntry('admin')`:

[[[ code('9cb529831f') ]]]

Refrescar y... ¡funciona! Es una página... bueno... de aspecto interesante.

Si ves la fuente de la página, puedes ver cómo funciona. Realmente no hay nada
especial. El archivo `app.css` nos da todo el estilo de EasyAdmin que hemos estado
disfrutando... y luego *aquí* está nuestro nuevo archivo `admin.css`.

## Propiedades CSS

Llegados a este punto, ¡somos peligrosos! Podemos añadir cualquier CSS que queramos al nuevo
`admin.css` y anulará *cualquiera* de los estilos de EasyAdmin. ¡Genial! Pero
EasyAdmin lo hace aún más fácil

Inspecciona el elemento de la barra lateral. El objetivo es cambiar el fondo de la barra lateral.
Encuentra el elemento real con la clase `sidebar`. Si te fijas en los estilos
de la derecha... Lo haré un poco más grande... puedes ver que la clase
`.sidebar` tiene un estilo `background`. Pero en lugar de estar configurado con un color,
está fijado en esta cosa de `var(--sidebar-bg)`. Si pasas el ratón por encima, aparentemente, esto
es igual a `#f8fafc`.

Si no lo has visto antes, se trata de una propiedad CSS. No tiene nada que ver con
EasyAdmin o Symfony. En CSS, puedes crear variables (llamadas "propiedades CSS") y
hacer referencia a ellas en otro lugar. Al parecer, EasyAdmin ha creado una variable `--sidebar-bg`
variable y la está referenciando aquí. Así que, en lugar de intentar anular el
fondo de `.sidebar` -lo que *podríamos* hacer- podemos anular esta propiedad CSS
y tendrá el mismo efecto.

¿Cómo? Vamos a hacer un poco de trampa, profundizando en el propio EasyAdmin.

Abre `vendor/easycorp/easyadmin-bundle/assets/css/easyadmin-theme/`. En su interior
hay un archivo llamado `variables-theme.scss`. *Aquí* es donde se definen todas estas propiedades CSS
están definidas. Y hay *tonos* de cosas aquí, para tamaños de fuente
diferentes anchos, y... ¡ `--sidebar-bg`! Esta variable `--sidebar-bg`,
o propiedad, se establece aparentemente en *otra* variable a través de la sintaxis `var`. Encontrarás
encontrarás *esa* variable en otro archivo llamado `./color-palette.scss`... que está
aquí mismo. Estos son archivos SCSS, pero este sistema de propiedades CSS no tiene *nada* que ver
con Sass. Es una característica *pura* de CSS.

Hay mucho aquí, pero si sigues la lógica, `--sidebar-bg` se establece en
`--gray-50`... luego *toda* la parte inferior, `--gray-50` se ajusta a
`--blue-gray-50`... entonces *eso*... si seguimos buscando... ¡sí! Está configurado con el
¡color que esperábamos!

Esta es una buena manera de aprender qué son estos valores, cómo se relacionan
entre sí y cómo anularlos. Copia la sintaxis de `--sidebar-bg`.

La forma de definir las variables CSS suele ser bajo este pseudo-selector `:root`.
Vamos a hacer lo mismo.

En nuestro archivo CSS, quitamos el `body`, añadimos el `:root` y lo pegamos. Y aunque es
*totalmente* legal hacer referencia a las propiedades CSS desde aquí, vamos a sustituirlo por
un color hexadecimal normal:

[[[ code('8976440c6a') ]]]

¡Probemos! Observa bien la barra lateral... el cambio es sutil. Actualiza y...
¡ha cambiado! Para comprobarlo, si encuentras el `--sidebar-bg` en los estilos y pasas el ratón por encima...
esa propiedad *está* ahora ajustada a `#deebff`. Es sutil, pero *está* cargando el color correcto
¡color correcto!

Así que acabamos de personalizar los activos globalmente para toda nuestra sección de administración. Pero podríamos
*podríamos* anular `configureAssets()` en un controlador CRUD específico para hacer cambios
que *sólo* se apliquen a esa sección.

A continuación, vamos a empezar a profundizar en lo que posiblemente sea la parte *más* importante de
configurar EasyAdmin: Los campos. Estos controlan los campos que aparecen en la página de índice
así como en las páginas de formularios.
