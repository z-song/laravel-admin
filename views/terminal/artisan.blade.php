<script>
$(function () {

    var storageKey = function () {
        var connection = $('#connections').val();
        return 'la-'+connection+'-history'
    };

    $('#terminal-box').slimScroll({
        height: $('#pjax-container').height() - 247 +'px'
    });

    function History () {
        this.index = this.count() - 1;
    }

    History.prototype.store = function () {
        var history = localStorage.getItem(storageKey());
        if (!history) {
            history = [];
        } else {
            history = JSON.parse(history);
        }
        return history;
    };

    History.prototype.push = function (record) {
        var history = this.store();
        history.push(record);
        localStorage.setItem(storageKey(), JSON.stringify(history));

        this.index = this.count() - 1;
    };

    History.prototype.count = function () {
        return this.store().length;
    };

    History.prototype.up = function () {
        if (this.index > 0) {
            this.index--;
        }

        return this.store()[this.index];
    };

    History.prototype.down = function () {
        if (this.index < this.count() - 1) {
            this.index++;
        }

        return this.store()[this.index];
    };

    History.prototype.clear = function () {
        localStorage.removeItem(storageKey());
    };

    var history = new History;

    var send = function () {

        var $input = $('#terminal-query');

        $.ajax({
            url:location.pathname,
            method: 'post',
            data: {
                c: $input.val(),
                _token: LA.token
            },
            success: function (response) {

                history.push($input.val());

                $('#terminal-box')
                    .append('<div class="item"><small class="label label-default"> > artisan '+$input.val()+'<\/small><\/div>')
                    .append('<div class="item">'+response+'<\/div>')
                    .slimScroll({ scrollTo: $("#terminal-box")[0].scrollHeight });

                $input.val('');
            }
        });
    };

    $('#terminal-query').on('keyup', function (e) {

        e.preventDefault();

        if (e.keyCode == 13) {
            send();
        }

        if (e.keyCode == 38) {
            $(this).val(history.up());
        }

        if (e.keyCode == 40) {
            $(this).val(history.down());
        }
    });

    $('#terminal-clear').click(function () {
        $('#terminal-box').text('');
        //history.clear();
    });

    $('.loaded-command').click(function () {
        $('#terminal-query').val($(this).html() + ' ');
        $('#terminal-query').focus();
    });

    $('#terminal-send').click(function () {
        send();
    });

});
</script>
<!-- Chat box -->
<div class="box box-primary">
    <div class="box-header with-border">
        <i class="fa fa-terminal"></i>
    </div>
    <div class="box-body chat" id="terminal-box">
        <!-- chat item -->

        <!-- /.item -->
    </div>
    <!-- /.chat -->
    <div class="box-footer with-border">

        <div style="margin-bottom: 10px;">

            @foreach($commands['groups'] as $group => $command)
            <div class="btn-group dropup">
                <button type="button" class="btn btn-default btn-flat">{{ $group }}</button>
                <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @foreach($command as $item)
                    <li><a href="#" class="loaded-command">{{$item}}</a></li>
                    @endforeach
                </ul>
            </div>
            @endforeach

            <div class="btn-group dropup">
                <button type="button" class="btn btn-twitter btn-flat">Other</button>
                <button type="button" class="btn btn-twitter btn-flat dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @foreach($commands['others'] as $item)
                    <li><a href="#" class="loaded-command">{{$item}}</a></li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="btn btn-success" id="terminal-send"><i class="fa fa-paper-plane"></i> send</button>

            <button type="button" class="btn btn-warning" id="terminal-clear"><i class="fa fa-refresh"></i> clear</button>
        </div>

        <div class="input-group">
            <span class="input-group-addon" style="font-size: 18px; line-height: 1.3333333;">artisan</span>
            <input class="form-control input-lg" id="terminal-query" placeholder="command" style="border-left: 0px;padding-left: 0px;">
        </div>
    </div>
</div>
<!-- /.box (chat box) -->