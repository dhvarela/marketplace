<?php
declare(strict_types=1);

namespace App\Marketplace\Domain\Model\Cart;


class Cart
{
    /** @var CartId $id */
    private $id;
    /** @var array $lines */
    private $lines;

    public function __construct(CartId $id)
    {
        $this->id = $id;
        $this->lines = array();
    }

    public static function init()
    {
        $id = CartId::random();

        return new static($id);
    }

    public function id(): CartId
    {
        return $this->id;
    }

    public function to1talProducts()
    {
        return 0;
    }
}