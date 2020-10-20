@extends("layouts.web")
@section("title","অভিযোগ করুন")
@section("content")

      <section class="shop-single section-padding pt-3">
         <div class="container">
           <div class="row">
              <div class="col-lg-11 mx-auto">
                 <div class="row no-gutters">
                    
                    {{-- customer menu added --}}
                    @include("web.customer_menu")
                    
                     <div class="col-md-9">
                        <div class="card card-body account-right">
                            <div class="widget">
                               <div class="section-header">
                                  <h5 class="heading-design-h5">
                                     অভিযোগ করুন
                                  </h5>
                               </div>
                               <div class="row">
                                  <div class="col-sm-8">
                                    <form id="ComplainBoxForm" method="post" action="javascript:void(0)">
                                        <div class="form-group">
                                          <label>অর্ডার আইডি</label>
                                          <input type="text" class="form-control" name="order_id" id="order_id" placeholder="অর্ডার আইডি দিন">
                                        </div>
                                        <div class="form-group">
                                          <label>সরবরাহকারী</label>
                                          <select id="supplier_id" class="form-control" name="supplier_id">
                                            <option value="">সরবরাহকারী নির্বাচন করুন</option>
                                          </select>
                                        </div>

                                        <div class="form-group">
                                          <label>অভিযোগ</label>
                                          <textarea id="message" name="message" class="form-control" placeholder="অভিযোগ লিখুন"></textarea>
                                        </div>

                                        <center>
                                          <button class="btn btn-info" type="submit">অভিযোগ করুন</button>
                                        </center>

                                    </form>
                                  </div>
                                  <div class="col-sm-4">
                                    <table class="table table-bordered order-table d-none">
                                    <thead>
                                      <tr>
                                        <th width="40%">সরবরাহকারী</th>
                                        <th width="60%">পন্যসমূহ</th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                   </table>
                                  </div>
                               </div>
                            </div>
                         </div>
                    </div>
                   
                 </div>
              </div>
           </div>
        </div>
      </section>

@endsection
@section('js')
<script type="text/javascript">
   $.ajaxSetup({
      headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
    });
  $(document).on('change','#order_id',function(){
    let id = $("#order_id").val() || 0;
    if(id != 0){
      $.ajax({
        url:"{{route('order_tracking_data')}}",
        dataType:"JSON",
        data:{
          order_id: id
        },
        method:"POST",
        success:function(res){
          if(res.status == 'success'){
            $('.order-table tbody').html('');
            let htmldata = htmlGenerate(res.data);
            $('.order-table tbody').html(htmldata);
            $('#supplier_id').html(pushSupplierToSelectBox(res.data));   
            $('.order-table').removeClass('d-none');   
          }else{
            swal('কিছু ভুল হয়েছে',res.message,res.status);   
            $('.order-table tbody').html('');   
            $('.order-table').addClass('d-none');   
          }
        }
      });
    }else{
      swal('আবশ্যক','অর্ডার আইডি আবশ্যক','error');
    }
  });
  function htmlGenerate(data){
    var html='';
    data.forEach(function(item){
       html+='<tr>';
       let product = '';
       item.products.forEach(function(obj){
        product+=obj+',';
       });
       html+='<td>';
       html+=item.supplier;
       html+='</td>';
       html+='<td>'+product;
       html+='</td>';

       html+='</tr>';
    });
    return html;
  }
  function pushSupplierToSelectBox(data){
    var html = '<option value="">সাপ্লায়ার নির্বাচন করুন</option>';
    data.forEach(function(item){
      html+='<option value="'+item.supplier_id+'" >'+item.supplier+'</option>';
    });
    return html;
  }
  $(document).on('submit','#ComplainBoxForm',function(e){
    e.preventDefault();
    let order_id = $("#order_id").val() || 0;
    let supplier_id = $("#supplier_id").val() || '';
    let message = $("#message").val() || 0;
    if(order_id == 0){
      toastr.error('অর্ডার আইডি আবশ্যক');
    }
    if(supplier_id == ''){
      toastr.error('সাপ্লায়ার আবশ্যক');
    }
    if(message == 0){
      toastr.error('আপনার অভিযোগ আবশ্যক');
    }

    $.ajax({
      url: "{{route('web.complain_submit')}}",
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function (response) {
        if(response.status == 'success'){
          toastr.success(response.msg);
          $("#ComplainBoxForm")[0].reset();
        }
        else{
          toastr.error('কোন ভুল হয়েছে');
        }
      },
    });

  });
</script>
@endsection

