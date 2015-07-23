# doctrine-yuml-bundle
Bundle to visualise doctrine mapping with yuml in Symfony2

This bundle is based on Marco Pivetta's work for zend doctrine ORM Module and zend developper tools
It uses the yuml api to display the doctrine data mapping.


## Installation

Installing this bundle can be done through these simple steps:

1. Add this bundle to your project as a composer dependency:
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

2. Declare these bundles in your application kernel:
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

3. Add this route in your global routing configuration
```yml
    # app/config/routing.yml

    # ...
    doctrine_yuml:
        resource: "@OnurbYumlBundle/Resources/config/routing.yml"
        prefix:   /
```

## Use
simply go to the url http://pathToYourProject/yuml

The controller gets all metadata from Doctrine, sends data to yuml.me
and redirects to the mapping page given by the api


## Link
You can add this code into your footer (or anywhere else) to create a link with a nice Doctrine icon
```twig
    {% if app.environment == 'dev' %}
        <a href="{{ path('doctrine_yuml_mapping') }}" target="_blank" title="Doctrine Mapping">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAaCAYAAAC3g3x9AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH3AwOFjIRE2xHhwAAAvZJREFUSMedlF9oVnUYxz/P77zzzRmruYELGhh48Qor3026sOlFUFHrQmMuvXAQZrS9r+DawkVSeCPsItmibbB3zMKSFBGLJcIuhnazaM2tZjRRr15LcP3BomKdc35PFxs7O2fnde92Lp/z/D6/7/N9nt8jRD7NPF3l4w0BO0CnHJFj0jc5RpGfiQZ8vB6gASgHedZX+drNpJ9ZM1BhWySSEGTEzaafXxNQ0NGYvA2iMuy1pvesGugk3feAWzG5SUTOetn07tUp7P7xd2d2Swo4FQtVueBlal8vGggg58/71nF6VfkGxIsWAeS8bPpQ7NllTTm8PeX7tgfhhbj/ocPCgNM32VIQuFDKR8D64oZEVeCM0z/VvKxkP5M+AgwuhUl1CtPSg2nsgMS6WI2KHPBbaztDCt3M9p2CvbLgT2Dw258gTzw1ryU/gz35Grj/xUm1in2ppP/7EaMgYHNRGAB//UFI7cEuSJQUaK7p1aYmR9xM3YuCXo616JFKTPsQUlkduPbTGHagHdy5OEtfNYLuLej5/V+xJ/ajN8YDpVt3YFq6oSQZo1P2iJ+pm1a0Zn5sS5HnmpGKx+H+bJC4fgNS3wgmGFvNz2C734C5v5cif0ko+tjiBa+0Ibv2FjUwUp3C7OvEnn5/aXiTCTUjfFsRY2iXhRICeYVHAexwP/LzbSh9GP78LcgqLcM0tsO6YN71zgz2XFeUdy+hwjTKk/NPxUW//SqcUlaB0zYYhk2NYk+9A370mcs1g8qFgiVtrMIc/RQ2bQ5g45exQ0djYAD2S+N4MgzkY6d137tIeVUA++Eq9rPjYG1c+l3Hc84YyU24qLbGKizbGMBufocd7ADPLdAgOSa5iX8Wt42bre0SpTPkyOYapOFNmM1jv/gw/nUAiH6c6Js6GFpfCuJlaz8Q5a2V9mAElnNc57DkJtzYBetl0w2qclIgtQIqD3Qm+ic/f+DGBtDjGP9e3cugDQL1CpUISVHuqHAd5aKTfOiSdI/9Gz37P6crCTosFB7sAAAAAElFTkSuQmCC" alt="Doctrine Mapping">
        </a>
    {% endif %}
```