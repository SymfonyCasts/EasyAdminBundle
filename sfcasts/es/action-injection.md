Habrás notado que parece que evito la inyección de "acción". Tanto para
`QuestionRepository` y `ChartBuilderInterface`, normalmente, cuando estoy en un controlador
Me gusta ser perezoso y autoconectarlas directamente en el método del controlador.

## El problema de la inyección de acciones

Vamos a intentarlo, al menos para `ChartBuilderInterface`. Elimina
`ChartBuilderInterface` del constructor... y, en su lugar, añádelo al método
`ChartBuilderInterface $chartBuilder`.

Y ahora... Tengo que pasar `$chartBuilder` a `createChart()`... porque, aquí abajo
ya no podemos hacer referencia a la propiedad. Así que añade `ChartBuilderInterface $chartBuilder`...
y utiliza ese argumento.

Genial. Así que, en teoría, esto debería funcionar... porque esto es un controlador normal y...
¡así es como funciona la inyección de acciones! Pero ya te habrás dado cuenta de que PhpStorm está
bastante enfadado. Y, ¡tiene razón! Si refrescamos, ¡un gran error!

> `DashboardController::index` debe ser compatible con
> `AbstractDashboardController::index`.

El problema es que nuestra clase madre - `AbstractDashboardController` - tiene un `index()`
método sin argumentos. Así que no es legal que lo anulemos y añadamos un
argumento necesario.

## La solución

Pero si quieres que la inyección de acciones funcione, hay una solución: permitir que el argumento
sea opcional. Así que añade ` = null`.

Eso hace feliz a PHP y, en la práctica, aunque sea opcional, Symfony
pasará el servicio del constructor de gráficos. Así que esto funcionará... pero para codificar a la defensiva
por si acaso, voy a añadir una pequeña función `assert()`.

Esta puede ser o no una función con la que estés familiarizado. Viene del propio PHP.
Pones una expresión dentro como `null !== $chartBuilder` - y si esa expresión
es falsa, se lanzará una excepción.

Así que ahora podemos saber con seguridad que si nuestro código llega hasta aquí, tenemos
un objeto `ChartBuilderInterface`.

Actualiza ahora y... ¡lo tienes! Así que la inyección de acciones sigue funcionando... pero no es
tan impresionante como lo es normalmente. Sin embargo, tiene una ventaja concreta
sobre la inyección de constructores: el servicio `ChartBuilderInterface` no se instanciará
a menos que se llame al método `index()`. Así que si estuvieras en un controlador Crud normal
con múltiples acciones, la inyección de acciones te permite asegurarte de que un servicio
sólo se instancie para la acción que lo necesite, en lugar de en todas las situaciones.

A continuación: vamos a aprender a anular plantillas, como la plantilla de diseño de EasyAdmin, o
cómo se muestra un `IdField` en toda nuestra área de administración.
