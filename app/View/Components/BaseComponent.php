<?php

namespace Roxayl\MondeGC\View\Components;

use Illuminate\View\Component;

abstract class BaseComponent extends Component
{
    /**
     * Obtient les attributes HTML sur un élément déclencheur.
     *
     * @param string $url
     * @param string $target
     * @return string Contenu HTML donnant les attributs pour un élément HTML déclencheur.
     */
    public static function getTargetHtmlAttributes(string $url, string $target): string
    {
        $data = [
            'data-component-url'    => $url,
            'data-component-target' => $target,
        ];

        $html = '';
        foreach($data as $key => $value) {
            $html .= ' ' . e($key) . '="' . e($value) . '"';
        }

        return $html;
    }
}
