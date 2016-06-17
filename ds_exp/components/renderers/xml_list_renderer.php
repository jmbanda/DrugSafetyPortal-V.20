<?php

include_once dirname(__FILE__) . '/abstract_xml_renderer.php';

class XmlListRenderer extends AbstractXmlRenderer
{
    protected function getGridPagePart()
    {
        return PagePart::Grid;
    }

    protected function getCurrentRowData($grid)
    {
        return null;
    }
}
