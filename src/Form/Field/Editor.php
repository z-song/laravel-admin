<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Editor extends Field
{
    protected static $js = [
        '//cdn.ckeditor.com/ckeditor5/11.0.1/decoupled-document/ckeditor.js',
    ];

    public function render()
    {
        $this->script = "DecoupledEditor
        .create( document.querySelector( '#$this->column' ) )
        .then( editor => {
            const toolbarContainer = document.querySelector( '#toolbar-container' );

            toolbarContainer.appendChild( editor.ui.view.toolbar.element );
        } )
        .catch( error => {
            console.error( error );
        } );";

        return parent::render();
    }
}
