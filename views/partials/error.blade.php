@if($errors->hasBag('_exception_'))
    <?php $error = $errors->getBag('_exception_');?>
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-warning"></i><i style="border-bottom: 1px dotted #fff;cursor: pointer;" title="{{ $error->get('type')[0] }}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ class_basename($error->get('type')[0]) }}</i>
            In <i title="{{ $error->get('file')[0] }} line {{ $error->get('line')[0] }}" style="border-bottom: 1px dotted #fff;cursor: pointer;" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ basename($error->get('file')[0]) }} line {{ $error->get('line')[0] }}</i> :</h4>
        <p>{{ $error->get('message')[0] }}</p>
    </div>
@endif