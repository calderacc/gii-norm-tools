<?php

namespace Caldera\GiiNormTools\GesetzTree;

class ItemList
{
    protected $items = [];

    public function __construct()
    {

    }

    public function addListItem(AbsatzListItem $item): ItemList
    {
        $this->items[] = $item;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}