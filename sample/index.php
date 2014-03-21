<?php

require_once 'common.php';

class Foo extends \TE\Mvc\Controller\AbstractController
{
    public function execute()
    {
        $define = array(
            'parameter-binding.php'     =>  '?say=' . urlencode('Hello World!')
        );

        $samples = array_map('basename', glob('./*.php'));
        $html = '';
        foreach ($samples as $sample) {
            if (in_array($sample, array('common.php', 'index.php'))) {
                continue;
            }

            list ($word) = explode('.', $sample, 2);
            $word = ucwords(str_replace('-', ' ', $word));
            $suffix = isset($define[$sample]) ? $define[$sample] : '';
            $html .= "<li><a href=\"./{$sample}{$suffix}\">{$word}</a> (<a href=\"?source={$sample}\">{$sample}</a>)</li>";
        }


        return array('content', '<h1>Samples list</h1><ul>' . $html . '</ul>');
    }

    public function source($source)
    {
        highlight_file($source);
    }
}

new \TE\Mvc\Server(array(
    '/?source'      =>      'Foo#source',
    '/'             =>      'Foo'
));

