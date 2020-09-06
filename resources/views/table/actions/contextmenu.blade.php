@extends('admin::table.actions.dropdown')

@section('child')
<script>
    $("body").on("contextmenu", "table.table-table>tbody>tr", function (e) {
        $('#table-context-menu .dropdown-menu').hide();

        var menu = $(this).find('td.column-__actions__ .dropdown-menu');
        var index = $(this).index();

        if (menu.length) {
            menu.attr('index', index).detach().appendTo('#table-context-menu');
        } else {
            menu = $('#table-context-menu .dropdown-menu[index=' + index + ']');
        }

        var height = 0;

        if (menu.height() > (document.body.clientHeight - e.pageY)) {
            menu.css({left: e.pageX + 10, top: e.pageY - menu.height()}).show();
        } else {
            menu.css({left: e.pageX + 10, top: e.pageY - 10}).show();
        }

        menu.removeClass('dropdown-menu-right');

        return false;
    });

    $(document).on('click', function () {
        $('#table-context-menu .dropdown-menu').hide();
    });

    $('#table-context-menu').click('a', function () {
        $('#table-context-menu .dropdown-menu').hide();
    });
</script>

<style>
    .table-table .column-__actions__ {
        display: none !important;
    }
</style>

<template>
    <div id="table-context-menu"></div>
</template>

@endsection
