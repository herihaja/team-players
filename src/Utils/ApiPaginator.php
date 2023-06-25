<?php

namespace App\Utils;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Validator\Constraints\Callback;

class ApiPaginator extends Paginator 
{
    /**
     * @param QueryBuilder|Query $query
     */
    public function paginate($query, int $page = 1, $addLink=null): Paginator
    {
        $query->setHint(Query::HINT_INCLUDE_META_COLUMNS, true)
            ->setHydrationMode(Query::HYDRATE_ARRAY);

        parent::paginate($query, $page);
        $items = [];
        foreach($this->getItems() as $item){
            if ($addLink)
                $item = call_user_func($addLink, $item);
            $items[] = $item;
        }
        $this->items = $items;
        
        return $this;
    }

    /**
     * Transform items into array
     * Use serializer later
     * @return array<mixed>
     */
    private function transformItems(): array
    {
        $items = [];
        foreach($this->getItems() as $item) {
            $attributes = get_object_vars($item);
            $element = [];
            foreach($attributes as $attr) {
                $element[$attr] = getAttr($item, $attr);
            }
            $items[] = $element;
        }

        return $items;
    }
}
