<?php
namespace Nutwerk\Extension;

use Silex\Application;
use Silex\ExtensionInterface;

class SolrExtension implements ExtensionInterface
{
    public function register(Application $app)
    {
        if (!isset($app['solr.class_path'])) {
            throw new \InvalidArgumentException('No "solr.class_path" given');
        }
        $app['autoloader']->registerPrefix('Apache_', $app['solr.class_path']);
        set_include_path($app['solr.class_path'] . PATH_SEPARATOR . get_include_path());
        
        $app['solr'] = $app->share(function () use ($app) {
            $host = isset($app['solr.host']) ? $app['solr.host'] : 'localhost';
            $port = isset($app['solr.port']) ? $app['solr.port'] : 8180;
            $path = isset($app['solr.path']) ? $app['solr.path'] : '/solr/';
            
            return new \Apache_Solr_Service($host, $port, $path);
        });
    }
}