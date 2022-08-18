<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\Api\Object\Property;

interface PurchaseProperties extends TrackableObjectProperties
{
    public const APP_ID = 'app_id';
    public const PRODUCT_ID = 'product_id';
    public const CURRENCY = 'currency';
    public const PRICE = 'price';
    public const QUANTITY = 'quantity';
    public const TIME = 'time';
    public const PROPERTIES = 'properties';
}
