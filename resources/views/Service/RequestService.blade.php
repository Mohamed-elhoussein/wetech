@extends('layout.store')
@section('content')
<div class="_service"></div>
<form style="width: 80%;margin: auto; padding: 20px;" class="form_services" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">رقم الهاتف</label>
    <input type="text" name="phone" class="form-control phone" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="رقم الهاتف">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">نوع الجهاز</label>
    <input type="text" name="phone_type" class="form-control phone_type" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="نوع الجهاز">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">الموديل</label>
    <input type="text" class="form-control model" name="model" id="exampleInputPassword1" placeholder="الموديل">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">اللون</label>
    <input type="text" name="color"  class="form-control color" id="exampleInputPassword1" placeholder="اللون">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">المشكله</label>
    <input type="text" name="problem"  class="form-control problem" id="exampleInputPassword1" placeholder="وصف المشكله">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">المدينه</label>
    <input type="text" name="city"  class="form-control city" id="exampleInputPassword1" placeholder="المدينه">
  </div>

      <div class="form-group">
    <label for="exampleFormControlTextarea1">ملاحظات</label>
    <textarea class="form-control comments" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>

  <button type="submit" class="btn btn-primary">ارسال الطلب</button>
</form>

@endsection

