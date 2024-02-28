<?php

namespace MyProject\Controllers;

use InvalidArgumentException;
use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Models\Articles\Article;
use MyProject\View\View;
use MyProject\Models\Users;
use MyProject\Models\Users\UsersAuthService;
use MyProject\Controllers\MainController;

class ArticlesController extends AbstractController {

    public function view(int $articleId)
    {
       $article = Article::getById($articleId);
       
        if($article === null){
        throw new NotFoundException();
        }

       $this->view->renderHtml('articles/view.php', 
       ['article'=>$article ]);
    }

    public function edit(int $articleId)
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException ();
        }

        if ($this->user === null) {
            throw new UnauthorizedException ();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Для доступа к данной странице необходимы права админинстратора!');
        } 

        if(!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }

            header('Location:  /MyProject/www/articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function add(): void
    {

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Статьи могут добавлять только админинстраторы');
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }
    
        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }
    
            header('Location: /MyProject/www/articles/' . $article->getId(), true, 302);
            exit();
        }
    
        $this->view->renderHtml('articles/add.php');
    }
    public function delete(int $articleId)
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException ();
        }

        if ($this->user === null) {
            throw new UnauthorizedException ();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Для доступа к данной странице необходимы права админинстратора!');
        } 

        if(!empty($_POST)) {
            try {
                $article->deleteThisArticle($article);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/delete.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }

            header('Location: /MyProject/www/articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/delete.php', ['article' => $article->delete()]);
    }

    public function before(int $id)
    {
        $this->page(Article::getPageBefore($id, 5));
    }
    
    public function after(int $id)
    {
        $this->page(Article::getPageAfter($id, 5));
    }
    
    private function page(array $articles)
    {
        if ($articles === []) {
            throw new NotFoundException();
        }
    
        $firstID = $articles[0]->getId();
        $lastID = $articles[count($articles)-1]->getId();
    
        $this->view->renderHtml('main/main.php', [
            'articles' => $articles,
            'previousPageLink' => Article::hasPreviousPage($firstID) ? '/before/' . $firstID : null,
            'nextPageLink' => Article::hasNextPage($lastID) ? '/after/' . $lastID : null,
        ]);
    }




}

?>