<?php

declare(strict_types=1);

namespace SmartCore\CMSBundle\Router;

use Symfony\Component\Routing\Generator\Dumper\GeneratorDumper;
//use Symfony\Component\Routing\Generator\Dumper\PhpGeneratorDumper;

/**
 * Своеобразное решение с методом getDeclaredRouteData($name), который возвращает данные из private static $declaredRoutes[]
 * Сделано из-за того, что метод getRouteCollection() очень ресурсоёмкий.
 *
 * @todo можно попробовать сделать пулл реквест в симфони.
 *
 * @deprecated пока не используется.
 */
class PhpGeneratorDumper extends GeneratorDumper
{
    /**
     * Dumps a set of routes to a PHP class.
     *
     * Available options:
     *
     *  * class:      The class name
     *  * base_class: The base class name
     *
     * @param array $options An array of options
     *
     * @return string A PHP class representing the generator class
     *
     * @api
     */
    public function dump(array $options = array()): string
    {
        $options = array_merge(array(
            'class' => 'ProjectUrlGenerator',
            'base_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
        ), $options);

        return <<<EOF
<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;

/**
 * {$options['class']}
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class {$options['class']} extends {$options['base_class']}
{
    private static \$declaredRoutes = {$this->generateDeclaredRoutes()};

    /**
     * Constructor.
     */
    public function __construct(RequestContext \$context, LoggerInterface \$logger = null)
    {
        \$this->context = \$context;
        \$this->logger = \$logger;
    }

{$this->generateGenerateMethod()}
}

EOF;
    }

    /**
     * Generates PHP code representing an array of defined routes
     * together with the routes properties (e.g. requirements).
     *
     * @return string PHP code
     */
    private function generateDeclaredRoutes(): string
    {
        $routes = "array(\n";
        foreach ($this->getRoutes()->all() as $name => $route) {
            $compiledRoute = $route->compile();

            $properties = array();
            $properties[] = $compiledRoute->getVariables();
            $properties[] = $route->getDefaults();
            $properties[] = $route->getRequirements();
            $properties[] = $compiledRoute->getTokens();
            $properties[] = $compiledRoute->getHostTokens();
            $properties[] = $route->getSchemes();

            $routes .= sprintf("        '%s' => %s,\n", $name, str_replace("\n", '', var_export($properties, true)));
        }
        $routes .= '    )';

        return $routes;
    }

    /**
     * Generates PHP code representing the `generate` method that implements the UrlGeneratorInterface.
     *
     * @return string PHP code
     */
    private function generateGenerateMethod(): string
    {
        return <<<EOF
    public function generate(\$name, \$parameters = array(), \$referenceType = self::ABSOLUTE_PATH)
    {
        if (!isset(self::\$declaredRoutes[\$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', \$name));
        }

        list(\$variables, \$defaults, \$requirements, \$tokens, \$hostTokens, \$requiredSchemes) = self::\$declaredRoutes[\$name];

        return \$this->doGenerate(\$variables, \$defaults, \$requirements, \$tokens, \$parameters, \$name, \$referenceType, \$hostTokens, \$requiredSchemes);
    }

    public function getDeclaredRouteData(\$name)
    {
        if (!isset(self::\$declaredRoutes[\$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', \$name));
        }

        return self::\$declaredRoutes[\$name];
    }
EOF;
    }
}
