<?php

namespace App\Support\API\Bitrix;

enum BitrixMethod: string
{
    // Lead methods
    case createLead = 'crm.lead.add.json';

    // Products methods
    case productGet = 'crm.product.get.json';
    case productFields = 'crm.product.fields.json';
    case productList = 'crm.product.list.json';

    // Sections
    case sectionGet = 'crm.productsection.get.json';
    case sectionList = 'crm.productsection.list.json';
    case sectionFields = 'crm.productsection.fields.json';
}
