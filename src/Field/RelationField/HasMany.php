<?php

namespace Encore\Admin\Field\RelationField;

use Encore\Admin\Admin;
use Encore\Admin\Field\RelationField;
use Encore\Admin\Field\Group;
use Illuminate\Database\Eloquent\Relations\HasMany as Relation;


/**
 * Class HasMany.
 */
class HasMany extends RelationField
{
	/**
	 * build relation field
	 *
	 * @param $data
	 */
	public function build($data = null)
	{
		$this->buildGroups($data);
		$this->buildTemplate();
	}

	/**
	 * Build a Nested form template for dynamically add sub form .
	 *
	 * @return string
	 */
	public function buildTemplate()
	{
		$this->template = $this->buildGroup($this->relationName, $this->builder, 'new_' . Group::DEFAULT_KEY_NAME);

		Admin::script(
			$this->getTemplateScript(
				$this->template->getFormScript()
			)
		);

		return $this->template;
	}



	public function buildGroups( $relationData = null )
	{
		$relationData = $relationData ? : $this->value;

		$model = $this->owner->model();

		$relation = call_user_func([$model, $this->relationName]);

		if (!$relation instanceof Relation) {
			throw new \Exception('hasMany field must be a HasMany relation.');
		}

		$this->groups = [];

		if ($datas = $this->getDataInFlash()) {
			foreach ($datas as $key => $data) {
				if ($data[Group::REMOVE_FLAG_NAME] == 1) {
					continue;
				}
				//{$this->relationName}.{$this->key}.{$column}
				$data = [$this->relationName => [ $key => $data]];

				$this->groups[$key] = $this->buildGroup($this->relationName, $this->builder, $key)->fill([$data]);
			}
		}else if( !empty($this->groups) ){
			return $this->groups;
		}

		foreach ($relationData as $key => $data) {
			$relationKey = array_get($data, $relation->getRelated()->getKeyName());
			$relationKey = $relationKey ? :$key;

			$data = [$this->relationName => [ $relationKey => $data]];

			$this->groups[$relationKey] = $this->buildGroup($this->relationName, $this->builder, $relationKey)->fill($data);
		}

		return $this->groups;
	}

	/**
	 * Get form data flashed in session.
	 *
	 * @return mixed
	 */
	protected function getDataInFlash()
	{
		return old($this->column);
	}

	/**
	 * Build fields group
	 *
	 * @param string $relationName
	 * @param \Closure$builder
	 *
	 * @return NestedForm2
	 */
	protected function buildGroup($relationName, \Closure $builder, $key)
	{
		$group = new Group($relationName, $key);

		call_user_func($builder, $group);

		$pk = $this->owner->model()->$relationName()->getRelated()->getKeyName();

		$group->hidden($pk);

		$group->hidden(Group::REMOVE_FLAG_NAME)->default(0)->attribute(['class' => Group::REMOVE_FLAG_CLASS]);

		return $group;
	}





	public function fields()
	{
		$fields = [];
		foreach($this->groups as $group){
			$fields = array_merge($fields, $group->fields());
		}

		return $fields;
	}

	/**
	 * Get validator for this field.
	 *
	 * @param array $input
	 *
	 * @return bool|Validator
	 */
	public function getValidator(array $input)
	{
		$validators = [];
		$groups = $this->buildGroups($input[$this->relationName]);
		foreach($groups as $group){
			$validators = array_merge($validators, $group->getValidator($input));
		}

		return array_filter($validators);
	}




	protected function getTemplateScript($templateScript)
	{
		$removeClass = Group::REMOVE_FLAG_CLASS;
		$defaultKey = Group::DEFAULT_KEY_NAME;

		return <<<EOT
    $('#has-many-{$this->column} > .nav').on('click', 'i.close-tab', function(){
        var \$navTab = $(this).siblings('a');
        var \$pane = $(\$navTab.attr('href'));
        if( \$pane.hasClass('new') ){
            \$pane.remove();
        }else{
            \$pane.removeClass('active').find('.$removeClass').val(1);
        }
        \$navTab.closest('li').remove();
        $('#has-many-{$this->column} > .nav > li:first-child > a').tab('show');
    });

    $('#has-many-{$this->column} > .nav > li.nav-tools').on('click', '.add', function(){
        var index = $('#has-many-{$this->column} > .nav > li').size();
        var navTabHtml = $('#has-many-{$this->column} > template.nav-tab-tpl').html().replace(/{$defaultKey}/g, ''+index+'');
        var paneHtml = $('#has-many-{$this->column} > template.pane-tpl').html().replace(/{$defaultKey}/g, ''+index+'');
        $('#has-many-{$this->column} > .nav').append(navTabHtml);
        $('#has-many-{$this->column} > .tab-content').append(paneHtml);
        $('#has-many-{$this->column} > .nav > li:last-child a').tab('show');
        {$templateScript}

    });
EOT;
	}



	/**
	 * Render the `HasMany` field.
	 *
	 * @throws \Exception
	 *
	 * @return $this
	 */
	public function render()
	{
		return parent::render()->with([
			'groups'     => $this->groups,
			'template'  => $this->template,
		]);
	}


}
