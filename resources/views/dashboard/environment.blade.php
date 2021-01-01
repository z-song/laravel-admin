<div class="card card-@color card-outline">
    <div class="card-header">
        <h3 class="card-title">Environment</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <!-- /.card-header -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">

                @foreach($envs as $env)
                <tr>
                    <td width="120px">{{ $env['name'] }}</td>
                    <td>{{ $env['value'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.card-body -->
</div>
