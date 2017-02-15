<script> Sfdump = window.Sfdump || (function (doc) { var refStyle = doc.createElement('style'), rxEsc = /([.*+?^${}()|\[\]\/\\])/g, idRx = /\bsf-dump-\d+-ref[012]\w+\b/, keyHint = 0 <= navigator.platform.toUpperCase().indexOf('MAC') ? 'Cmd' : 'Ctrl', addEventListener = function (e, n, cb) { e.addEventListener(n, cb, false); }; (doc.documentElement.firstElementChild || doc.documentElement.children[0]).appendChild(refStyle); if (!doc.addEventListener) { addEventListener = function (element, eventName, callback) { element.attachEvent('on' + eventName, function (e) { e.preventDefault = function () {e.returnValue = false;}; e.target = e.srcElement; callback(e); }); }; } function toggle(a, recursive) { var s = a.nextSibling || {}, oldClass = s.className, arrow, newClass; if ('sf-dump-compact' == oldClass) { arrow = '&#9660;'; newClass = 'sf-dump-expanded'; } else if ('sf-dump-expanded' == oldClass) { arrow = '&#9654;'; newClass = 'sf-dump-compact'; } else { return false; } a.lastChild.innerHTML = arrow; s.className = newClass; if (recursive) { try { a = s.querySelectorAll('.'+oldClass); for (s = 0; s < a.length; ++s) { if (a[s].className !== newClass) { a[s].className = newClass; a[s].previousSibling.lastChild.innerHTML = arrow; } } } catch (e) { } } return true; }; return function (root) { root = doc.getElementById(root); function a(e, f) { addEventListener(root, e, function (e) { if ('A' == e.target.tagName) { f(e.target, e); } else if ('A' == e.target.parentNode.tagName) { f(e.target.parentNode, e); } }); }; function isCtrlKey(e) { return e.ctrlKey || e.metaKey; } addEventListener(root, 'mouseover', function (e) { if ('' != refStyle.innerHTML) { refStyle.innerHTML = ''; } }); a('mouseover', function (a) { if (a = idRx.exec(a.className)) { try { refStyle.innerHTML = 'pre.sf-dump .'+a[0]+'{background-color: #B729D9; color: #FFF !important; border-radius: 2px}'; } catch (e) { } } }); a('click', function (a, e) { if (/\bsf-dump-toggle\b/.test(a.className)) { e.preventDefault(); if (!toggle(a, isCtrlKey(e))) { var r = doc.getElementById(a.getAttribute('href').substr(1)), s = r.previousSibling, f = r.parentNode, t = a.parentNode; t.replaceChild(r, a); f.replaceChild(a, s); t.insertBefore(s, r); f = f.firstChild.nodeValue.match(indentRx); t = t.firstChild.nodeValue.match(indentRx); if (f && t && f[0] !== t[0]) { r.innerHTML = r.innerHTML.replace(new RegExp('^'+f[0].replace(rxEsc, '\\$1'), 'mg'), t[0]); } if ('sf-dump-compact' == r.className) { toggle(s, isCtrlKey(e)); } } if (doc.getSelection) { try { doc.getSelection().removeAllRanges(); } catch (e) { doc.getSelection().empty(); } } else { doc.selection.empty(); } } }); var indentRx = new RegExp('^('+(root.getAttribute('data-indent-pad') || ' ').replace(rxEsc, '\\$1')+')+', 'm'), elt = root.getElementsByTagName('A'), len = elt.length, i = 0, t = []; while (i < len) t.push(elt[i++]); elt = root.getElementsByTagName('SAMP'); len = elt.length; i = 0; while (i < len) t.push(elt[i++]); root = t; len = t.length; i = t = 0; while (i < len) { elt = root[i]; if ("SAMP" == elt.tagName) { elt.className = "sf-dump-expanded"; a = elt.previousSibling || {}; if ('A' != a.tagName) { a = doc.createElement('A'); a.className = 'sf-dump-ref'; elt.parentNode.insertBefore(a, elt); } else { a.innerHTML += ' '; } a.title = (a.title ? a.title+'\n[' : '[')+keyHint+'+click] Expand all children'; a.innerHTML += '<span>&#9660;</span>'; a.className += ' sf-dump-toggle'; if ('sf-dump' != elt.parentNode.className) { toggle(a); } } else if ("sf-dump-ref" == elt.className && (a = elt.getAttribute('href'))) { a = a.substr(1); elt.className += ' '+a; if (/[\[{]$/.test(elt.previousSibling.nodeValue)) { a = a != elt.nextSibling.id && doc.getElementById(a); try { t = a.nextSibling; elt.appendChild(a); t.parentNode.insertBefore(a, t); if (/^[@#]/.test(elt.innerHTML)) { elt.innerHTML += ' <span>&#9654;</span>'; } else { elt.innerHTML = '<span>&#9654;</span>'; elt.className = 'sf-dump-ref'; } elt.className += ' sf-dump-toggle'; } catch (e) { if ('&' == elt.innerHTML.charAt(0)) { elt.innerHTML = '&hellip;'; elt.className = 'sf-dump-ref'; } } } } ++i; } }; })(document); </script><style> pre.sf-dump { display: block; white-space: pre; padding: 5px; } pre.sf-dump span { display: inline; } pre.sf-dump .sf-dump-compact { display: none; } pre.sf-dump abbr { text-decoration: none; border: none; cursor: help; } pre.sf-dump a { text-decoration: none; cursor: pointer; border: 0; outline: none; }pre.sf-dump{ color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; word-break: normal}pre.sf-dump .sf-dump-num{font-weight:bold; color:#1299DA}pre.sf-dump .sf-dump-const{font-weight:bold}pre.sf-dump .sf-dump-str{font-weight:bold; color:#56DB3A}pre.sf-dump .sf-dump-note{color:#1299DA}pre.sf-dump .sf-dump-ref{color:#A0A0A0}pre.sf-dump .sf-dump-public{color:#FFFFFF}pre.sf-dump .sf-dump-protected{color:#FFFFFF}pre.sf-dump .sf-dump-private{color:#FFFFFF}pre.sf-dump .sf-dump-meta{color:#B729D9}pre.sf-dump .sf-dump-key{color:#56DB3A}pre.sf-dump .sf-dump-index{color:#1299DA}</style>
<script>
$(function () {

    var storageKey = function () {
        var connection = $('#connections').val();
        return 'la-'+connection+'-history'
    };

    $('#terminal-box').slimScroll({
        height: $('#pjax-container').height() - 205 +'px'
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
                c: $('#connections').val(),
                q: $input.val(),
            _token: LA.token
    },
        success: function (response) {

            history.push($input.val());

            $('#terminal-box')
                .append('<div class="item"><small class="label label-default">'+$('#connections').val()+'> '+$input.val()+'<\/small><\/div>')
                .append('<div class="item">'+response+'<\/div>')
                .slimScroll({ scrollTo: $("#terminal-box")[0].scrollHeight });

                $input.val('');
            }
        });
    };

    $('#terminal-query').on('keyup', function (e) {
        if (e.keyCode == 13 && $(this).val()) {
            send();
        }

        if (e.keyCode == 38) {
            $(this).val(history.up());
        }

        if (e.keyCode == 40) {
            $(this).val(history.down());
        }
    });

    $('#terminal-send').click(function () {
        send();
    });

    $('#terminal-clear').click(function () {
        $('#terminal-box').text('');
        //history.clear();
    });

});
</script>
<!-- Chat box -->
<div class="box box-primary">
    <div class="box-header with-border">
        <i class="fa fa-terminal"></i>

        <div class="box-tools pull-right" data-toggle="tooltip" title="Status">

            <div class="input-group input-group-sm" style="width: 150px;">

                <select name="connection" id="connections" class="form-control pull-right" style="margin-right: 10px;">

                    @if(!empty($connections['dbs']))
                        <optgroup label="dbs">
                        @foreach($connections['dbs'] as $db)
                            <option value="{{$db['value']}}" {{ $db['selected'] ? 'selected':'' }}>{{$db['option']}}</option>
                        @endforeach
                        </optgroup>
                    @endif

                    @if(!empty($connections['redis']))
                        <optgroup label="redis">
                            @foreach($connections['redis'] as $redis)
                                <option value="{{$redis['value']}}">{{$redis['option']}}</option>
                            @endforeach
                        </optgroup>
                    @endif

                </select>
            </div>

        </div>
    </div>
    <div class="box-body chat" id="terminal-box">
        <!-- chat item -->

        <!-- /.item -->
    </div>
    <!-- /.chat -->
    <div class="box-footer with-border">
        <div class="input-group">

            <input class="form-control input-lg" id="terminal-query" placeholder="Type query...">

            <div class="input-group-btn">
                <button type="button" class="btn btn-primary btn-lg" id="terminal-send"><i class="fa fa-paper-plane"></i></button>
            </div>

            <div class="input-group-btn">
                <button type="button" class="btn btn-warning btn-lg" id="terminal-clear"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    </div>
</div>
<!-- /.box (chat box) -->