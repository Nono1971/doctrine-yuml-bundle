# doctrine-yuml-bundle
Bundle to visualise doctrine mapping with yuml in Symfony2

This bundle is based on Marco Pivetta's work for zend doctrine ORM Module and zend developper tools
It uses the yuml api to display the doctrine data mapping.


## Installation

Installing this bundle can be done through these simple steps:

- Add this bundle to your project as a composer dependency:
```javascript
    // composer.json
    {
        // ...
        require: {
            // ...
            "onurb/doctrine-yuml-bundle": "dev-master"
        }
    }
```

- Declare these bundles in your application kernel:
```php
    // app/AppKernel.php
    public function registerBundles()
    {
        // ...
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            // ...

            $bundles[] = new Sensio\Bundle\BuzzBundle\SensioBuzzBundle();
            $bundles[] = new Onurb\Bundle\YumlBundle\OnurbYumlBundle();
        }
        return $bundles;
    }
```

- Add this route in your global routing configuration
```yml
    # app/config/routing.yml

    # ...
    doctrine_yuml:
        resource: "@OnurbYumlBundle/Resources/config/routing.yml"
        prefix:   /
```

- Copy this complete folder from your vendor lib/Onurb/Bundle/YumlBundle/Resources/WebProfilerBundle/ in the folder app/resources
=> is overwrites a view from sensio\WebProfilerBundle to add an icon in the dev toolbar

## Use
in the toolbar

The controller gets all metadata from Doctrine, sends data to yuml.me
and redirects to the mapping page given by the api


