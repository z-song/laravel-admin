<div {!! admin_attrs($group_attrs) !!}>
    <label for="@id" class="{{$viewClass['label']}} col-form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}" id="@id">

        <div class="treejs"></div>

        <input type="hidden" class="{{ $class }}" name="{{$name}}" value="@json($value)">
        @include('admin::form.error')
        @include('admin::form.help-block')
    </div>
</div>

<script require="treejs" @script>
    var $input = $(this);
    var tree = new Tree('.treejs', {
        data: @json($options),
        closeDepth: {{ $closeDepth ?? 3 }},
        loaded: function () {
            this.values = @json($checked);
        },
        onChange: function (value) {
            var val = this.selectedNodes.map(function (i) {
                return i.id;
            });
            $input.val(val);
        }
    })
</script>

<style>

.treejs {
    font-size: 0.875rem !important;
    font-weight: 700;
}

.treejs .treejs-node__halfchecked > .treejs-checkbox:before {
    background-color: #17a2b8;
    border-color: #17a2b8;
    width: 22px;
    height: 22px;
    display: inline-block;
}

.treejs .treejs-node__halfchecked > .treejs-checkbox:after {
    top: 12px;
    left: 6px;
    width: 10px;
    height: 2px;
}

.treejs .treejs-node__checked > .treejs-checkbox:before {
    background-color: #17a2b8;
    border-color: #17a2b8;
    display: inline-block;
}

.treejs .treejs-node__checked > .treejs-checkbox:after {
    top: 1px;
    left: 0;
    width: 7px;
    height: 10px;
    border: solid 2px #fff;
    border-left: none;
    border-top: none;
    transform: translate(7.75px, 4.5px) rotate(45deg);
    -ms-transform: translate(7.75px, 4.5px) rotate(45deg);
}

.treejs .treejs-checkbox:hover:before {
    border: 1px solid #17a2b8;
}

.treejs .treejs-checkbox:before {
    width: 22px;
    height: 22px;
    border: 1px solid #D3CFC8;
    border-radius: 0;
}

.treejs .treejs-node {
    margin-top: 3px;
    margin-bottom: 3px;
    padding: 2px;
}

.treejs .treejs-label {
    vertical-align: middle;
    margin-left: 10px;
    margin-top: 5px;
    display: inline-block;
}

.treejs .treejs-switcher {
    margin-top: 5px;
}
</style>
