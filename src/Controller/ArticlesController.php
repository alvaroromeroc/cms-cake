<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

use App\Controller\AppController; // PARECE que es necesario para el el FlashComponent

class ArticlesController extends AppController
{
	public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Paginator'); // Se puede incluir en cada metodo
        $this->loadComponent('Flash'); // Include the FlashComponent
    }

	public function index()
    {
        //$this->loadComponent('Paginator'); // se cambio a initialize
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    public function view($slug = null)
	{
	    $article = $this->Articles->findBySlug($slug)->firstOrFail();
	    $this->set(compact('article'));
	}

	public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Hardcoding the user_id is temporary, and will be removed later
            // when we build authentication out.
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }

    public function edit($slug)
	{
	    $article = $this->Articles->findBySlug($slug)->firstOrFail();
	    if ($this->request->is(['post', 'put'])) {
	        $this->Articles->patchEntity($article, $this->request->getData());
	        if ($this->Articles->save($article)) {
	            $this->Flash->success(__('Your article has been updated.'));
	            return $this->redirect(['action' => 'index']);
	        }
	        $this->Flash->error(__('Unable to update your article.'));
	    }

	    $this->set('article', $article);
	}

	public function delete($slug)
	{
	    $this->request->allowMethod(['post', 'delete']);

	    $article = $this->Articles->findBySlug($slug)->firstOrFail();
	    if ($this->Articles->delete($article)) {
	        $this->Flash->success(__('The {0} article has been deleted.', $article->title));
	        return $this->redirect(['action' => 'index']);
	    }
	}
}