<?php

class CardView extends GenericComponentView
{
    public function __construct($content = '', $width = 'w-25', $height = 'h-50')
    {
        $this->affichage .= <<<HTML
            <div class="$width $height shadow p-3 mb-5 bg-white rounded">
                $content
            </div>
        HTML;
    }
}
