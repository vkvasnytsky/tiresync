<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Vyacheslav Kvasnitsky
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * @const string API_BASE TireSYNC REST API URL among free access key and base64
 * encoded localhost as a domain name
 */
define('API_BASE', 'http://api.tiresync.com/v1/oe/%s/1111-1111-1111-1111/bG9jYWxob3N0/');

/**
 * Get API data
 *
 * @param string $action Action
 * @param string $url Additional parameters in query
 *
 * @return string
 */
function getUrl($action, $url = '')
{
    $ch = curl_init(sprintf(API_BASE, $action) . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);

    return json_decode($json);
}

/**
 * Get Available Years
 *
 * @return string
 */
function getYears()
{
    $object = getUrl('years');

    return $object->items;
}

/**
 * Get Available Makes
 *
 * @param $year
 * @return string
 */
function getMakes($year)
{
    $object = getUrl('makes', base64_encode($year));

    return $object->items;
}

/**
 * Get Available Models
 *
 * @param $year
 * @param $make
 * @return string
 */
function getModels($year, $make)
{
    $object = getUrl('models', implode('/', array_map('base64_encode', array($year, $make))));

    return $object->items;
}

/**
 * Get Available Options
 *
 * @param $year
 * @param $make
 * @param $model
 * @return string
 */
function getOptions($year, $make, $model)
{
    $object = getUrl('options', implode('/', array_map('base64_encode', array($year, $make, $model))));

    return $object->items;
}

/**
 * Get Vehicle Fitments
 *
 * @param $year
 * @param $make
 * @param $model
 * @param $option
 * @return string
 */
function getFitments($year, $make, $model, $option)
{
    $object = getUrl('vehicle_fitments', implode('/', array_map('base64_encode', array($year, $make, $model, $option))));

    return $object->items;
}

// process parameters and return result in JSON format
if (array_key_exists('do', $_GET) && in_array($_GET['do'], array('years', 'makes', 'models', 'options', 'fitments'))) {
    header('Content-Type: application/json');

    switch ($_GET['do']) {
        case 'years':
            echo json_encode(getYears());
            break;
        case 'makes':
            echo json_encode(getMakes((int)$_GET['year']));
            break;
        case 'models':
            echo json_encode(getModels((int)$_GET['year'], $_GET['make']));
            break;
        case 'options':
            echo json_encode(getOptions((int)$_GET['year'], $_GET['make'], $_GET['model']));
            break;
        case 'fitments':
            echo json_encode(getFitments((int)$_GET['year'], $_GET['make'], $_GET['model'], $_GET['option']));
            break;
    }
}