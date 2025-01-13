<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlfile'])) {
    $file = $_FILES['xmlfile'];

    if ($file['type'] !== 'text/xml') {
        echo "Please upload a valid XML file.";
        exit;
    }

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . 'xml_upload.xml';
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        try {
            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->load($filePath);
            
            $tagContent = $dom->getElementsByTagName('product');

            foreach ($tagContent as $value) {
                $tagCounts = [];
                convertXml($value, $tagCounts);
            }

            $xmlContent = $dom->saveXML();

            file_put_contents($filePath, $xmlContent);
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/xml');
            header('Content-Disposition: attachment; filename="edited_file.xml"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } catch (Exception $e) {
            var_dump($e->getMessage);
            die;
        }
    } else {
        echo "File upload failed.";
    }
}

function convertXml($element, &$tagCounts) {
    $children = [];

        foreach ($element->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $children[] = $child;
            }
        }

        foreach ($children as $child) {
            $tagName = $child->nodeName;

            if (!isset($tagCounts[$tagName])) {
                $tagCounts[$tagName] = 0;
            }

            $tagCounts[$tagName]++;

            if ($tagCounts[$tagName] > 1) {
                $suffix = $tagCounts[$tagName] - 1;
                $newTagName = "{$tagName}_{$suffix}";

                $newElement = $element->ownerDocument->createElement($newTagName);

                foreach ($child->attributes as $attr) {
                    $newElement->setAttribute($attr->nodeName, $attr->nodeValue);
                }

                while ($child->firstChild) {
                    $newElement->appendChild($child->firstChild);
                }

                $element->replaceChild($newElement, $child);

                $child = $newElement;
            }

            convertXml($child, $tagCounts);
        }
}