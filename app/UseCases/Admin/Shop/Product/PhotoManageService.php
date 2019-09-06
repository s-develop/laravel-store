<?php

namespace App\UseCases\Admin\Shop\Product;

use App\Command\CommandBus;
use App\Entity\Shop\Product\Product;
use App\Query\QueryBus;
use App\Entity\Shop\Product\Photo;
use App\Http\Requests\Admin\Shop\Product\Photo\CreateRequest;
use App\Command\Admin\Shop\Product\Photo\Create\Command as CreatePhotosCommand;
use App\Command\Admin\Shop\Product\Photo\Remove\Command as RemovePhotoCommand;
use App\Command\Admin\Shop\Product\Photo\RemoveAll\Command as RemoveAllPhotosCommand;
use App\Query\Shop\Product\Photo\FindPhotosByProductIdQuery;

class PhotoManageService
{
    private $commandBus;
    private $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function create(CreateRequest $request, Product $product): void
    {
        $this->commandBus->handle(new CreatePhotosCommand($request, $product));
    }

    public function remove(Photo $photo): void
    {
        $this->commandBus->handle(new RemovePhotoCommand($photo));
    }

    public function removeAll(Product $product): void
    {
        $photos = $this->queryBus->query(new FindPhotosByProductIdQuery($product->id));
        $this->commandBus->handle(new RemoveAllPhotosCommand($photos));
    }
}