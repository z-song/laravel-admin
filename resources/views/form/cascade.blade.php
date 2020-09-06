<script selector="{{ $selector }}" all="1">
    var operator_table = {
        '=': function(a, b) {
            if ($.isArray(a) && $.isArray(b)) {
                return $(a).not(b).length === 0 && $(b).not(a).length === 0;
            }

            return a == b;
        },
        '>': function(a, b) { return a > b; },
        '<': function(a, b) { return a < b; },
        '>=': function(a, b) { return a >= b; },
        '<=': function(a, b) { return a <= b; },
        '!=': function(a, b) {
            if ($.isArray(a) && $.isArray(b)) {
                return !($(a).not(b).length === 0 && $(b).not(a).length === 0);
            }

            return a != b;
        },
        'in': function(a, b) { return $.inArray(a, b) != -1; },
        'notIn': function(a, b) { return $.inArray(a, b) == -1; },
        'has': function(a, b) { return $.inArray(b, a) != -1; },
    };
    var cascade_groups = @json($cascadeGroups);

    // $.admin.initialize('{$this->getElementClassSelector()}', function () {
        $(this).on('{{ $event }}', function (e, state) {
            {!! $value !!}
            var self = $(this);
            cascade_groups.forEach(function (event) {
                var group = self.parents('.form-group').siblings('div.cascade-group.'+event.class);
                if(operator_table[event.operator](checked, event.value)) {
                    group.removeClass('d-none');
                } else {
                    group.addClass('d-none');
                }
            });
        })
    // });
</script>
