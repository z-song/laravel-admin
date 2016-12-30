<?php

namespace Encore\Admin\Widgets;

use Encore\Admin\Facades\Admin;
use Illuminate\Contracts\Support\Renderable;

class Tab extends Widget implements Renderable
{
    /**
     * @var array
     */
    protected $attributes = [
        'id'         => '',
        'title'      => '',
        'tabs'       => [],
        'dropDown'   => [],
    ];

	protected $id;

	protected $script;

	public function __construct()
	{
		$this->attributes['id'] = $this->id = time() . mt_rand(0, 10000);
	}

	/**
     * Add a tab and its contents.
     *
     * @param string            $title
     * @param string|Renderable $content
     *
     * @return $this
     */
    public function add($title, $content)
    {
        $this->attributes['tabs'][] = [
            'id'      => time() . mt_rand(0, 10000),
            'title'   => $title,
            'content' => $content,
        ];

        return $this;
    }

    /**
     * Set title.
     *
     * @param string $title
     */
    public function title($title = '')
    {
        $this->attributes['title'] = $title;
    }

    /**
     * Set drop-down items.
     *
     * @param array $links
     *
     * @return $this
     */
    public function dropDown(array $links)
    {
        if (is_array($links[0])) {
            foreach ($links as $link) {
                call_user_func([$this, 'dropDown'], $link);
            }

            return $this;
        }

        $this->attributes['dropDown'][] = [
            'name' => $links[0],
            'href' => $links[1],
        ];

        return $this;
    }

    public function silmScroll($options = [])
    {
	    $options = json_encode($options);

	    $this->script = <<<EOT
$('#tab_content_{$this->id}').slimScroll({$options});
EOT;

    }

    /**
     * Render Tab.
     *
     * @return string
     */
    public function render()
    {
    	$js = $this->script ? '<script>'. $this->script .'</script>' : '';
        return view('admin::widgets.tab', $this->attributes)->render() . $js;
    }
}
