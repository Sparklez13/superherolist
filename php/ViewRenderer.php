<?php
namespace SuperHeroList;

use Exception;

class ViewRenderer
{
    public function __construct(private $templateDir)
    {
        
    }

    public function render (string $view, array $vars = [])
    {
        if (!empty($vars)) {
            extract($vars);
        }

        $html = include("{$this->templateDir}/{$view}");

        if ($html === false) throw new Exception(self::class." Exception: Template file not found");
    }
}