<span class="tree-column-wrap" data-key='{{ $key }}' data-parent="{{ $parent }}">
    {!!  str_repeat('<span></span>', $space)  !!}
    @if($has_children)
        <a href="#" class="mr-2 column-tree-expand"><i class="fas fa-chevron-down"></i></a>
    @endif
    {!! $value !!}
</span>

<script>
    $('.column-tree-expand').click(function () {
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-right');
        var key = $(this).parents('.tree-column-wrap').data('key');
        var $tr = $('.tree-column-wrap[data-parent='+key+']').parents('tr');
        $tr.toggleClass('d-none').find('.column-tree-expand').trigger('click');
    });
</script>

<style>
    .table-table .tree-column-wrap span {
        width: 30px;
        display: inline-block;
    }
</style>
