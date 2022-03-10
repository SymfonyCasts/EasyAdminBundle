¡Hola amigos! ¡Estamos de enhorabuena con este tutorial! Se trata de EasyAdmin: mi
generador de administración favorito para Symfony. Simplemente... te da tantas características fuera de
de la caja. ¡Y tiene un aspecto estupendo! Esto no debería ser una sorpresa porque su creador
es el siempre impresionante Javier Eguiluz.

Así que vamos a divertirnos un poco y a aprender a doblegar EasyAdmin a nuestra voluntad. Porque conseguir
un montón de funciones gratis es genial... siempre y cuando podamos ampliarlo para hacer
cosas locas cuando lo necesitemos.

## Configuración del proyecto

Para exprimir al máximo lo "fácil" de EasyAdmin, deberías codificar conmigo.
Probablemente conozcas el procedimiento: descarga el código del curso desde esta página y descomprímelo para
encontrar un directorio `start/` con el mismo código que ves aquí. Comprueba el archivo
`README.md` para ver todos los detalles de la configuración. Yo ya he hecho todos estos pasos
excepto dos.

Para el primero, busca tu terminal y ejecuta

```terminal
yarn install
```

Ya he ejecutado esto para ahorrar tiempo... así que pasaré a compilar mis activos con:

```terminal
yarn watch
```

También puedes ejecutar:

```terminal
yarn dev-server
```

Que puede hacer cosas geniales como actualizar tu CSS sin refrescar.

Perfecto Para lo segundo, abre otra pestaña y ejecuta

```terminal
symfony serve -d
```

Esto lanza un servidor web local - usando el binario Symfony - en
https://127.0.0.1:8000. Seré perezoso manteniendo `Cmd` y haciendo clic en el enlace para que se
abrir mi navegador. Di "hola" a... ¡Cauldron Overflow! Si has estado haciendo nuestra
Serie Symfony 5, seguro que conoces este proyecto. Pero, este
es un proyecto Symfony 6, no Symfony 5:

[[[ code('312def2054') ]]]

Oooo. Si estás usando Symfony 5, no te preocupes: muy poco será diferente.

No tienes que preocuparte demasiado por la mayor parte del código dentro del proyecto. El
más importante es probablemente nuestro directorio `src/Entity/`. Nuestro sitio tiene preguntas,
y cada `Question` tiene un número de respuestas. Cada `Question` pertenece a un único
`Topic`... y luego tenemos una entidad `User`.

Nuestro objetivo en este tutorial es crear una sección de administración rica que permita a nuestros usuarios
gestionar todos estos datos.

## Instalación de EasyAdmin

¡Así que vamos a instalar EasyAdmin! Busca tu terminal y ejecuta

```terminal
composer require admin
```

Este es un alias de Flex para `easycorp/easyadmin-bundle`. Observa que descarga la
nueva y brillante versión 4 de EasyAdmin, que sólo funciona con Symfony 6. Así que si estás usando
Symfony 5, ejecuta:

```terminal
composer require admin:^3
```

para obtener la versión 3. En este momento, la versión 4 y la versión 3 son idénticas,
así que no notarás ninguna diferencia. Pero en adelante, las nuevas funciones sólo se
añadidas a la versión 4.

¡Genial! Ahora que esto está instalado, ¿qué es lo siguiente? ¿¡Enviarlo!? Bueno, antes de empezar a
desplegar y celebrar nuestro éxito... si queremos ver realmente algo
en nuestro sitio, vamos a necesitar un panel de control. ¡Generemos eso a continuación!
