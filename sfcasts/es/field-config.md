Otra propiedad que tenemos dentro de `User` es `$roles`, que en realidad almacena
un *array* de los roles que debe tener este usuario:

[[[ code('24694ac22e') ]]]

Eso es *probablemente* algo bueno para incluir en nuestra página de administración. Y afortunadamente
¡EasyAdmin tiene un `ArrayField`!

## ArrayField

¡Compruébalo! Di `yield ArrayField::new('roles')`:

[[[ code('bbba042727') ]]]

Y luego vuelve a tu navegador. En la página del índice... ¡qué bien! Se muestra como
una lista separada por comas. Y en la página "Editar"... ¡qué bien! Se ha añadido
¡un bonito widget para añadir y eliminar roles!

## Añadir texto de ayuda a los campos

La única parte complicada podría ser recordar *qué* roles están disponibles. Ahora mismo
tienes que escribir cada uno manualmente. Al menos podemos ayudar a nuestros administradores volviendo
a nuestro campo de matriz e implementando un método llamado `->setHelp()`. Añade a
un mensaje que incluya los roles disponibles:

[[[ code('50fe784f96') ]]]

Ahora cuando refresquemos... ¡mucho mejor!

## ->setFormType() y ->setFormTypeOptins()

Pero... Ahora que lo veo, podría quedar *incluso* mejor si tuviéramos casillas de verificación.
Así que vamos a ver si podemos *cambiar* el `ArrayField` para que muestre casillas de verificación. Mantén
 `Cmd` y abre esta clase principal.

Esto es *realmente* interesante, porque realmente puedes *ver* cómo se configura el campo
configurado dentro de su método `new()`. Establece el nombre de la plantilla (hablaremos
sobre las plantillas más adelante), pero *también* establece el tipo de formulario. Entre bastidores
el `ArrayField` utiliza un `CollectionType`. Si estás familiarizado con el componente de formulario Symfony
Ya sabes que, para representar las casillas de verificación, necesitas el `ChoiceType`.
Me pregunto si podemos *utilizar* `ArrayField`... pero anular su tipo de formulario para que sea
 `ChoiceType` .

Vamos a... ¡intentarlo!

Primero, encima de esto, añade `$roles = []` y enumera nuestros roles. Luego, aquí abajo, después de
 `->setHelp()` , uno de los métodos que podemos llamar es `->setFormType()`... también está
 `->setFormTypeOptions()` . Selecciona `->setFormType()` y ponlo en `ChoiceType::class`:

[[[ code('9b1814a144') ]]]

*Entonces* `->setFormTypeOptions()`... porque una de las opciones que *debes* pasar
a este tipo de formulario es `choices`. Establece esto como `array_combine()` y pasa `$roles` dos veces:

[[[ code('b5ef65869c') ]]]

¡Me encantan los rollos!

Lo sé, parece raro. Esto creará un array en el que estas son las claves
*y* los valores. El resultado es que estos serán *tanto* los valores que se guardan
en la base de datos si ese campo está marcado *y* lo que se muestra al usuario. Por último,
establece `multiple` en `true` - porque podemos seleccionar varios roles - y
 `expanded` a `true`... que es lo que hace que los `ChoiceType` se muestren como casillas de verificación:

[[[ code('5341461c67') ]]]

¡Muy bien! Veamos qué ocurre. Refresca y... ¡explota! ¡Emocionante!

> Se ha producido un error al resolver las opciones de `ChoiceType`: Las opciones
> `allow_add`, `allow_delete`, `delete_empty`, `entry_options` y `entry_type`
> no existen.

Hmm... Reconozco estas opciones como opciones que pertenecen al `CollectionType`,
que es el tipo que el `ArrayField` estaba usando *originalmente*. Esto me dice que
algo, *en algún sitio* está intentando añadir estas opciones a nuestro tipo de formulario... que
no queremos porque... ¡ya no usamos `CollectionType`!

