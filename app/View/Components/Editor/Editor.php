<?php

namespace App\View\Components\Editor;

use Closure;
//use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use mysql_xdevapi\Collection;

class Editor extends Component
{
    public $data = [];

    /**
     * Create a new component instance.
     */
    public function __construct(string $data = null)
    {
        $this->data = $this->renderBocks(json_decode($data)->blocks ?? []);
    }

    public function renderBocks(array $data = null)
    {
        if($data != null)
        {
            $renderedBlocks = [];

            foreach ($data as $block) {

                $viewName = "components.editor.blocks." . $block->type;

                if (!View::exists($viewName)) {
                    $viewName = 'components.editor.blocks.not-found';
                }

                $renderedBlocks[] = View::make($viewName, ['data' => $block->data, 'tunes' => isset($block->tunes->itemProp->value) ? $block->tunes : null])->render();
            }

            return implode($renderedBlocks);
        } else {
            return null;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.editor.editor', ['text' => $this->data]);
    }
}
