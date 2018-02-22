# doctrine-yuml-bundle

[![Build Status](https://travis-ci.org/Nono1971/doctrine-yuml-bundle.svg?branch=master)](https://travis-ci.org/Nono1971/doctrine-yuml-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/build-status/master) [![License](https://poser.pugx.org/onurb/doctrine-yuml-bundle/license)](https://packagist.org/packages/onurb/doctrine-yuml-bundle) [![Latest Stable Version](https://poser.pugx.org/onurb/doctrine-yuml-bundle/v/stable)](https://packagist.org/packages/onurb/doctrine-yuml-bundle) [![Total Downloads](https://poser.pugx.org/onurb/doctrine-yuml-bundle/downloads)](https://packagist.org/packages/onurb/doctrine-yuml-bundle)

Bundle to visualise doctrine entities graph with yuml in Symfony

Compatible with SF4 or SF3

This bundle is based on Marco Pivetta's work for zend doctrine ORM Module and zend developper tools

It uses the yuml.me api to display your project's objects mapping.


## Installation
### Symfony 4

  Run the `composer require onurb/doctrine-yuml-bundle` command in your console

  Adjust your parameters to personalize the render in config/packages/dev/yuml.yaml, or use annotations as describe bellow

  Adjust the route (if you want to add a prefix) in config/routes/dev/yuml.yaml

### Symfony 3
- Add this bundle to your project as a composer dependency:
```javascript
    // composer.json
    {
        // ...
        require: {
            // ...
            "onurb/doctrine-yuml-bundle": "~1.0"
        }
    }
```

- Declare the bundle in your application kernel:
```php
    // app/AppKernel.php
    public function registerBundles()
    {
        // ...
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            // ...

            $bundles[] = new Onurb\Bundle\YumlBundle\OnurbYumlBundle();
        }
        return $bundles;
    }
```

- Add this route in your global routing_dev configuration (with optional prefix)
```yml
    # app/config/routing_dev.yml

    # ...
    doctrine_yuml:
        resource: "@OnurbYumlBundle/Resources/config/routing.yml"
        prefix:   /my_prefix/
```

configure access to the yuml route (if you use security of course)

## Use
Click on Doctrine icon added in the dev toolbar.

Run the `yuml:mappings` console command to save the image locally.

# Personalize the render
Full personalisation for mapping rendering, defining parameters or using Metadatagrapher annotations
[![Colored Map with note](https://yuml.me/23e34ac0.png)](https://yuml.me/23e34ac0.png)

## define the output file extension
Use the parameter file :
```yml
     # app/config/parameters.yml        => symfony 3
     # config/packages/dev/yuml.yaml    => symfony 4

    parameters:
        onurb_yuml.extension: svg
        # ...
```
Extensions allowed : jpg, png (default), svg, pdf, or json

## define the yuml rendering style
Use the parameter file :
```yml
     # app/config/parameters.yml        => symfony 3
     # config/packages/dev/yuml.yaml    => symfony 4

    parameters:
        onurb_yuml.style: scruffy
        # ...
```
Styles allowed : plain (default), boring or scruffy

## define the graph direction
Use the parameter file :
```yml
     # app/config/parameters.yml        => symfony 3
     # config/packages/dev/yuml.yaml    => symfony 4

    parameters:
        onurb_yuml.direction: LR
        # ...
```
Directions allowed : LR (left to Right), RL (Right to Left), TB (Top to bottom => default).

## define the graph scale
Use the parameter file :
```yml
     # app/config/parameters.yml        => symfony 3
     # config/packages/dev/yuml.yaml    => symfony 4

    parameters:
        onurb_yuml.scale: huge
        # ...
```
Scales allowed : huge, big, normal (default), small or tiny.


## Hide entities attributes properties (unique, type, length, ...)
Use the parameter file :
```yml
     # app/config/parameters.yml        => symfony 3
     # config/packages/dev/yuml.yaml    => symfony 4

    parameters:
        onurb_yuml.show_fields_description: false
        # ...
```
this parameter is set to true by default since v1.1

##### Warning : In Symfony 3, don't forget to also define parameter keys in parameters.yml.dist to avoid symfony update
to clear your parameters

### Toggle attributes properties on a specific class using annotations
to show only desired classes details if global parameter is set to false :
```php
    namespace My\Bundle\Entity

    use Onurb\Doctrine\ORMMetadataGrapher\Mapping as Grapher;

    /**
    * @Grapher\ShowAttributesProperties()
    */
    Class MyClass
    {
        // ...
    }
```

And, if set to true (default), you can hide properties for a specific class :
```php
    namespace My\Bundle\Entity

    use Onurb\Doctrine\ORMMetadataGrapher\Mapping as Grapher;

    /**
    * @Grapher\HideAttributesProperties()
    */
    Class MyClass
    {
        // ...
    }
```

## Define colors for entities rendering
### Define default color for a complete bundle or namespace by defining it in parameters.yml
```yml
     # app/config/parameters.yml        => Symfony 3
     # config/packages/dev/yuml.yaml    => Symfony 4

    parameters:
        onurb_yuml.colors:
            App\Security: red
            App\Blog: blue
        # ...
```

You can also define colors for classes this way... but it is easier using annotations as described next

Complete list of yuml colors availables [here](https://yuml.me/69f3a9ba.svg)
                                       [![Color list](https://yuml.me/69f3a9ba.svg)](https://yuml.me/69f3a9ba.svg)

### Define Entity color in graph using annotations
```php
    namespace My\Bundle\Entity

    use Onurb\Doctrine\ORMMetadataGrapher\Mapping as Grapher;

    /**
    * @Grapher\Color("blue")
    */
    Class MyClass
    {

    }
```

### Display specific entity method
You can display specific methods in the graph, using annotations
```php
    namespace My\Bundle\Entity

    use Onurb\Doctrine\ORMMetadataGrapher\Mapping as Grapher;

    // ...
    Class MyEntity
    {
        // ...

        /**
         * @Grapher\IsDisplayedMethod()
         */
        public function myDisplayedMethod()
        {
            // ...
        }
    }
```
## Hide columns
### Hide all columns of the entity
If you want, you can hide Entity attributes with annotations : using annotation on the class :

```php
/**
* @Grapher\Hidecolumns
*/
MyEntity
{
    //[...]
}
```
### Hide specific column
Or hide a specific secret column you want to hide, using annotation on the Entity column :
(it could be usefull to hide you credential logic, or to avoid the display recurrent fields,
like created_at, or updated_at in the graph...)

```php
MyEntity
{
    /**
     * @ORM\Column(/* ... */)
     * @Grapher\HiddenColumn
     */
    private $secret;
}
```

## Add notes to comment entities in the graph
use annotations :
```php
    namespace My\Bundle\Entity

    use Onurb\Doctrine\ORMMetadataGrapher\Mapping as Grapher;

    /**
    * @Grapher\Note("Some information about this class")
    */
    Class MyClass
    {

    }
```
Notes are yellow by default, but you can customize note's' color
```php
    /**
    * @Grapher\Note(value="Some information about this class", color="blue")
    */
```
