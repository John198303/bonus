<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var CBitrixComponent $component */

CJSCore::Init(array("date", "tasks_util_query", "tasks_util_template"));
$i = 1;

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Document</title>
</head>
<body>


<div id="disney" class="tabcontent">

    <div>
        <table class="table table-hover" id="my-table" style="width: 100%; margin: 0 auto">
            <thead>
            <tr>
                <th class="task-time-date-column">#</th>
                <th class="task-time-date-column">Программа</th>
                <th class="task-time-date-column">канал</th>


            </tr>
            </thead>
            <tbody>
            <? foreach ($arResult as $item): ?>
                <tr>
                    <td ><?= $i++;?></td>

                    <td><?=$item['VALUE'] ?></td>
                    <td >disney</td>

                </tr>
            <?endforeach; ?>
            </tbody>
        </table>
    </div>
    <div >
        <button id='add' class="task-dashed-link-inner" for-table='#my-table'>Добавить программу</button>
    </div>
</div>
<script>
    $('#my-table').Tabledit({

        url: 'add_value_to_field.php',
        deleteButton: true,

        columns: {
            identifier: [0, 'id'],
            editable: [[1, 'col1'],[2, 'ch']]
        },
        onSuccess: function(data, textStatus, jqXHR) {
            console.log('onSuccess(data, textStatus, jqXHR)');
            console.log(data);
            let ad = data;
            alert(data.id);
            alert(data.username);
            console.log(ad);
            console.log(textStatus);
            console.log(jqXHR);
        },
        onFail: function(jqXHR, textStatus, errorThrown) {
            console.log('onFail(jqXHR, textStatus, errorThrown)');
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        onAlways: function() {
            console.log('onAlways()');
        },
        onAjax: function(action, serialize) {
            console.log('onAjax(action, serialize)');
            console.log(action);
            console.log(serialize);
        }
    });

    $("#add").click(function(e){

        var table = $(this).attr('for-table');  //get the target table selector
        var $tr = $(table + ">tbody>tr:last-child").clone(true, true);  //clone the last row
        var nextID = 0; //get the ID and add one.
        $tr.find("input.tabledit-identifier").val(nextID);  //set the row identifier
        $tr.find("span.tabledit-identifier").text(nextID);  //set the row identifier
        $(table + ">tbody").append($tr);    //add the row to the table
        $tr.find(".tabledit-edit-button").click();  //pretend to click the edit button
        $tr.find("input:not([type=hidden]), select").val("");   //wipe out the inputs.
    });
</script>
</body>