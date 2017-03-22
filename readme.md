Phing Symfony Command Task
==========================

This Phing task allows you to call commands from a Symfony Console Application comfortably.

There is a built in `SymfonyConsoleTask` in Phing, but with this custom task you can:

* see the output as soon as it is ready (displaying progress bars)
* configure default Symfony Application Console location
* configure the executable with which the Console is run (environments on Windows usually require running the console trough PHP binary, not executing directly), again with a configurable default value
* escaping argument values and paths as in the `ExecTask`

Usage
-----

To call the commands in the simplest possible way like this:

```xml
<symfony-cmd cmd="test:test"/>
```

you have to configure the default values:

```xml
<property name="symfony-command.default.app" value="path/to/console"/>
<property name="symfony-command.default.executable" value="php"/>
```

Of course you can set these properties in any other regular way.

If you do not want to use the defaults, or you want to override them you can always specify both or one of them:

```xml
<symfony-cmd executable="php-cgi" app="path/to/another/console" cmd="test:test"/>
```

If you want to pass any additional parameters, you can use `<arg>` elements to do so (as in `ExecTask`):

```xml
<symfony-cmd cmd="test:test">
  <arg value="--strict"/>
  <arg path="path/to/tests"/>
</symfony-cmd>
```

Installation
------------

1) Install package [`vasek-purchart/phing-symfony-command-task`](https://packagist.org/packages/vasek-purchart/phing-symfony-command-task) with [Composer](https://getcomposer.org/):

```bash
composer require vasek-purchart/phing-symfony-command-task
```

2) Register this task under a name of your choosing.

There are several ways how to register a task, see the `TaskDefTask` documentation. The recommended way is putting this in your `build.xml`:

```xml
<taskdef name="symfony-cmd" classname="VasekPurchart\Phing\SymfonyCommand\SymfonyCommandTask"/>
```

You can pick any other name for the command if you would like to.
