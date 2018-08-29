<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text; // para pasar el titulo a slug
use Cake\Validation\Validator; // Para el metodo de validación 

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags'); // Add this line
    }

    public function beforeSave($event, $entity, $options)
	{
	    if ($entity->isNew() && !$entity->slug) {
	        $sluggedTitle = Text::slug($entity->title);
	        // trim slug to maximum length defined in schema
	        $entity->slug = substr($sluggedTitle, 0, 191);
	    }
	}

	public function validationDefault(Validator $validator)
	{
	    $validator
	        ->notEmpty('title')
	        ->minLength('title', 10)
	        ->maxLength('title', 255)
	        ->notEmpty('body')
	        ->minLength('body', 10);

	    return $validator;
	}
}