@extends('admin::grid.actions.dropdown')

@section('child')
<script>
    $("body").on("contextmenu", "table.grid-table>tbody>tr", function (e) {
        $('#grid-context-menu .dropdown-menu').hide();

        var menu = $(this).find('td.column-__actions__ .dropdown-menu');
        var index = $(this).index();

        if (menu.length) {
            menu.attr('index', index).detach().appendTo('#grid-context-menu');
        } else {
            menu = $('#grid-context-menu .dropdown-menu[index=' + index + ']');
        }

        var height = 0;

        if (menu.height() > (document.body.clientHeight - e.pageY)) {
            menu.css({left: e.pageX + 10, top: e.pageY - menu.height()}).show();
        } else {
            menu.css({left: e.pageX + 10, top: e.pageY - 10}).show();
        }

        return false;
    });

    $(document).on('click', function () {
        $('#grid-context-menu .dropdown-menu').hide();
    });

    $('#grid-context-menu').click('a', function () {
        $('#grid-context-menu .dropdown-menu').hide();
    });
</script>

<style>
    .grid-table .column-__actions__ {
        display: none !important;
    }
</style>

<template>
    <div id="grid-context-menu"></div>
</template>

@endsection
