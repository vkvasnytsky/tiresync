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
?>
<!DOCTYPE html>
<html>
<head>
    <title>TireSYNC REST API</title>
</head>
<script>
    var $ = function(id) { return document.getElementById(id); };
    var xhr = new XMLHttpRequest();

    function onError(e) {
        console.log('Error', e);
    }

    function getYears() {
        xhr.open('GET', 'api.php?do=years');
        xhr.onload = function(e) {
            clearOptions(['makes', 'models', 'options']);
            addOptions("years", JSON.parse(this.response));
        }
        xhr.send();
    }

    function getMakes(year) {
        xhr.open('GET', 'api.php?do=makes&year='+year);
        xhr.onload = function(e) {
            clearOptions(['makes', 'models', 'options']);
            addOptions("makes", JSON.parse(this.response));
        }
        xhr.send();
    }

    function getModels(year, make) {
        xhr.open('GET', 'api.php?do=models&year='+year+'&make='+make);
        xhr.onload = function(e) {
            clearOptions(['models', 'options']);
            addOptions("models", JSON.parse(this.response));
        }
        xhr.send();
    }

    function getOptions(year, make, model) {
        xhr.open('GET', 'api.php?do=options&year='+year+'&make='+make+'&model='+model);
        xhr.onload = function(e) {
            clearOptions(['options']);
            addOptions("options", JSON.parse(this.response));
        }
        xhr.send();
    }

    function getFitments(year, make, model, option) {
        xhr.open('GET', 'api.php?do=fitments&year='+year+'&make='+make+'&model='+model+'&option='+option);
        xhr.onload = function(e) {
            var fitments = JSON.parse(this.response);

            $('content').innerText = '';

            fitments.forEach(function(entry) {
                $('content').innerText += JSON.stringify(entry, null, 4);
            });
        }
        xhr.send();
    }

    function addOptions(el, options) {
        options.forEach(function(entry) {
            var option = document.createElement("option");

            option.text = entry;
            option.value = entry;

            $(el).appendChild(option);
        });
    }

    function clearOptions(elements) {
        elements.forEach(function(entry) {
            if ($(entry).options.length > 1) {
                $(entry).options.length = 1;
            }
        });
    }
</script>
<body onload="getYears()">
<form>
    <dl>
        <dt>Year:</dt>
        <dd>
            <select name="years" id=years onchange="getMakes(this.value)">
                <option>- Select One -</option>
            </select>
        </dd>
        <dt>Make:</dt>
        <dd>
            <select name="makes" id=makes onchange="getModels($('years').value, this.value)">
                <option>- Select One -</option>
            </select>
        </dd>
        <dt>Model:</dt>
        <dd>
            <select name="models" id=models onchange="getOptions($('years').value, $('makes').value, this.value)">
                <option>- Select One -</option>
            </select>
        </dd>
        <dt>Options:</dt>
        <dd>
            <select name="options" id=options onchange="getFitments($('years').value, $('makes').value, $('models').value, this.value)">
                <option>- Select One -</option>
            </select>
        </dd>
    </dl>

    <div id=content></div>
</form>
</body>
</html>