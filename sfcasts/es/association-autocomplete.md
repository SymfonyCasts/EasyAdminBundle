El `AssociationField` crea unos elementos muy chulos `select`. Pero estos son
realmente elementos normales y aburridos de `select` con una interfaz de usuario elegante. *Todas* las opciones,
en este caso, todos los usuarios de la base de datos, se cargan en la página en segundo plano
para construir el `select`. Esto significa que si tienes incluso cien usuarios en tu
base de datos, esta página va a empezar a ralentizarse y, finalmente, a explotar.

## AssociationField::autoComplete()

Para arreglar esto, dirígete y llama a un método personalizado en el `AssociationField` llamado
 `->autocomplete()` :

[[[ code('2c55c950e3') ]]]

Sí, esto *es* tan bonito como parece. Refresca. Parece* lo mismo, pero cuando escribimos
en la barra de búsqueda... y abrimos las Herramientas de Red... ¡fíjate! Eso ha hecho una petición AJAX
¡AJAX! Así que en lugar de cargar *todas* las opciones en la página, aprovecha un
Punto final AJAX que gestiona el autocompletado. ¡Problema resuelto!

## Controlar los elementos de autocompletar con formatValue()

Y como puedes ver, utiliza el método `__toString()` en `User` para mostrar la
opción, que es lo mismo que hace en la página del índice en la columna "Preguntado por
columna "Preguntado por". Sin embargo, podemos *controlar esto. ¿Cómo? Ya lo sabemos: es nuestro viejo amigo
 `->formatValue()` . Como recordarás, toma una función de devolución de llamada como su
argumento: `static function()` con `$value` como *primer* argumento
y `Question` como segundo:

[[[ code('568e743513') ]]]

El argumento `$value` será el valor formateado que se va a imprimir en
la página. Y luego `Question` es el objeto actual `Question`. Con el tiempo, tendremos que
necesitaremos hacer este argumento `nullable` y explicaré *por qué* más adelante. Pero por ahora
imagina que siempre tenemos un `Question` con el que trabajar.

Dentro: `if (!$question->getAskedBy())` - si por alguna razón ese campo es `null`,
haremos `return null`. Si eso *es*, `return sprintf()` - con `%s`, `&nbsp;` para
un espacio, y luego `%s` dentro de los paréntesis. Para el primer comodín, pasa
 `$user->getEmail()` .

Oh, ¡vaya! En la sentencia if, quería decir si `!$user =`. Esto, de forma extravagante, asigna
la variable `$user` *y* comprueba si hay *un usuario preguntado* a la vez.
Termina el método `->getEmail()` y utiliza `$user->getQuestions()->count()` para el
segundo comodín:

[[[ code('69f7dbd321') ]]]

## HTML ESTÁ PERMITIDO en EasyAdmin

Ah, y sobre ese `&nbsp;`. He añadido esto, en parte, para mostrar el hecho de que cuando
se renderizan las cosas en EasyAdmin, *puedes* incluir HTML en la mayoría de las situaciones. Eso es
normalmente *no* cómo funcionan Symfony y Twig, pero como nunca vamos a configurar
EasyAdmin *basado* en las entradas del *usuario*... y todo esto es sólo para una interfaz de administración
de todos modos, EasyAdmin permite incrustar HTML en la mayoría de los lugares.

Bien, ¡vamos a comprobarlo! Recarga y... ¡boom! Obtenemos nuestro nuevo formato "Preguntado por"
en la página de índice.

La razón *real* por la que quería que hiciéramos esto era para señalar que el valor formateado
se utiliza en la página de índice *y* en la página de detalle... pero *no* se utiliza en el formulario.
El formulario *siempre* utiliza el método `__toString()` de su entidad.

## Controlar la consulta de autocompletar

Una de las cosas que *podemos* controlar para estos campos de asociación es la consulta que se
utiliza para los resultados. Ahora mismo, nuestro campo de autocompletar devuelve *cualquier* usuario en
la base de datos. Restrinjamos esto a sólo los usuarios *habilitados*.

¿Cómo? Una vez más, podemos llamar a un método personalizado en `AssociationField` llamado
 `->setQueryBuilder()` . Pásale un `function()` con un argumento `QueryBuilder $queryBuilder`
argumento:

[[[ código('44b01e8577') ]]]

Cuando EasyAdmin genera la lista de resultados, crea el constructor de consultas *para*
para nosotros, y luego podemos modificarlo. Por ejemplo, `$queryBuilder->andWhere()`. El único secreto
es que tienes que saber que el *alias* de la entidad en la consulta es siempre
 `entity` . Así que: `entity.enabled = :enabled`, y luego `->setParameter('enabled', true)`:

[[[ código('de61807928') ]]]

¡Eso es todo! No necesitamos *devolver* nada porque hemos *modificado* el
 `QueryBuilder` . ¡Así que vamos a ver si ha funcionado!

Bueno, no creo que *notemos* ninguna diferencia porque estoy bastante seguro de que todos los
usuario *está* habilitado. Pero observa esto. Cuando escriba... aquí está la petición AJAX.
Abre la barra de herramientas de depuración de la web... pasa por encima de la sección AJAX y haz clic para abrir
el perfilador.

Ahora estás viendo el perfilador de la llamada AJAX de autocompletar. Dirígete a la sección
Sección Doctrina para que podamos ver el aspecto de esa consulta. Aquí está. Haz clic en "Ver
consulta formateada". ¡Genial! Mira en cada campo para ver si coincide con el `%ti%`
valor *y* tiene el WHERE `enabled = ?` con un valor de 1... que viene de
aquí. ¡Superguay!

Siguiente: ¿podríamos utilizar un `AssociationField` para manejar una relación de *colección*?
¿Como editar la colección de *respuestas* relacionadas con un `Question`? ¡Totalmente! Pero
necesitaremos algunos trucos de Doctrina y formulario para ayudarnos.
