# doctrine-yuml-bundle
Bundle to visualise doctrine entities graph with yuml in Symfony2

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

- Add this route in your global routing_dev configuration
    of course, you can add a prefix if you want
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
