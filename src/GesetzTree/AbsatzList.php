<?php

namespace Caldera\GiiNormTools\GesetzTree;

class AbsatzList
{
    protected $items = [];

    public function __construct()
    {

    }

    public function addListItem(AbsatzListItem $item): AbsatzList
    {
        $this->items[] = $item;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}