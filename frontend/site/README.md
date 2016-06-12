skel
====
Skeleton para desarrollo frontend configurado con GruntJS.

Requisitos
==============
NodeJS >= 0.10 [http://nodejs.org/]

Python >= 2.7 [https://www.python.org/downloads/]

Glue [http://glue.readthedocs.org/en/latest/installation.html]

Grunt CLI [http://gruntjs.com/getting-started]

Instalacion de dependencias
===========================

```shell
cd frontend/site
npm install
```

Tareas disponibles
==================

Todas las tareas pueden ejecutarse en produccion agregando el argumento *--prod*

**Construccion de archivos**
```shell
grunt deploy
```


**Compilacion de archivos stylus**
```shell
grunt styles
```


**Compilacion de archivos jade**
```shell
grunt templates
```

**Generacion de sprites**

```shell
grunt sprites
```

**Minificado de css**

```shell
grunt cssmin
```

**Servidor de archivos estaticos**

```shell
grunt connect
```

**Watch de cambios en templates o styles y compilacion automatica**

```shell
grunt watch
```

**Compilacion de modulos de requirejs**

```shell
grunt requirejs
```