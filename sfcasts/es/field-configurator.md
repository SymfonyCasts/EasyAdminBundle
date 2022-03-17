Vamos a terminar de configurar unos cuantos campos más y luego hablaremos más de un sistema
importante que funciona entre bastidores: los configuradores de campos.

## Desactivar un campo de formulario sólo en la edición

Otro campo que quiero mostrar en la sección de preguntas es "slug": `yield
Field::new('slug')`... y luego `->hideOnIndex()`:

[[[ code('05717465df') ]]]

Esto será sólo para los formularios.

Ahora, cuando vamos a Preguntas... no está ahí. Si editamos una pregunta, está
allí. Las babosas suelen ser autogeneradas... pero a veces es bueno
controlarlas. Sin embargo, una vez que se ha creado una pregunta y se ha establecido el slug, éste
nunca debería cambiar.

Por eso, en la página de edición, quiero desactivar este campo. Podríamos eliminarlo
por completo añadiendo `->onlyWhenCreating()`... pero pff. ¡Eso es demasiado fácil! Mostrémoslo
mostrarlo, pero deshabilitarlo.

¿Cómo? Ya sabemos que cada campo tiene un tipo de formulario detrás. Y cada tipo de formulario
en Symfony tiene una opción llamada `disabled`. Para controlar esto, podemos decir
`->setFormTypeOption()` y pasar `disabled`:

[[[ code('5748aa1ddb') ]]]

Pero no podemos poner esto en "true" en todas partes... ya que eso lo deshabilitaría
en la nueva página. Aquí es donde el argumento `$pageName` resulta útil Será
será una cadena como `index` o `edit` o `details`. Así que podemos establecer `disabled`
a `true` si `$pageName !==`... y usaré la clase `Crud` para referenciar su
`PAGE_NEW` constante:

[[[ code('c7a0905495') ]]]

¡Hagamos esto! Aquí en la página de edición... está desactivada. Y si volvemos a
Preguntas... y creamos una nueva pregunta... ¡tenemos un campo slug no desactivado!

Bien, ¡basta con la sección de preguntas! Cierra `QuestionCrudController` y abre
`AnswerCrudController`. Descomenta `configureFields()`... y luego pegaré
algunos campos. Sólo tengo que volver a escribir el final de estas clases y pulsar `Tab` para
autocompletarlas... para obtener las declaraciones que faltan en `use`:

[[[ code('bd0af0137e') ]]]

Perfecto No hay nada especial aquí. Tal vez quieras añadir autocompletar a
los campos `question` y `answeredBy`, pero lo dejo a tu elección.

Si actualizamos... ¡la página de Respuestas tiene un aspecto impresionante! Y si editamos una, obtenemos nuestro
error favorito:

> El objeto de la clase `Question` no se ha podido convertir en cadena

Esto viene del `AssociationField`. La solución es entrar en `Question.php`
y añadir `public function __toString(): string`... y `return $this->name`:

[[[ code('0d29bd69c6') ]]]

Y ahora... ¡esa página también funciona!

## Cambiar globalmente un campo

De vuelta a la página principal de Respuestas... a veces este texto puede ser demasiado largo para encajar
bien en la tabla. Vamos a truncarlo si supera una longitud determinada.
Hacer esto es... realmente fácil. Dirígete al campo `answer`, utiliza `TextField`...
y luego aprovecha un método personalizado `->setMaxLength()`:

```php
public function configureFields(string $pageName): iterable
{
    // ...
    yield TextField::new('answer')
        // ...
        ->setMaxLength(50);
}
```

Si lo ponemos a 50, ¡se truncará cualquier texto que supere los 50 caracteres!

Pero voy a deshacerlo. ¿Por qué? ¡Porque quiero que hagamos algo más interesante!

Ahora mismo, estoy utilizando `Field`, que le dice a EasyAdmin que adivine el mejor tipo de campo.
Esto se está imprimiendo como un textarea... así que su tipo de campo es realmente `TextareaField`...
y podemos utilizarlo si queremos.

## Más sobre los configuradores de campos

Este es el nuevo objetivo: quiero establecer una longitud máxima para cada `TextareaField` en
toda nuestra aplicación. ¿Cómo podemos cambiar el comportamiento de muchos campos a la vez
mismo tiempo? Con un configurador de campos.

Ya hemos hablado de ellos un poco antes. Desplázate hacia abajo: Ya tengo
`/vendor/easycorp/easyadmin-bundle/` abierto. Uno de los directorios se llama
llamado `Field/`... y tiene un subdirectorio llamado `Configurator/`. Después de que tu
campo se crea, se pasa por este sistema configurador. Cualquier configurador
puede entonces realizar cambios en cualquier campo. Hay dos configuradores "comunes".
`CommonPreConfigurator` el configurador común es llamado cuando se crea tu campo, y hace una serie de
diferentes cosas a tu campo, incluyendo la construcción de la etiqueta, establecer si
es obligatorio, hacer que se pueda ordenar, establecer la ruta de su plantilla, etc.

También hay un `CommonPostConfigurator`, que se ejecuta después de crear tu campo.

Pero la mayoría de estos configuradores son específicos para uno o pocos tipos de campo.
Y si alguna vez utilizas un campo y algo "mágico" ocurre entre bastidores
entre bastidores, es muy probable que proceda de uno de ellos. Por ejemplo, el campo
`AssociationConfigurator` es un poco complejo... pero configura todo tipo de cosas
para que ese campo funcione.

Conocerlos es importante porque es una buena manera de entender lo que ocurre
que sucede bajo el capó, como por ejemplo, por qué un campo se comporta de alguna manera o cómo puedes ampliarlo
de una manera o cómo puedes ampliarlo. ¡Pero también es genial porque podemos crear nuestro propio configurador de campos personalizado!

Vamos a hacerlo. Sube a `src/`... aquí vamos... crea un nuevo directorio llamado
`EasyAdmin/` y, dentro, una nueva clase PHP llamada... ¿qué tal
`TruncateLongTextConfigurator`. La única regla para estas clases es que deben
implementar un `FieldConfiguratorInterface`:

[[[ code('754625bbdf') ]]]

Ve a "Código"->"Generar" o `Cmd`+`N` en un Mac, y selecciona "Implementar métodos"
para implementar los dos que necesitamos:

[[[ code('dd79dc2a10') ]]]

Así es como funciona. Por cada campo que devolvamos en `configureFields()`
para cualquier sección CRUD, EasyAdmin llamará al método `supports()` de nuestra nueva clase
y básicamente preguntará

> ¿Quiere este configurador operar en este campo específico?

Esto suele ser `return $field->getFieldFqcn() ===` un tipo de campo específico. En
nuestro caso, vamos a dirigirnos a los campos de área de texto: `TextareaField::class`:

[[[ code('2553f266c3') ]]]

Si el campo que se está creando es un `TextareaField`, entonces sí queremos modificarlo
el campo. A continuación, si devolvemos `true` desde soportes, EasyAdmin llama a `configure()`.
Dentro, sólo por ahora, `dd()` la variable `$field`:

[[[ code('91a7ece907') ]]]

¡Veamos si se activa! Encuentra tu navegador. No importa a dónde vaya, así que
iré a la página índice. Y... ¡boom! ¡Se dispara! Este `FieldDto` está lleno de
información y llena de formas de cambiarla.

Vamos a sumergirnos en él a continuación, incluyendo cómo se relaciona este `FieldDto` con los `Field`
objetos que devolvemos de `configureFields()`.
