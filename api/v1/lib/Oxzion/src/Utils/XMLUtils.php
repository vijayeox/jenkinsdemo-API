<?php
namespace Oxzion\Utils;

class XMLUtils
{
    public static function xmlToArray($file)
    {
        return $xmlArray = json_decode(json_encode(simplexml_load_string($file)), true);
    }

    public static function domToArray($root)
    {
        $result = array();
        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $i => $attr) {
                $result[$attr->name] = $attr->value;
            }
        }
        $children = $root->childNodes;
        if ($children) {
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    if (count($result) == 1) {
                        return $result['_value'];
                    } else {
                        return $result;
                    }
                }
            }
        }
        $group = array();
        if (isset($children->length)) {
            for ($i = 0; $i < $children->length; $i++) {
                $child = $children->item($i);
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = self::domToArray($child);
                } else {
                    if (!isset($group[$child->nodeName])) {
                        $tmp = $result[$child->nodeName];
                        $result[$child->nodeName] = array($tmp);
                        $group[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = self::domToArray($child);
                }
            }
        }
        return $result;
    }
}
