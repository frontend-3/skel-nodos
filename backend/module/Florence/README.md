# Florence

__Ultima actualización:__ 2015-08-11 para la versión 1.0.1 de Florence.

Florence es un módulo de Zend Framework 2 diseñado para definir y crear Forms, Elements e InputFilters de Zend Framework 2 sin mucho esfuerzo, a través de un fichero en formato [YAML](http://yaml.org).

Florence sigue la práctica _"convention over configuration"_, así que todo lo que pueda ser definido por defecto, lo está, minimizando así el tiempo y esfuerzo del programador.

Florence fue creada por [Jaime G. Wong](http://jgwong.org) en Agosto 2015, usando Zend Framework 2.4. Todavía es un trabajo en progreso. Cambios, bugfixes y contribuciones son todas bienvenidas.

## Introducción

El concepto de Florence es crear al vuelo objetos de __Form__, __Elements__ e __InputFilters__ para poder crear, modificar y validar formularios. Si bien Florence agiliza y facilita bastante la creación de éstos es importante _entender_ previamente cómo funcionan. Este documento asume que uno está familiarizado con los objetos Form, Element e InputFilter de Zend Framework 2.

Todo lo que tiene que ver con el renderizado y validación del formulario es a través de la funcionalidad standard de ZF2. El trabajo de Florence termina en crear estos objetos.

## Instalación

TO-DO. Se supone que ya viene instalado en el Skelsus. :)

## Uso

Como ejemplo, imaginemos que vamos a crear un formulario de suscripción. El formulario tiene una serie de campos (e.g. inputs, selects, checkboxes). Cada uno de estos se denomina __elemento__.

> Un __elemento__ de Florence es un conjunto de definiciones para el formulario y su validación. Cada elemento tiene un __nombre__ y __atributos__.  
Por ejemplo, podemos tener un elemento de nombre `first_name` y un atributo `type` con valor `Text`.

Primero, debemos crear un fichero en formato [YAML](http://yaml.org) donde definimos cada uno de los elementos de nuestro formulario con sus atributos.

> YAML es un formato de fichero que representa estructuras de datos con un formato bien legible. Es similar a JSON, con la principal diferencia de que YAML es fácil de escribir por humanos.

El fichero debe tener la extensión `.yaml` y debe estar ubicado en un directorio `Formdefs` dentro del controlador donde quieres crear el formulario.

Por ejemplo, digamos que estoy trabajando en mi controlador `Subscription` y tengo mi fichero de definición con nombre `subscription.yaml`. La ruta podría ser, siguiendo el ejemplo:

    modules/Subscription/src/Subscription/Formdefs/subscription.yaml

Creamos una instancia de Florence en el controlador, llamando al nombre del fichero sin la extensión `.yaml`:

    $flr = new Florence('subscription');

# Florence

__Ultima actualización:__ 2015-08-11 para la versión 1.0.0 de Florence.

Florence es un módulo de Zend Framework 2 diseñado para definir y crear Forms, Elements e InputFilters de Zend Framework 2 sin mucho esfuerzo, a través de un fichero en formato [YAML](http://yaml.org).

Florence sigue la práctica _"convention over configuration"_, así que todo lo que pueda ser definido por defecto, lo está, minimizando así el tiempo y esfuerzo del programador.

Florence fue creada por [Jaime G. Wong](http://jgwong.org) en Agosto 2015, usando Zend Framework 2.4. Todavía es un trabajo en progreso. Cambios, bugfixes y contribuciones son todas bienvenidas.

## Introducción

El concepto de Florence es crear al vuelo objetos de __Form__, __Elements__ e __InputFilters__ para poder crear, modificar y validar formularios. Si bien Florence agiliza y facilita bastante la creación de éstos es importante _entender_ previamente cómo funcionan. Este documento asume que uno está familiarizado con los objetos Form, Element e InputFilter de Zend Framework 2.

Todo lo que tiene que ver con el renderizado y validación del formulario es a través de la funcionalidad standard de ZF2. El trabajo de Florence termina en crear estos objetos.

## Instalación

TO-DO. Se supone que ya viene instalado en el Skelsus. :)

## Uso

Como ejemplo, imaginemos que vamos a crear un formulario de suscripción. El formulario tiene una serie de campos (e.g. inputs, selects, checkboxes). Cada uno de estos se denomina __elemento__.

> Un __elemento__ de Florence es un conjunto de definiciones para el formulario y su validación. Cada elemento tiene un __nombre__ y __atributos__.  
Por ejemplo, podemos tener un elemento de nombre `first_name` y un atributo `type` con valor `Text`.

Primero, debemos crear un fichero en formato [YAML](http://yaml.org) donde definimos cada uno de los elementos de nuestro formulario con sus atributos.

> YAML es un formato de fichero que representa estructuras de datos con un formato bien legible. Es similar a JSON, con la principal diferencia de que YAML es fácil de escribir por humanos.

El fichero debe tener la extensión `.yaml` y debe estar ubicado en un directorio `forms` (ojo, PLURAL -- no el directorio `form` propio de ZF2) dentro del controlador donde quieres crear el formulario.

Por ejemplo, digamos que estoy trabajando en mi controlador `Subscription` y tengo mi fichero de definición con nombre `subscription.yaml`. La ruta podría ser, siguiendo el ejemplo:

    modules/Subscription/src/Subscription/forms/subscription.yaml

Creamos una instancia de Florence en el controlador, llamando al nombre del fichero sin la extensión `.yaml`:

    $flr = new Florence('subscription');

Lo que se ha hecho es leer el YAML y crear una estructura interna (léase: un array) sobre la cual se crearán el Form, los Elements y los InputFilters.

Para crear los mencionados, llamamos al método generate().

    $flr->generate();

Son dos pasos separados debido a que en la práctica a veces necesitamos manipular los elementos del formulario en el controlador _antes_ de generar los objetos. Por ejemplo, queremos cargar la lista de opciones de un Select con un query complicado o queremos activar o desactivar elementos.

`generate()` instancia el objeto Form, objetos Element por cada elemento e InputFilters para cada uno de ellos según se haya definido.

Y eso es todo. Todo lo demás ya es lo usual de ZF2.

## Formato del Fichero de Definición

Siguiendo con el ejemplo de nuestro formulario de suscripción, imaginemos que necesitamos un formulario sencillo con tres campos: `first_name`, `last_name` y `accept_tos`.

    first_name:
         type:       Text
         required:   true
    
    last_name:
        type:        Text
        only_alpha:  true
    
    accept_tos:
        type:        Checkbox

