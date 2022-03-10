Hay otro `AssociationField` que quiero incluir dentro de esta sección CRUD
y es interesante: `$answers`. A diferencia de `$topic` y `$answeredBy`, éste es un
 `Collection` : cada `Question` tiene *muchas* respuestas:

[[[ code('9d004493f1') ]]]

Volviendo a `QuestionCrudController`, `yield AssociationField::new('answers')`:

[[[ code('b1fb480333') ]]]

Y.... ¡vamos a ver qué pasa! Vuelve a la página del índice y... ¡Impresionante!
Reconoce que es una Colección e imprime el número de respuestas que tiene cada
 `Question` tiene... lo cual es bastante bonito. Y si vamos al formulario, realmente me está
¡me empieza a gustar este error! El formulario está, una vez más, intentando obtener una cadena
de la entidad.

## Configuración de la opción de campo choice_label

Ya sabemos cómo arreglar esto: dirígete a `Answer.php` y añade el método `__toString()`
método. *Pero*, en realidad hay una *otra* manera de manejar esto. Si estás familiarizado con
con el componente Symfony Form, este problema de convertir tu entidad en una
cadena de caracteres es algo que se ve siempre con el `EntityType`. Las dos formas de
resolverlo son añadir el método `__toString()` a tu entidad, *o* pasar a tu
campo una opción `choice_label`. Aquí podemos hacerlo gracias al método
 método`->setFormTypeOption()`.

Antes de rellenarlo, abre la clase `AssociationField`... y desplázate hasta
 `new` . Entre bastidores, esto utiliza el `EntityType` para el formulario. Así que cualquier opción que tenga
 que tenga`EntityType`, la tenemos *nosotros*. Por ejemplo, podemos establecer `choice_label`, que acepta
una devolución de llamada o simplemente la propiedad de la entidad que debe utilizar. Probemos con `id`:

[[[ código('5343658e4b') ]]]

Y ahora... ¡qué bonito! El ID no está súper claro, pero *podemos* ver que funciona.

## by_reference => false

Vamos a... ¡intentar eliminar una pregunta! Elimina "95", dale a "Guardar y continuar editando"
y... eh. ¿No ha pasado absolutamente nada? ¡La respuesta id "95" sigue ahí!

Si estás familiarizado con las colecciones y el componente Symfony Form, puede que
conocer la solución. Dirígete y configura otra opción de tipo de formulario llamada `by_reference`
configurada en `false`:

[[[ code('9a6a5125b6') ]]]

No entraré en demasiados detalles, pero básicamente, al configurar `by_reference` a `false`,
si se *elimina* una respuesta de esta pregunta, obligará al sistema a llamar
el método `removeAnswer()` que tengo en `Question`:

[[[ code('271644ce49') ]]]

Ese método elimina correctamente el `Answer` de `Question`. Pero lo más importante es que
establece `$answer->setQuestion()` en `null`, que es el lado *propietario* de esta
de esta relación... para los frikis de Doctrine.

## orphanRemoval

Bien, intenta eliminar "95" de nuevo y guardar. Oye, ¡hemos actualizado a un error!

> Se ha producido una excepción ... Violación de nulo: ... valor nulo en
> columna `question_id` de la relación `answer`...

¿Qué ha pasado? Vuelve a abrir `Question.php`. Cuando eliminamos una respuesta de `Question`,
nuestro método establece la propiedad `question` del objeto `Answer` en `null`. Esto hace que
que `Answer` sea un _huérfano_: es un `Answer` que ya no está relacionado con *ningún* `Question`.

Sin embargo, dentro de `Answer`, tenemos un código que impide que esto ocurra
ocurra: `nullable: false`:

[[[ code('bce7516389') ]]]

Si alguna vez intentamos guardar una Respuesta sin una Pregunta, nuestra base de datos nos detendrá.

Así que tenemos que decidir qué debe ocurrir cuando una respuesta queda "huérfana". En algunas
aplicaciones, puede que las respuestas huérfanas estén bien. En ese caso, cambia a `nullable: true`
y deja que se guarde. Pero en *nuestro* caso, si una respuesta es eliminada de su pregunta
debe ser *borrada*.

En Doctrine, hay una forma de forzar esto y decir

> Si un `Answer` se queda huérfano, por favor, bórralo.

Se llama "eliminación de huérfanos". Dentro de `Question`, desplázate hacia arriba para encontrar la `$answers`
propiedad... aquí está. Al final, añade `orphanRemoval` ajustado a `true`:

[[[ code('c2075f64ee') ]]]

Ahora actualiza y... ¡sí! ¡Ha funcionado! ¡El "95" ha desaparecido! Y si miraras en la base de datos
no existiría ninguna respuesta con "ID 95". ¡Problema resuelto!

## Personalización del campo de asociación

El último problema con esta área de respuestas es el *mismo* que tenemos con las otras
respuestas. Si tenemos muchas respuestas en la base de datos, se van a cargar *todas* en
la página para mostrar el `select`. Eso no va a funcionar, así que vamos a añadir
 `->autocomplete()` :

[[[ code('87d0951785') ]]]

Cuando actualicemos, ¡oh!

> Error al resolver `CrudAutocompleteType`: La opción `choice_label` no existe.

Ahhh. Cuando llamamos a `->autocomplete()`, esto *cambia* el tipo de formulario detrás de
 `AssociationField` . ¡Y *ese* tipo de formulario *no* tiene una opción `choice_label`!
En su lugar, *siempre* se basa en el método `__toString()` de la entidad para mostrar
las opciones, sea cual sea.

No es un gran problema. Elimina esa opción:

[[[ code('a0823e94fa') ]]]

Probablemente puedas adivinar lo que ocurrirá si refrescamos. Sí *Ahora* dice:

> ¡Hey Ryan! ¡Ve a añadir ese método `__toString()`!

De acuerdo, ¡está bien! En `Answer`, en cualquier lugar de aquí, añade `public function __toString(): string`...
y `return $this->getId()`:

[[[ code('a23bf9e106') ]]]

Ahora... ¡hemos vuelto! Y si escribimos... bueno... la búsqueda no es *grandiosa* porque
son sólo números, pero te haces una idea. Dale a guardar y... ¡bien!

A continuación, vamos a adentrarnos en el potente sistema de Configuradores de Campos, donde puedes modificar
algo sobre *todos* los campos del sistema desde un solo lugar. También es clave para
entender cómo funciona el núcleo de EasyAdmin.
