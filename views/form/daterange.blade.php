<div class="form-group {!! $startVars['errors']->has($startVars['errorKey']) || $endVars['errors']->has($endVars['errorKey']) ? 'has-error' : ''  !!}">

    <label for="{{$startVars['id']}}" class="col-sm-{{$width['label']}} control-label">{{$label}}</label>

    <div class="col-sm-{{$width['field']}}">

        @include('admin::form.error')

        <div class="row" style="width: 370px">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="{{$startVars['name']}}" value="{{ old($startVars['column'], $startVars['value']) }}" class="form-control {{$startVars['class']}}" style="width: 150px" {!! $startVars['attributes'] !!} />
                </div>
            </div>

            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="{{$endVars['name']}}" value="{{ old($endVars['column'], $endVars['value']) }}" class="form-control {{$endVars['class']}}" style="width: 150px" {!! $endVars['attributes'] !!} />
                </div>
            </div>
        </div>

        @include('admin::form.help-block')

    </div>
</div>
