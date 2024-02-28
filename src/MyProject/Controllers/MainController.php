<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\NotFoundException;
use MyProject\Models\Articles\Article;
use MyProject\Controllers\ArticlesController;


class MainController extends AbstractController
{
     public function main()
     {
        $this->page(1);
     }

    public function page(int $pageNum)
    {
        $pagesCount = Article::getPagesCount(5);
        $this->view->renderHtml('main/main.php', [
            'articles' => Article::getPage($pageNum, 5),
            'previousPageLink' => $pageNum > 1
                ? '/MyProject/www/' . ($pageNum - 1)
                : null,
            'nextPageLink' => $pageNum < $pagesCount
                ? '/MyProject/www/' . ($pageNum + 1)
                : null
        ]);
    }

   
}