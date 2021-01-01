<div class="card card-@color card-outline">
    <div class="card-header">
        <h3 class="card-title">Dependencies</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
    </div>

    <!-- /.card-header -->
    <div class="card-body dependencies">
        <div class="table-responsive">
            <table class="table table-striped">
                @foreach($dependencies as $dependency => $version)
                <tr>
                    <td width="240px">{{ $dependency }}</td>
                    <td><span class="label label-@color">{{ $version }}</span></td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.card-body -->
</div>
