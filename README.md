# doctrine-yuml-bundle

[![Build Status](https://travis-ci.org/Nono1971/doctrine-yuml-bundle.svg?branch=master)](https://travis-ci.org/Nono1971/doctrine-yuml-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Nono1971/doctrine-yuml-bundle/build-status/master) [![Latest Stable Version](https://poser.pugx.org/onurb/doctrine-yuml-bundle/v/stable)](https://packagist.org/packages/onurb/doctrine-yuml-bundle) [![Total Downloads](https://poser.pugx.org/onurb/doctrine-yuml-bundle/downloads)](https://packagist.org/packages/onurb/doctrine-yuml-bundle)

Bundle to visualise doctrine entities graph with yuml in Symfony3 (or 2)

This bundle is based on Marco Pivetta's work for zend doctrine ORM Module and zend developper tools
It uses the yuml api to display your project's objects mapping.


## Installation

Installing this bundle can be done through these simple steps:

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


# New Features
Full personalisation for mapping rendering defining parameters or using Metadatagrapher annotations
[![Colored Map with note](http://yuml.me/23e34ac0)](http://yuml.me/23e34ac0)

## Display entities attributes properties (unique, type, length, ...)
Use the parameter file :
```yml
     # app/config/parameters.yml

     # ...
    parameters:
        onurb_yuml.show_fields_description: true
```
this parameter is set to false by default

##### Warning : don't forget to also define parameter keys in parameters.yml.dist to avoid symfony update to clear your parameters

### Toggle attributes properties on a specific class using annotations
to show only desired classes details :
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

And if display is set to true in your parameters, you can hide specific classes details :
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
### Define default color for a complete bundle by defining it in parameters.yml
```yml
     # app/config/parameters.yml

     # ...
    parameters:
        onurb_yuml.colors:
            My\AppBundle: green
            My\OtherBundle: red
```

You can also define colors for classes this way... but it is easier using annotations as described next

Complete list of yuml colors availables [here](http://yuml.me/69f3a9ba.svg)
                                       [![Color list](http://yuml.me/69f3a9ba.svg)](http://yuml.me/69f3a9ba.svg)

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

## Display specific entity method
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
