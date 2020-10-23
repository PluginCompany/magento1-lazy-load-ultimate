<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */

$this->startSetup();
$this->run("DROP TABLE IF EXISTS `{$this->getTable('plugincompany_lazyload/remoteimage')}`");
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_lazyload/remoteimage'))
    ->addColumn(
        'image_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'ID'
    )
    ->addColumn(
        'url',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array(),
        'URL'
    )
    ->addColumn(
        'width',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        10,
        array(),
        'Width'
    )
    ->addColumn(
        'height',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        10,
        array(),
        'Height'
    )
    ->setComment('Remote Image Dimension Table');

$this->getConnection()->createTable($table);
$this->endSetup();
