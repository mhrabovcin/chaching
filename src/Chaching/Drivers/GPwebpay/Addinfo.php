<?php

namespace Chaching\Drivers\GPwebpay;

use SimpleXMLElement;

/**
 * Addinfo represents AdditionalInfoRequest that allows to pass additional
 * transaction data to GP Webpay. The additional data may be required for
 * better transaction risk analysis.
 *
 * @see https://www.globalpayments.sk/sk-sk/blog/2021/01/26/psd2-povinnost-zmeny-addinfo
 */
class Addinfo {

    protected SimpleXMLElement $xml;

    public function __construct($version = '1.0')
    {
        $root = sprintf('<additionalInfoRequest version="%s" />', $version);
        $this->xml = new SimpleXMLElement($root);
    }

    /**
     * Add custom data to the AdditionalInfoRequest
     */
    public function set(array $path, $value)
    {
        if (count($path) == 0)
        {
            throw new \Exception("path must be defined");
        }

        $last_element = array_pop($path);

        /** @var SimpleXMLElement $element */
        $element = $this->xml;
        foreach ($path as $part)
        {
            if (!isset($element->{$part}))
            {
                $element->addChild($part);
            }
            $element = $element->{$part};
        }

        $element->addChild($last_element, $value);
    }

    /**
     * Serializes element to XML request.
     */
    public function asXML() {
        return $this->xml->asXML();
    }
}
