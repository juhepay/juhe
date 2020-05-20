{{csrf_field()}}
<div class="form-group">
    <label class="col-md-2 control-label">账户实名</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="real_name"  autocomplete="off" placeholder="请输入账户实名"  value="{{$bankcard->real_name??''}}"  required>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">银行卡号</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="card_no" autocomplete="off" placeholder="请输入银行卡号" value="{{$bankcard->card_no ??''}}"   required >
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">银行名称</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="bank_name"  autocomplete="off" placeholder="请输入银行名称"  value="{{$bankcard->bank_name??''}}" required>
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-2 col-md-4">
        <button type="submit" class="btn btn-primary">
            <span class="glyphicon glyphicon-saved"></span>&nbsp;提交&nbsp;
        </button>
        <button type="button" class="btn btn-primary jumpbutton">
            <span class="glyphicon glyphicon-arrow-left"></span>&nbsp;返回
        </button>
    </div>
</div>

