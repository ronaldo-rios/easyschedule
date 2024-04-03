<?php

namespace Core;

use Core\Config;
use Core\LoadPageLevels;
use App\adms\Models\helpers\SlugControllerOrMethod;

class ConfigController extends Config
{
    private ?string $url;
    private array $urlArray;
    private string $urlController;
    private string $urlMethod;
    private string $urlParameter;
    private array $format;

    public function __construct()
    {
        $this->configAdm();
        
        if (! empty(filter_input(INPUT_GET, 'url', FILTER_DEFAULT))) {
            
            $this->url = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
            $this->clearUrl();
            $this->urlArray = explode('/', $this->url);
            
            isset($this->urlArray[0]) 
                ? $this->urlController = SlugControllerOrMethod::slugController($this->urlArray[0])
                : $this->urlController = SlugControllerOrMethod::slugController(CONTROLLER);
    
            isset($this->urlArray[1]) 
                ? $this->urlMethod = SlugControllerOrMethod::slugMethod($this->urlArray[1])
                : $this->urlMethod = SlugControllerOrMethod::slugMethod(METHOD);

            isset($this->urlArray[2]) 
                ? $this->urlParameter = $this->urlArray[2]
                : $this->urlParameter = '';
        }
        else {
            $this->urlController = SlugControllerOrMethod::slugController(CONTROLLER_ERROR);
            $this->urlMethod = SlugControllerOrMethod::slugMethod(METHOD);
            $this->urlParameter = '';
        }
    }

    public function loadPage(): void
    {
        LoadPageLevels::load($this->urlController, $this->urlMethod, $this->urlParameter);
    }

    /**
     * This method is responsible for cleaning the URL:
     * @return void
     */
    private function clearUrl(): void
    {
        $this->url = strip_tags($this->url);
        $this->url = trim($this->url);
        $this->url = rtrim($this->url, '/');
        $this->format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]?;:.,\\\'<>°ºª ';
        $this->format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr------------------------------------
        -------------------------------------------------------------';
        $this->url = strtr(
            mb_convert_encoding($this->url, 'ISO-8859-1', 'UTF-8'), 
            mb_convert_encoding($this->format['a'], 'ISO-8859-1', 'UTF-8'), 
            $this->format['b']
        );
    }

}