Así que... ¿quién *está* configurando esas opciones? Esto es complicado. Podrías esperar verlas
dentro de `ArrayField`. Pero... ¡no está aquí! ¿Qué misterioso ser se está metiendo
con nuestro campo?

## Hola configuradores de campo

La respuesta es algo llamado _Configurador_.

Vuelve a bajar a `vendor/`. Ya he abierto `easycorp/easyadmin-bundle/src/`.
Antes hemos visto el directorio `Field/`: estos son todos los campos incorporados.

Después de crear un campo, EasyAdmin lo hace pasar por un sistema `Configurator` 
que puede hacer cambios *adicionales* en él. Este directorio `Configurator/` 
contiene *esos*. Hay un par de ellos -como `CommonPreConfigurator` - que se
aplican a *todos* los campos. Devuelve `true` desde `supports()`... y realiza varias
normalizaciones en el campo. `CommonPostConfigurator` es otro que se aplica a
todos los campos.

Pero *entonces*, también hay un montón de configuradores que son específicos
a sólo *un*... o quizá a unos pocos... tipos de campo, entre ellos `ArrayConfigurator`.
Este configurador hace su trabajo cuando el `$field` es un `ArrayField`. El
 `$field->getFieldFqcn()` ayuda básicamente a preguntar:

> Oye, ¿el campo actual que se está configurando es un `ArrayField`? Si lo es,
> entonces llama a mi método `configure()` para que pueda hacer algunas cosas

Y... ¡sí! *Aquí* es donde se añaden esas opciones. El sistema del Configurador
es algo que veremos más adelante. Incluso vamos a crear nuestro
¡propio! Por ahora, sólo tienes que saber que existe.

## Refactorización a ChoiceField

Así que... En nuestra situación, *no* queremos que el `ArrayConfigurator` haga su trabajo.
Pero, por desgracia, ¡no tenemos otra opción! El Configurador *siempre* va a aplicar su lógica
aplicar su lógica si se trata de un `ArrayField`.

Y en realidad, ¡eso está bien! En `UserCrudController.php`, no me di cuenta
¡al principio, pero también hay un `ChoiceField`!

[[[ code('3cb0eed2ae') ]]]

Mantén pulsado `Cmd` o `Ctrl` para abrirlo. Sí, podemos ver que ya utiliza `ChoiceType`.
*Por tanto*, no necesitamos coger `ArrayField` e intentar convertirlo *en* una opción...
¡ya hay un `ChoiceField` integrado *hecho* para esto!

Y ahora no necesitamos establecer el tipo de formulario... y no necesitamos la ayuda ni las
opciones del tipo de formulario. Probablemente *podría* establecer las opciones de esa manera, pero el
 `ChoiceField` tiene un método especial llamado `->setChoices()`. Pasa eso mismo
cosa: `array_combine($roles, $roles)`. Para las demás opciones, podemos decir
 `->allowMultipleChoices()` y `->renderExpanded()`:

[[[ code('e9b57b211f')]]

¿Qué te parece?

Vamos a probar esto. Refresca y... *¡eso* es lo que esperaba! De nuevo en
el índice... `ChoiceType` *todavía* se muestra como una bonita lista separada por comas.

Ah, y por cierto: si quieres ver la lógica que hace que `ChoiceType` se muestre
como una lista separada por comas, hay un `ChoiceConfigurator.php`. Si lo abres...
y te desplazas hasta el final -más allá de un montón de código de normalización- aquí está
 `$field->setFormattedValue()` donde se implosiona el `$selectedChoices` con una coma.

## Rendering ChoiceList as Badges

Ah, y hablando de este tipo -permíteme cerrar algunas clases básicas- otro
método que podemos llamar es `->renderAsBadges()`:

[[[ code('e7e4282a44') ]]]

Eso afecta al "valor formateado" que acabamos de ver... y lo convierte en estos
pequeñitos. ¡Qué bonito!

A continuación, vamos a tratar el campo `$avatar` de nuestro usuario, ¡que debe ser un campo de carga!
