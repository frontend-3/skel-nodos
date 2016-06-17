skel
====
Skeleton para desarrollo frontend configurado con GulpJS.

Requisitos
==============
NodeJS >= 0.12 [http://nodejs.org/]

Python >= 2.7 [https://www.python.org/downloads/]

Glue [http://glue.readthedocs.org/en/latest/installation.html]

Gulp CLI [http://gulpjs.com/getting-started]

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
gulp
```


**Compilacion de archivos stylus**
```shell
gulp styles
```


**Compilacion de archivos jade**
```shell
gulp templates
```

**Generacion de sprites**

```shell
gulp sprites
```


**Servidor de archivos estaticos**

```shell
gulp server
```

**Watch de cambios en templates o styles y compilacion automatica**

```shell
gulp watch
```

**Compilacion de modulos de requirejs**

```shell
gulp requirejs
```
