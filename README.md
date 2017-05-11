# Container

[![Build Status](https://scrutinizer-ci.com/g/sensorario/container/badges/build.png?b=master)](https://scrutinizer-ci.com/g/sensorario/container/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sensorario/container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sensorario/container/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/sensorario/container/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sensorario/container/?branch=master)

## Features

 - [Constructor Injection][1]
 - [Method Injection][2]

## Suggestions

 - implement your own [Container Loader][3]

## Folding

```
src/
└── Sensorario
    └── Container
        ├── ArgumentBuilder.php
        ├── Container.php
        ├── Objects
        │   ├── Argument.php
        │   └── Service.php
        ├── Register.php
        └── Resolver
            ├── ConstructorResolver.php
            ├── MethodResolver.php
            ├── Resolver.php
            └── ResolverInterface.php

4 directories, 9 files
```

 [1]: doc/constructor-injection.md
 [2]: doc/method-injection.md
 [3]: doc/container-loader.md
