<?php

namespace App\Users\Domain\Dto\Response\Transformer;

interface ResponseDtoTransformerInterface
{
    public function transformFromObject($object);
    public function transformFromObjects(iterable $objects): iterable;
}
